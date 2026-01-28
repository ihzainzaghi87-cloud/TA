<?php

namespace Tests\Unit;

use App\Models\Cart;
use App\Models\User;
use App\Models\Variation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test cart can be created
     */
    public function test_cart_can_be_created(): void
    {
        $user = User::factory()->create();
        
        $cart = Cart::factory()->create([
            'user_id' => $user->id,
            'quantity' => 2,
        ]);

        $this->assertDatabaseHas('carts', [
            'user_id' => $user->id,
            'quantity' => 2,
        ]);
    }

    /**
     * Test cart has fillable attributes
     */
    public function test_cart_has_fillable_attributes(): void
    {
        $cart = new Cart();
        
        $expected = [
            'user_id',
            'variation_id',
            'quantity',
        ];

        $this->assertEquals($expected, $cart->getFillable());
    }

    /**
     * Test cart belongs to user
     */
    public function test_cart_belongs_to_user(): void
    {
        $cart = new Cart();
        
        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\BelongsTo::class,
            $cart->user()
        );
    }

    /**
     * Test cart belongs to variation
     */
    public function test_cart_belongs_to_variation(): void
    {
        $cart = new Cart();
        
        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\BelongsTo::class,
            $cart->variation()
        );
    }

    /**
     * Test quantity is cast to integer
     */
    public function test_quantity_is_cast_to_integer(): void
    {
        $cart = Cart::factory()->create([
            'quantity' => 5,
        ]);

        $this->assertIsInt($cart->quantity);
        $this->assertEquals(5, $cart->quantity);
    }

    /**
     * Test cart uses correct table
     */
    public function test_cart_uses_correct_table(): void
    {
        $cart = new Cart();
        
        $this->assertEquals('carts', $cart->getTable());
    }
}
