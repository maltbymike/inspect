<?php

namespace Tests\Feature\Livewire\Items;

use App\Livewire\Items\ListItems;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class ListItemsTest extends TestCase
{
    /** @test */
    public function renders_successfully()
    {
        Livewire::test(ListItems::class)
            ->assertStatus(200);
    }
}
