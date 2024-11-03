<?php

namespace App\Filament\Imports\Items;

use App\Models\Items\Item;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class ItemImporter extends Importer
{
    protected static ?string $model = Item::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('reference')
                ->requiredMapping()
                // ->rules(['required']),
                ->rules(['required', 'unique:App\Models\Items\Item,reference']),
            ImportColumn::make('name')
                ->requiredMappingForNewRecordsOnly()
                ->rules(['required']),
        ];
    }

    public function resolveRecord(): ?Item
    {

        return Item::firstOrNew([
            // Update existing records, matching them by `$this->data['column_name']`
            'reference' => $this->data['reference'],
        ]);

        // return new Item();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your item import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
