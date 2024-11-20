<?php

namespace App\Filament\Resources\ItemResource\Pages;

use App\Filament\Resources\ItemResource;
use App\Models\Items\Item;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditItem extends EditRecord
{
    protected static string $resource = ItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('view')
                ->disabled(fn (Item $record) => $record->trashed())
                ->url(route('filament.admin.resources.items.view', ['record' => $this->record])),
                // ->url(route('filament.admin.pages.items.view', ['id' => $this->record])),
            Actions\DeleteAction::make()
                ->label('Make Inactive'),
            Actions\RestoreAction::make()
                ->label('Make Active')
                ->color('success'),
        ];
    }
}
