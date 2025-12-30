<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'T-Shirt', 'slug' => 't-shirt'],
            ['name' => 'Hoodie', 'slug' => 'hoodie'],
            ['name' => 'Jacket', 'slug' => 'jacket'],
            ['name' => 'Pants', 'slug' => 'pants'],
            ['name' => 'Shorts', 'slug' => 'shorts'],
            ['name' => 'Cap', 'slug' => 'cap'],
            ['name' => 'Accessories', 'slug' => 'accessories'],
            ['name' => 'Bag', 'slug' => 'bag'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                ['name' => $category['name']]
            );
        }
    }
}
