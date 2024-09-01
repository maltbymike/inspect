<?php

namespace App\Livewire\Items;

use Livewire\Component;
use Filament\Tables\Table;

class Item extends Component
{
    public function table(Table $table): Table
    {
        return $table;
    }
    
    public function render()
    {
        return view('livewire.items.item');
    }
}
