<x-filament-panels::page>
    {{ $this->itemInfolist }}
    
    @livewire('items.list-item-inspections', ['item' => $record->id])
</x-filament-panels::page>
