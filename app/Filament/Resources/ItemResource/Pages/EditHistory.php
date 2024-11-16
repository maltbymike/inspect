<?php

namespace App\Filament\Resources\ItemResource\Pages;

use App\Filament\Resources\ItemResource;
use Filament\Resources\Pages\Page;
use pxlrbt\FilamentActivityLog\Pages\ListActivities;

class EditHistory extends ListActivities
{
    protected static string $resource = ItemResource::class;

    public function getBreadcrumbs(): array
    {
        return [
            route('filament.admin.resources.items.index') => __('Items'),
            route('filament.admin.resources.items.edit', ['record' => $this->record]) => $this->record->reference,
            $this->getTitle(),
        ];
    }

    public function getTitle(): string
    {
        return __('Item Edit History');
    }

}
