<?php

namespace App\Filament\Resources\ItemTemplateResource\Pages;

use App\Filament\Resources\ItemTemplateResource;
use pxlrbt\FilamentActivityLog\Pages\ListActivities;

class EditHistory extends ListActivities
{
    protected static string $resource = ItemTemplateResource::class;


    public function getBreadcrumbs(): array
    {
        return [
            route('filament.admin.resources.item-templates.index') => __('Item Inspection Template'),
            route('filament.admin.resources.item-templates.edit', ['record' => $this->record]) => $this->record->template->name,
            'Edit History',
        ];
    }

    public function getTitle(): string
    {
        return __('Item Inspection Template Edit History');
    }
}
