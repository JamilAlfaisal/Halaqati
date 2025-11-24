<?php

namespace App\Filament\Resources\Students\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class StudentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                    ->label('Name'),
                TextEntry::make('email')
                    ->label('Email'),
                TextEntry::make('phone')
                    ->label('Phone')
                    ->placeholder('-'),
                TextEntry::make('date_of_birth')
                    ->label('Date of Birth')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('teacher.id')
                    ->label('Teacher'),
                TextEntry::make('class.name')
                    ->label('Class')
                    ->placeholder('-'),
                TextEntry::make('student_id')
                    ->placeholder('-'),
                TextEntry::make('guardian_name')
                    ->placeholder('-'),
                TextEntry::make('guardian_phone')
                    ->placeholder('-'),
                TextEntry::make('notes')
                    ->placeholder('-')
                    ->columnSpanFull(),
                IconEntry::make('is_active')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
