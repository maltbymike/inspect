<?php

namespace App\Filament\Resources\ItemInspectionResource\Pages;

use App\Filament\Resources\ItemInspectionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditItemInspection extends EditRecord
{
    protected static string $resource = ItemInspectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
