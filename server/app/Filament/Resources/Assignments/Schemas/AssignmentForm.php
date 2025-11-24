<?php

namespace App\Filament\Resources\Assignments\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AssignmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                Textarea::make('description')
                    ->default(null)
                    ->columnSpanFull(),
                Select::make('teacher_id')
                    ->relationship('teacher', 'id')
                    ->required(),
                Select::make('student_id')
                    ->relationship('student', 'id')
                    ->default(null),
                Select::make('class_id')
                    ->relationship('class', 'name')
                    ->default(null),
                Select::make('type')
                    ->options(['reading' => 'Reading', 'revision' => 'Revision', 'memorizing' => 'Memorizing'])
                    ->required(),
                Textarea::make('pages')
                    ->required()
                    ->columnSpanFull(),
                DatePicker::make('assigned_date')
                    ->required(),
                DatePicker::make('due_date'),
                TextInput::make('week_number')
                    ->numeric()
                    ->default(null),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
