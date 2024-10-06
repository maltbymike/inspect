<?php

namespace App\Filament\Resources\ItemTemplateResource\Pages;

use App\Filament\Resources\ItemTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListItemTemplates extends ListRecords
{
    protected static string $resource = ItemTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
