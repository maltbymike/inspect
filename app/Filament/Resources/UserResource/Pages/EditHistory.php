<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Traits\HasCorrectedCreateFieldLabelMap;
use App\Filament\Resources\UserResource;
use pxlrbt\FilamentActivityLog\Pages\ListActivities;

class EditHistory extends ListActivities
{
    use HasCorrectedCreateFieldLabelMap;

    protected static string $resource = UserResource::class;

    public function getBreadcrumbs(): array
    {
        return [
            route('filament.admin.resources.users.index') => __('Users'),
            route('filament.admin.resources.users.edit', ['record' => $this->record]) => $this->record->name,
            $this->getTitle(),
        ];
    }

    public function getTitle(): string
    {
        return __('User Edit History');
    }
}
