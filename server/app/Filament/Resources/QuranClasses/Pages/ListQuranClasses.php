<?php

namespace App\Filament\Resources\QuranClasses\Pages;

use App\Filament\Resources\QuranClasses\QuranClassResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListQuranClasses extends ListRecords
{
    protected static string $resource = QuranClassResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
