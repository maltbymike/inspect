<?php

namespace Tests\Feature\Livewire\Items;

use App\Livewire\Items\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class ItemTest extends TestCase
{
    public function renders_successfully()
    {
        Livewire::test(Item::class)
            ->assertStatus(200);
    }
}
