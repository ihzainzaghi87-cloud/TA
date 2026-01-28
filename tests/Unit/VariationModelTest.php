<?php

namespace Tests\Unit;

use App\Models\Variation;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VariationModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test variation can be created
     */
    public function test_variation_can_be_created(): void
    {
        $product = Product::factory()->create();
        
        $variation = Variation::factory()->create([
            'product_id' => $product->id,
            'color' => 'Red',
            'size' => 'L',
            'stock' => 10,
        ]);

        $this->assertDatabaseHas('variations', [
            'product_id' => $product->id,
            'color' => 'Red',
            'size' => 'L',
            'stock' => 10,
        ]);
    }

    /**
     * Test variation belongs to product
     */
    public function test_variation_belongs_to_product(): void
    {
        $variation = new Variation();
        
        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\BelongsTo::class,
            $variation->product()
        );
    }

    /**
     * Test variation has color and size attributes
     */
    public function test_variation_has_color_and_size_attributes(): void
    {
        $variation = Variation::factory()->create([
            'color' => 'Blue',
            'size' => 'XL',
        ]);

        $this->assertEquals('Blue', $variation->color);
        $this->assertEquals('XL', $variation->size);
    }

    /**
     * Test stock is cast to integer
     */
    public function test_stock_is_cast_to_integer(): void
    {
        $variation = Variation::factory()->create([
            'stock' => 25,
        ]);

        $this->assertIsInt($variation->stock);
        $this->assertEquals(25, $variation->stock);
    }
}
