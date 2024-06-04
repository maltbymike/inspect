<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use App\Models\Items\Category;
use Illuminate\Database\Seeder;
use SebastianBergmann\Type\VoidType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DevItemCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function createCategoriesFromArray(Category $parent, array $categories): Void
    {
        foreach ($categories as $category) {
            $subcategory = $parent->children()->create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
            ]);

            if (isset($category['children'])) {
                $this->createCategoriesFromArray(parent: $subcategory, categories: $category['children']);
            }
        }
    }

    public function run(): void
    {
        $rental = Category::create([
            'name' => 'Rental',
            'slug' => 'rental',
            'is_root' => true,
        ]);

        $sale = Category::create([
            'name' => 'Sales',
            'slug' => 'sales',
            'is_root' => true,
        ]);

        $categories = [
            ['name' => 'Air Compressors'],
            [
                'name' => 'Concrete & Masonry',
                'children' => [
                    ['name' => 'Concrete Breakers'],
                    ['name' => 'Concrete Saws'],
                ]
            ],
            ['name' => 'Aerial'],
            ['name' => 'Compaction'],
        ];

        $this->createCategoriesFromArray(parent: $rental, categories: $categories);
    }
}
