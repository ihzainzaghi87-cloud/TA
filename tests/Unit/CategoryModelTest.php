<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test category can be created
     */
    public function test_category_can_be_created(): void
    {
        $category = Category::factory()->create([
            'name' => 'Electronics',
            'slug' => 'electronics',
        ]);

        $this->assertDatabaseHas('categories', [
            'name' => 'Electronics',
            'slug' => 'electronics',
        ]);
    }

    /**
     * Test category has many products
     */
    public function test_category_has_many_products(): void
    {
        $category = new Category();
        
        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\HasMany::class,
            $category->products()
        );
    }

    /**
     * Test category can have multiple products
     */
    public function test_category_can_have_multiple_products(): void
    {
        $category = Category::factory()->create();
        
        $products = Product::factory()->count(3)->create([
            'category_id' => $category->id,
        ]);

        $this->assertCount(3, $category->products);
    }
}
