<?php
namespace App\Livewire;

use App\Models\Settings;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Livewire\Component;

class SendEmailNotificationSetting extends Component implements HasSchemas
{
    use InteractsWithSchemas;

    /**
     * @var array<string, mixed> | null
     */
    public ?array $data = [];

    public function mount(){
        $this->form->fill($this->getRecord()?->attributesToArray());
    }

    public function render()
    {
        return view('livewire.send-email-notification-setting');
    }

    public function getRecord()
    {
        return Settings::query()
            ->where('name', 'send_email_notification')
            ->first();
    }

    public function form(Schema $schema) : Schema {
        return $schema
            ->components([
               Form::make([
                    Section::make('Emails')
                        ->description('Opciones para el envío de notificaciones por email')
                        ->aside()
                        ->schema([
                            TextInput::make('name')
                                ->label('Nombre')
                                ->maxLength(255)
                                ->disabled(),
                            ToggleButtons::make('value')
                                ->label('Valor')
                                ->boolean()
                                ->grouped(),
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
}
