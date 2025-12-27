<?php

namespace App\Filament\Pages;

use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use App\Models\Settings;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Form;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class SeettingsPage extends Page
{
    use HasPageShield;

    protected string $view = 'filament.pages.seettings-page';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedWrenchScrewdriver;
    protected static string|UnitEnum|null $navigationGroup = 'Opciones';
    protected static ?string $navigationLabel = 'Configuración';
    protected static ?string $title = 'Configuración';

    /**
     * @var array<string, mixed> | null
     */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->getRecord()?->attributesToArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([
                    Section::make('Notificaciones')
                        ->description('Opciones para el envío de notificaciones')
                        ->aside()
                        ->schema([
                            TextInput::make('name')
                                ->label('Nombre')
                                ->maxLength(255)
                                ->disabled(),
                            TextInput::make('value')
                                ->label('Valor')
                                ->numeric()
                                ->required(),
                            TextInput::make('description')
                                ->label('Descripción')
                                ->required()
                                ->disabled(),
                            DatePicker::make('updated_at')
                                ->label('Modificado')
                                ->disabled()
                        ])
                ])
                ->livewireSubmitHandler('save')
                ->footer([
                    Actions::make([
                        Action::make('Guardar')
                            ->submit('save'),
                    ])->alignRight(),
                ]),
            ])
            ->record($this->getRecord())
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();
        
        $record = $this->getRecord();
        
        if (! $record) {
            $record = new Settings();
            $record->name = $data['name'];
        }
        
        $record->fill($data);
        $record->save();
        
        if ($record->wasRecentlyCreated) {
            $this->form->record($record)->saveRelationships();
        }

        Notification::make()
            ->success()
            ->title('Configuración guardada!')
            ->send();
    }

    public function getRecord(): ?Settings
    {
        return Settings::query()
            ->where('status', true)
            ->first();
    }
}
