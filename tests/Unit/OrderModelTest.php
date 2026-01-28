<?php

namespace Tests\Unit;

use App\Models\Order;
use App\Models\User;
use App\Models\OrderItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test order can be created
     */
    public function test_order_can_be_created(): void
    {
        $user = User::factory()->create();
        
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'order_number' => 'ORD-001',
            'total' => 150000,
        ]);

        $this->assertDatabaseHas('orders', [
            'order_number' => 'ORD-001',
            'total' => 150000,
        ]);
    }

    /**
     * Test order has fillable attributes
     */
    public function test_order_has_fillable_attributes(): void
    {
        $order = new Order();
        
        $expected = [
            'user_id',
            'user_address_id',
            'order_number',
            'subtotal',
            'shipping_cost',
            'total',
            'total_points_used',
            'points_earned',
            'status',
            'snap_token',
            'payment_status',
            'notes',
            'shipping_recipient_name',
            'shipping_phone',
            'courier',
            'service',
            'weight',
            'origin_city_id',
            'destination_city_id',
            'tracking_number',
            'shipped_at',
            'delivered_at',
        ];

        $this->assertEquals($expected, $order->getFillable());
    }

    /**
     * Test order belongs to user
     */
    public function test_order_belongs_to_user(): void
    {
        $order = new Order();
        
        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\BelongsTo::class,
            $order->user()
        );
    }

    /**
     * Test order has many order items
     */
    public function test_order_has_many_order_items(): void
    {
        $order = new Order();
        
        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\HasMany::class,
            $order->orderItems()
        );
    }

    /**
     * Test subtotal is cast to decimal
     */
    public function test_subtotal_is_cast_to_decimal(): void
    {
        $order = Order::factory()->create([
            'subtotal' => 100000.50,
        ]);

        $this->assertIsString($order->subtotal);
        $this->assertEquals('100000.50', $order->subtotal);
    }

    /**
     * Test shipping_cost is cast to decimal
     */
    public function test_shipping_cost_is_cast_to_decimal(): void
    {
        $order = Order::factory()->create([
            'shipping_cost' => 25000.00,
        ]);

        $this->assertIsString($order->shipping_cost);
        $this->assertEquals('25000.00', $order->shipping_cost);
    }

    /**
     * Test total is cast to decimal
     */
    public function test_total_is_cast_to_decimal(): void
    {
        $order = Order::factory()->create([
            'total' => 125000.50,
        ]);

        $this->assertIsString($order->total);
        $this->assertEquals('125000.50', $order->total);
    }

    /**
     * Test points fields are cast to integer
     */
    public function test_points_fields_are_cast_to_integer(): void
    {
        $order = Order::factory()->create([
            'total_points_used' => 100,
            'points_earned' => 50,
        ]);

        $this->assertIsInt($order->total_points_used);
        $this->assertIsInt($order->points_earned);
        $this->assertEquals(100, $order->total_points_used);
        $this->assertEquals(50, $order->points_earned);
    }

    /**
     * Test datetime fields are cast correctly
     */
    public function test_datetime_fields_are_cast_correctly(): void
    {
        $order = Order::factory()->create([
            'shipped_at' => now(),
            'delivered_at' => now()->addDays(3),
        ]);

        $this->assertInstanceOf(\DateTime::class, $order->shipped_at);
        $this->assertInstanceOf(\DateTime::class, $order->delivered_at);
    }

    /**
     * Test order uses correct table
     */
    public function test_order_uses_correct_table(): void
    {
        $order = new Order();
        
        $this->assertEquals('orders', $order->getTable());
    }
}
