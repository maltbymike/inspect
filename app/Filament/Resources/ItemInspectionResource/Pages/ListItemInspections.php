<?php

namespace App\Filament\Resources\ItemInspectionResource\Pages;

use App\Filament\Resources\ItemInspectionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListItemInspections extends ListRecords
{
    protected static string $resource = ItemInspectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
