<?php

namespace Database\Seeders;

use App\Models\Items\Category;
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
        $categories = Category::all();

        $headers = [
            [
                'reference' => '1001',
                'name' => '2HP Electric Air Compressor',
                'children' => 4,
                'category' => $categories->where('name', 'Air Compressors')->first()->id,
            ],
            [
                'reference' => '1505',
                'name' => 'Breaker Electric 65lb',
                'children' => 5,
                'category' => $categories->where('name', 'Concrete Breakers')->first()->id,
            ],
            [
                'reference' => '3813',
                'name' => 'Artic. Lift, Towable 4527A',
                'children' => 3,
                'category' => $categories->where('name', 'Aerial')->first()->id,
            ],
            [
                'reference' => '1603',
                'name' => 'Concrete Saw Gas 14"',
                'children' => 5,
                'category' => $categories->where('name', 'Concrete Saws')->first()->id,
            ],
            [
                'reference' => '1403',
                'name' => '200lb Plate Compactor',
                'children' => 6,
                'category' => $categories->where('name', 'Compaction')->first()->id,
            ],
        ];

        foreach ($headers as $header) {
            $item = Item::create([
                'reference' => $header['reference'],
                'name' => $header['name'],
            ]);

            $item->categories()->attach($header['category']);
            $item->templates()->create([
                'name' => $header['name'] . ' header inspection',
            ]);
        
            for ($i=1; $i < $header['children']; $i++) { 
                $child = $item->children()->create([
                    'reference' => $header['reference'] . '-0' . $i,
                    'name' => $header['name'],
                ]);

                $child->categories()->attach($header['category']);
                $child->templates()->create([
                    'name' => $header['name'] . ' child inspection 1',
                ]);
                $child->templates()->create([
                    'name' => $header['name'] . ' child inspection 2',
                ]);
            }
        }
    }
}
