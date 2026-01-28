<?php

namespace Tests\Unit;

use App\Models\ProductImage;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductImageModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test product image can be created
     */
    public function test_product_image_can_be_created(): void
    {
        $product = Product::factory()->create();
        
        $productImage = ProductImage::create([
            'product_id' => $product->id,
            'image' => 'products/test.jpg',
        ]);

        $this->assertDatabaseHas('product_images', [
            'product_id' => $product->id,
            'image' => 'products/test.jpg',
        ]);
    }

    /**
     * Test product image belongs to product
     */
    public function test_product_image_belongs_to_product(): void
    {
        $productImage = new ProductImage();
        
        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\BelongsTo::class,
            $productImage->product()
        );
    }

    /**
     * Test product image has fillable attributes
     */
    public function test_product_image_has_fillable_attributes(): void
    {
        $productImage = new ProductImage();
        
        $expected = [
            'product_id',
            'image',
        ];

        $this->assertEquals($expected, $productImage->getFillable());
    }
}
