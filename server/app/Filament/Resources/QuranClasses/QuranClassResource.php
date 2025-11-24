<?php

namespace App\Filament\Resources\QuranClasses;

use App\Filament\Resources\QuranClasses\Pages\CreateQuranClass;
use App\Filament\Resources\QuranClasses\Pages\EditQuranClass;
use App\Filament\Resources\QuranClasses\Pages\ListQuranClasses;
use App\Filament\Resources\QuranClasses\Pages\ViewQuranClass;
use App\Filament\Resources\QuranClasses\Schemas\QuranClassForm;
use App\Filament\Resources\QuranClasses\Schemas\QuranClassInfolist;
use App\Filament\Resources\QuranClasses\Tables\QuranClassesTable;
use App\Models\QuranClass;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class QuranClassResource extends Resource
{
    protected static ?string $model = QuranClass::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return QuranClassForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return QuranClassInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return QuranClassesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListQuranClasses::route('/'),
            'create' => CreateQuranClass::route('/create'),
            'view' => ViewQuranClass::route('/{record}'),
            'edit' => EditQuranClass::route('/{record}/edit'),
        ];
    }
}
