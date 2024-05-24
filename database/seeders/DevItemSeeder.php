<?php

namespace Database\Seeders;

use App\Models\Items\Item;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DevItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $headers = [
            [
                'reference' => '1001',
                'name' => '2HP Electric Air Compressor',
                'children' => 4,
            ],
            [
                'reference' => '1505',
                'name' => 'Breaker Electric 65lb',
                'children' => 5,
            ],
            [
                'reference' => '3813',
                'name' => 'Artic. Lift, Towable 4527A',
                'children' => 3,
            ],
            [
                'reference' => '1603',
                'name' => 'Concrete Saw Gas 14"',
                'children' => 5,
            ],
            [
                'reference' => '1403',
                'name' => '200lb Plate Compactor',
                'children' => 6,
            ],
        ];

        foreach ($headers as $header) {
            $item = Item::create([
                'reference' => $header['reference'],
                'name' => $header['name'],
            ]);
        
            for ($i=1; $i < $header['children']; $i++) { 
                $item->children()->create([
                    'reference' => $header['reference'] . '-0' . $i,
                    'name' => $header['name'],
                ]);
            }
        }
    }
}
