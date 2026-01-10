<?php

namespace App\Filament\Resources\Customers\RelationManagers;

use App\Filament\Resources\Incomes\IncomeResource;
use App\Models\Device;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Icon;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SupportsRelationManager extends RelationManager
{
    protected static string $relationship = 'supports';
    protected static ?string $title = 'Soportes';

    public static function getModelLabel(): string
    {
        return "Soportes";
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('date')
                    ->label('Fecha')
                    ->default(now())
                    ->required(),
                Textarea::make('detail')
                    ->default(null)
                    ->label('Detalle')
                    ->autosize()
                    ->rows(1),
                Textarea::make('comments')
                    ->default(null)
                    ->label('Comentarios')
                    ->autosize()
                    ->rows(1),
                Select::make("establishment_id")
                    ->options(fn () =>  $this->getOwnerRecord()->establishments->pluck('name', 'id'))
                    ->label('Establecimiento')
                    ->reactive()
                    ->default(null)
                    ->afterStateUpdated(function ($state, callable $set) {
                        $set('device_id', null);
                    })
                    ->native(false),
                Select::make("device_id")
                    ->options(function (callable $get) {
                        $establishment_id = $get('establishment_id');
                        return $establishment_id ? Device::where('establishment_id', $establishment_id)->pluck('description', 'id') : [];
                    })
                    ->label('Dispositivo')
                    ->reactive()
                    ->default(null)
                    ->native(false),
                TextInput::make('total')
                    ->required()
                    ->numeric()
                    ->prefix('$')
                    ->label('Total')
                    ->default('0.00'),
                Select::make('payment_status')
                    ->options(['paid' => 'Pagada', 'pending' => 'Pendiente', 'partial' => 'Parcial'])
                    ->default('pending')
                    ->required()
                    ->label('Estado de pago')
                    ->visible(fn ($operation) => $operation === 'create')
                    ->native(false),
                FileUpload::make('attached_file')
                    ->label('Adjunto')
                    ->disk('public')
                    ->directory('support-attachments')
                    ->visibility('public')
                    ->preserveFilenames()
                    ->aboveContent([
                        Icon::make(Heroicon::BellAlert),
                        'Admite archivos PDF e imágenes JPEG y PNG'
                    ])
                    ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png',])
                    ->maxSize(1024)
                    ->openable()
                    ->moveFiles(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('number')
            ->columns([
                TextColumn::make('date')
                    ->date()
                    ->label('Fecha')
                    ->sortable(),
                TextColumn::make('number')
                    ->searchable()
                    ->label('Número'),
                TextColumn::make('detail')
                    ->searchable()
                    ->label('Detalle')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('comments')
                    ->searchable()
                    ->label('Comentarios')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('establishment.name')
                    ->label('Establecimiento')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('device.name')
                    ->label('Dispositivo')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('total')
                    ->prefix('$')
                    ->label('Total')
                    ->weight('bold'),
                TextColumn::make('incomes.total')
                    ->label('Pagos')
                    ->listWithLineBreaks()
                    ->prefix('$')
                    ->alignment('right')
                    ->url(fn($state):string =>IncomeResource::getUrl('view', ['record' => $state]))
                    ->placeholder('$0.00'),
                TextColumn::make('attached_file')
                    ->label('Adjunto')
                    ->formatStateUsing(function ($state) {
                        if ($state !== null) {
                            $symbol = "/";
                            $position = strpos($state, $symbol);
                            if ($position !== false) {
                                $result = substr($state, $position + 1);
                                return $result;
                            }
                        }
                    })
                    ->url(fn ($record) => Storage::url($record->attached_file), true) // El segundo argumento 'true' indica que se descargará
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->placeholder('-'),
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
                    ->label('Creado')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->label('Modificado')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
            ])
            ->headerActions([
                CreateAction::make()
                ->mutateDataUsing(function (array $data): array {
                    $data['number'] = \Carbon\Carbon::now()->format('Ymdhis');

                    return $data;
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
                                ->body('No se puede eliminar este soporte porque tiene ingresos relacionados.')
                                ->persistent()
                                ->send();
                        
                            $action->cancel();
                        }
                    }),
            ])
            ->recordUrl(
                fn (Model $record): string => route('filament.admin.resources.supports.view', ['record' => $record])
            )
            ->defaultPaginationPageOption(5)
            ->paginated([5, 10, 25]);
    }

    public function isReadOnly(): bool
    {
        return false;
    }
}
