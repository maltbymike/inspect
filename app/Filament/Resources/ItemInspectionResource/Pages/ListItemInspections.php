<?php

namespace App\Filament\Resources\ItemInspectionResource\Pages;

use App\Filament\Resources\ItemInspectionResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListItemInspections extends ListRecords
{
    protected static string $resource = ItemInspectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'Pending' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query
                    ->where('started_at', null)
                ),
            'In Process' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query
                    ->where('completed_at', null)
                    ->where('started_at', '!=', null)
                ),
            'Completed' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query
                    ->where('completed_at', '!=', null)
                ),
        ];
    }
}
