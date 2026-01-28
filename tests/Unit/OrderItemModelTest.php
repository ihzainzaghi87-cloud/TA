<?php

namespace Tests\Unit;

use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Variation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderItemModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test order item can be created
     */
    public function test_order_item_can_be_created(): void
    {
        $order = Order::factory()->create();
        $variation = Variation::factory()->create();
        
        $orderItem = OrderItem::create([
            'order_id' => $order->id,
            'variation_id' => $variation->id,
            'product_name' => 'Test Product',
            'variant_details' => 'Size: L, Color: Red',
            'quantity' => 2,
            'price' => 50000,
        ]);

        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'variation_id' => $variation->id,
            'quantity' => 2,
        ]);
    }

    /**
     * Test order item belongs to order
     */
    public function test_order_item_belongs_to_order(): void
    {
        $orderItem = new OrderItem();
        
        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\BelongsTo::class,
            $orderItem->order()
        );
    }

    /**
     * Test order item belongs to variation
     */
    public function test_order_item_belongs_to_variation(): void
    {
        $orderItem = new OrderItem();
        
        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\BelongsTo::class,
            $orderItem->variation()
        );
    }

    /**
     * Test quantity is cast to integer
     */
    public function test_quantity_is_cast_to_integer(): void
    {
        $order = Order::factory()->create();
        $variation = Variation::factory()->create();
        
        $orderItem = OrderItem::create([
            'order_id' => $order->id,
            'variation_id' => $variation->id,
            'product_name' => 'Test Product',
            'variant_details' => 'Size: L, Color: Red',
            'quantity' => 3,
            'price' => 50000,
        ]);

        $this->assertIsInt($orderItem->quantity);
        $this->assertEquals(3, $orderItem->quantity);
    }

    /**
     * Test price is cast to decimal
     */
    public function test_price_is_cast_to_decimal(): void
    {
        $order = Order::factory()->create();
        $variation = Variation::factory()->create();
        
        $orderItem = OrderItem::create([
            'order_id' => $order->id,
            'variation_id' => $variation->id,
            'product_name' => 'Test Product',
            'variant_details' => 'Size: L, Color: Red',
            'quantity' => 2,
            'price' => 75000.50,
        ]);

        $this->assertIsString($orderItem->price);
    }
}
