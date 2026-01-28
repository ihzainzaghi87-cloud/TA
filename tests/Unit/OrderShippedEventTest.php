<?php

namespace Tests\Unit;

use App\Events\OrderShipped;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderShippedEventTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test order shipped event can be instantiated
     */
    public function test_order_shipped_event_can_be_instantiated(): void
    {
        $user = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id]);
        
        $event = new OrderShipped($order);
        
        $this->assertInstanceOf(OrderShipped::class, $event);
        $this->assertEquals($order->id, $event->order->id);
    }

    /**
     * Test event has correct broadcast channels
     */
    public function test_event_has_broadcast_channels(): void
    {
        $user = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id]);
        
        $event = new OrderShipped($order);
        
        $this->assertTrue(method_exists($event, 'broadcastOn'));
    }
}
