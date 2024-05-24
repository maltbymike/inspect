<?php

namespace App\Livewire\Items;

use Livewire\Component;

class Item extends Component
{
    public function render()
    {
        return view('livewire.items.item', [
            'items' => \App\Models\Items\Item::all(),
        ]);
    }
}
