<?php

namespace App\Filament\Resources\ItemTemplateTypeResource\Pages;

use App\Filament\Resources\ItemTemplateTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListItemTemplateTypes extends ListRecords
{
    protected static string $resource = ItemTemplateTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
