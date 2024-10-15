<?php

namespace App\Filament\Resources\ItemResource\Pages;

use App\Filament\Resources\ItemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditItem extends EditRecord
{
    protected static string $resource = ItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('view')
                ->url(route('filament.admin.pages.items.view', ['id' => $this->record])),
            Actions\DeleteAction::make(),
        ];
    }
}
