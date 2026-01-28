<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\Variation;
use App\Models\OrderItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test product can be created
     */
    public function test_product_can_be_created(): void
    {
        $category = Category::factory()->create();
        
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'name' => 'Test Product',
            'price' => 100000,
        ]);

        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'price' => 100000,
        ]);
    }

    /**
     * Test product has fillable attributes
     */
    public function test_product_has_fillable_attributes(): void
    {
        $product = new Product();
        
        $expected = [
            'category_id',
            'slug',
            'name',
            'description',
            'weight',
            'price',
            'point_price',
            'is_active',
            'is_reward',
        ];

        $this->assertEquals($expected, $product->getFillable());
    }

    /**
     * Test product belongs to category
     */
    public function test_product_belongs_to_category(): void
    {
        $product = new Product();
        
        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\BelongsTo::class,
            $product->category()
        );
    }

    /**
     * Test product has many images
     */
    public function test_product_has_many_images(): void
    {
        $product = new Product();
        
        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\HasMany::class,
            $product->images()
        );
    }

    /**
     * Test product has many variations
     */
    public function test_product_has_many_variations(): void
    {
        $product = new Product();
        
        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\HasMany::class,
            $product->variations()
        );
    }

    /**
     * Test product has many order items
     */
    public function test_product_has_many_order_items(): void
    {
        $product = new Product();
        
        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\HasMany::class,
            $product->orderItems()
        );
    }

    /**
     * Test price is cast to decimal
     */
    public function test_price_is_cast_to_decimal(): void
    {
        $product = Product::factory()->create([
            'price' => 100000.50,
        ]);

        $this->assertIsString($product->price);
        $this->assertEquals('100000.50', $product->price);
    }

    /**
     * Test is_active is cast to boolean
     */
    public function test_is_active_is_cast_to_boolean(): void
    {
        $product = Product::factory()->create([
            'is_active' => 1,
        ]);

        $this->assertIsBool($product->is_active);
        $this->assertTrue($product->is_active);
    }

    /**
     * Test is_reward is cast to boolean
     */
    public function test_is_reward_is_cast_to_boolean(): void
    {
        $product = Product::factory()->create([
            'is_reward' => 1,
        ]);

        $this->assertIsBool($product->is_reward);
        $this->assertTrue($product->is_reward);
    }

    /**
     * Test product uses correct table
     */
    public function test_product_uses_correct_table(): void
    {
        $product = new Product();
        
        $this->assertEquals('products', $product->getTable());
    }
}
