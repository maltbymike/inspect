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
        $this->createUser('Mike Maltby', 'mike@ingersollrentall.ca', ['super_admin', 'panel_user']);
        $this->createUser('Item Inspector', 'mike+inspector@ingersollrentall.ca', ['panel_user', 'item_inspector']);
    }

    public function createUser(string $name, string $email, array|string $roles)
    {
        $user = User::factory()->withPersonalTeam()->create([
            'name' => $name,
            'email' => $email,
        ]);

        $user->assignRole($roles);
    }
}
