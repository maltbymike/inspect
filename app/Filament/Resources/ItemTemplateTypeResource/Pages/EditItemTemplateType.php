<?php

namespace App\Filament\Resources\ItemTemplateTypeResource\Pages;

use App\Filament\Resources\ItemTemplateTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditItemTemplateType extends EditRecord
{
    protected static string $resource = ItemTemplateTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
