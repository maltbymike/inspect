<?php

namespace App\Filament\Resources\ItemInspectionResource\Pages;

use App\Filament\Resources\ItemInspectionResource;
use Filament\Resources\Pages\Page;
use pxlrbt\FilamentActivityLog\Pages\ListActivities;

class EditHistory extends ListActivities
{
    protected static string $resource = ItemInspectionResource::class;

    public function getBreadcrumbs(): array
    {
        return [
            route('filament.admin.resources.item-inspections.index') => __('Item Inspections'),
            route('filament.admin.resources.item-inspections.edit', ['record' => $this->record]) => $this->record->id,
            $this->getTitle(),
        ];
    }

    public function getTitle(): string
    {
        return __('Item Edit History');
    }
}
