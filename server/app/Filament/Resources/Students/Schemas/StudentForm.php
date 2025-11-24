<?php

namespace App\Filament\Resources\Students\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Schema;

class StudentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                TextInput::make('password')
                    ->password()
                    ->required()
                    ->maxLength(255)
                    ->hiddenOn('edit'),
                TextInput::make('phone')
                    ->tel()
                    ->maxLength(255),
                DatePicker::make('date_of_birth'),
                Select::make('teacher_id')
                    ->relationship('teacher', 'id')
                    ->required(),
                Select::make('class_id')
                    ->relationship('class', 'name')
                    ->default(null),
                TextInput::make('student_id')
                    ->default(null),
                TextInput::make('guardian_name')
                    ->default(null),
                TextInput::make('guardian_phone')
                    ->tel()
                    ->default(null),
                Textarea::make('notes')
                    ->default(null)
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
