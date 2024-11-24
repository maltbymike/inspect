<?php

namespace App\Filament\Resources\ItemResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use Filament\Infolists\Infolist;
use App\Filament\Resources\ItemResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Components\TextEntry;

class ViewItem extends ViewRecord
{
    protected static string $resource = ItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('edit')
                ->disabled(fn () => ! auth()->user()->can('update_item'))
                ->url(route('filament.admin.resources.items.edit', ['record' => $this->record])),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->columns(2)
            ->schema([
                TextEntry::make('reference'),
                TextEntry::make('name'),
            ]);
    }
}
