<?php

namespace App\Filament\Resources\ItemResource\Pages;

use Filament\Actions;
use App\Filament\Resources\ItemResource;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Imports\Items\ItemImporter;

class ListItems extends ListRecords
{
    protected static string $resource = ItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\ActionGroup::make([
                Actions\ImportAction::make()
                    ->visible(fn ($livewire): bool => $livewire instanceof ListItems)
                    ->importer(ItemImporter::class),
            ]),
        ];
    }
}
