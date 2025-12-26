<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->label('Nombre'),
                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required(),
                DateTimePicker::make('email_verified_at')
                    ->label('Verificado'),
                TextInput::make('password')
                    ->password()
                    ->required()
                    ->label('Contraseña'),
                CheckboxList::make('roles')
                    ->relationship('roles', 'name')
                    ->searchable(),
                            ]);
    }
}
