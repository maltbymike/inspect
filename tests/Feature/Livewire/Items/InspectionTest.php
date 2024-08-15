<?php

namespace Tests\Feature\Livewire\Items;

use App\Livewire\Items\Inspection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class InspectionTest extends TestCase
{
    public function renders_successfully()
    {
        Livewire::test(Inspection::class)
            ->assertStatus(200);
    }
}
