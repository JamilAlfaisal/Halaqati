<?php

namespace App\Filament\Resources\QuranClasses\Pages;

use App\Filament\Resources\QuranClasses\QuranClassResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditQuranClass extends EditRecord
{
    protected static string $resource = QuranClassResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
