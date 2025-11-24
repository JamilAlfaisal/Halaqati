<?php

namespace App\Filament\Resources\QuranClasses\Pages;

use App\Filament\Resources\QuranClasses\QuranClassResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewQuranClass extends ViewRecord
{
    protected static string $resource = QuranClassResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
