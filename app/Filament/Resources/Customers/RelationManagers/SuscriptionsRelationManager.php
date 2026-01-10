<?php

namespace App\Filament\Resources\Customers\RelationManagers;

use App\Filament\Resources\Incomes\IncomeResource;
use App\Mail\SuscriptionActivated;
use App\Models\Plan;
use App\Models\Settings;
use App\Models\Suscription;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class SuscriptionsRelationManager extends RelationManager
{
    protected static string $relationship = 'suscriptions';
    protected static ?string $title = 'Suscripciones';

    public static function getModelLabel(): string
    {
        return "Suscripciones";
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('start_date')
                    ->required()
                    ->label('Inicia')
                    ->default(now()),
                Select::make('plan_id')
                    ->relationship('plan', 'name')
                    ->default(null)
                    ->label('Plan')
                    ->required()
                    ->native(false)
                    ->afterStateUpdated(function($state, callable $set){
                        $set('price', $state);
                    })
                    ->reactive(),
                Select::make('price')
                    ->relationship('plan', 'price')
                    ->label('Total')
                    ->prefix('$')
                    ->disabled(),
                Textarea::make('description')
                    ->label('Descripción')
                    ->autosize()
                    ->rows(1),
                Select::make('status')
                    ->options(['active' => 'Activo', 'inactive' => 'Inactivo'])
                    ->default('active')
                    ->required()
                    ->label('Estado')
                    ->native(false),
                Select::make('payment_status')
                    ->options(['paid' => 'Pagada', 'pending' => 'Pendiente', 'partial' => 'Parcial'])
                    ->default('pending')
                    ->required()
                    ->label('Estado de pago')
                    ->visible(fn ($operation) => $operation === 'create')
                    ->native(false),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('number')
            ->columns([
                TextColumn::make('number')
                    ->sortable()
                    ->searchable()
                    ->label('Número'),
                TextColumn::make('start_date')
                    ->date()
                    ->sortable()
                    ->label('Inicia'),
                TextColumn::make('end_date')
                    ->date()
                    ->sortable()
                    ->label('Termina'),
                TextColumn::make('plan.name')
                    ->description(fn ($record) :string =>'$ ' . $record->plan->price)
                    ->sortable(),
                TextColumn::make('incomes.total')
                    ->label('Pagos')
                    ->listWithLineBreaks()
                    ->alignment('right')
                    ->url(fn($state):string =>IncomeResource::getUrl('view', ['record' => $state]))
                    ->placeholder('$0.00')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->prefix('$'),
                TextColumn::make('description')
                    ->searchable()
                    ->label('Descripción')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('status')
                    ->badge()
                    ->label('Estado')
                    ->alignCenter()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                    })
                    ->formatStateUsing(function ($state) {
                        if ($state == 'active') {
                            return 'Activo';
                        }else {
                            return 'Inactivo';
                        }
                    }),
                TextColumn::make('payment_status')
                    ->badge()
                    ->label('Estado de pago')
                    ->alignCenter()
                    ->color(fn (string $state): string => match ($state) {
                        'paid' => 'success',
                        'pending' => 'danger',
                        'partial' => 'info',
                    })
                    ->formatStateUsing(function ($state) {
                        if ($state == 'paid') {
                            return 'Pagado';
                        }elseif($state == 'partial') {
                            return 'Parcial';
                        }else {
                            return 'Pendiente';
                        }
                    }),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Creado')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Modificado')
                    ->toggleable(isToggledHiddenByDefault: true)
            ])
            ->headerActions([
                CreateAction::make()
                 ->mutateDataUsing(function (array $data): array {
                    $data['number'] = \Carbon\Carbon::now()->format('Ymdhis');

                    // calcula la fecha en la que termina la suscripcion 
                    // a partir de la fecha de incio y los meses del plan
                    $start_date_input = $data['start_date'];

                    //obtiene la cantidad de meses del plan seleccionado en el input
                    $plan = Plan::findOrFail($data['plan_id']);
                
                    $months = 0;
                    if ($plan) {
                        $months = $plan->months;
                    }
                    
                    $start_date = \Carbon\Carbon::parse($start_date_input);
                    $end_date = $start_date->addMonth($months);
                    $data['end_date'] = $end_date->toDateString();

                    return $data;
                })
                ->after(function (Suscription $record) {
                    $suscription = $record;
        
                    Notification::make()
                        ->title("Suscripción {$suscription->number} creada correctamente!")
                        ->success()
                        ->send();

                    $send_email_suscription = (bool)Settings::where('name', 'send_email_notification')->value('value');

                    //envio de email 
                    if ($send_email_suscription) {
                        $customer_mail = "";
                        if ($suscription->customer->email !== null) {
                            if (filter_var($suscription->customer->email, FILTER_VALIDATE_EMAIL)) {
                                $customer_mail = $suscription->customer->email;
                                $copy_recipient = Settings::where('name', 'mail_copy_recipient')->value('value');
                                $email = Mail::to($customer_mail)
                                            ->bcc($copy_recipient)
                                            ->queue(new SuscriptionActivated($suscription));
                                
                                if ($email) {
                                    Notification::make()
                                    ->title("Email enviado!")
                                    ->success()
                                    ->send();
                                }else {
                                    Notification::make()
                                    ->title("Error al enviar email")
                                    ->body('Hubo un error al enviar el email.')
                                    ->warning()
                                    ->send();
                                }
                            }else {
                                Notification::make()
                                ->title("Error al enviar email")
                                ->body('Email no válido o inexistente.')
                                ->warning()
                                ->send();
                            }
                        }else {
                                Notification::make()
                                ->title("Error al enviar email")
                                ->body('Email no válido o inexistente.')
                                ->warning()
                                ->send();
                        }
                    }
                })
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                ->before(function (DeleteAction $action, $record) {
                        if ($record->incomes()->exists()) {
                            Notification::make()
                            ->warning()
                                ->title('Acción no válida!')
                                ->body('No se puede eliminar este suscripción porque tiene ingresos relacionados.')
                                ->persistent()
                                ->send();
                        
                            $action->cancel();
                        }
                    }),
            ])
            ->recordUrl(
                fn (Model $record): string => route('filament.admin.resources.suscriptions.view', ['record' => $record])
            )
            ->defaultPaginationPageOption(5)
            ->paginated([5, 10, 25]);
    }

    public function isReadOnly(): bool
    {
        return false;
    }
}
