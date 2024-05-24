<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DevUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->withPersonalTeam()->create([
            'name' => 'Mike Maltby',
            'email' => 'mike@ingersollrentall.ca',
        ]);
    }
}
