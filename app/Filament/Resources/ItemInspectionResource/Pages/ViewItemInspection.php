<?php

namespace App\Filament\Resources\ItemInspectionResource\Pages;

use App\Filament\Resources\ItemInspectionResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewItemInspection extends ViewRecord
{
    protected static string $resource = ItemInspectionResource::class;
 
    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
