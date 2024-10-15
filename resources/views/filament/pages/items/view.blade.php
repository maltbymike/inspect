<x-filament-panels::page>    

    {{ $this->itemInfolist }}
    
    @livewire('items.list-item-inspections', ['item' => $record->id])
    @livewire('items.list-item-inspection-templates', ['item' => $record->id])
</x-filament-panels::page>
