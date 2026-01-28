<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Variation;
use App\Models\Cart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function createProductWithVariation()
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);
        $variation = Variation::factory()->create(['product_id' => $product->id]);
        
        return $variation;
    }

    /**
     * Test authenticated user can view their cart.
     */
    public function test_authenticated_user_can_view_cart(): void
    {
        $user = User::factory()->create();
        $variation = $this->createProductWithVariation();
        
        Cart::factory()->create([
            'user_id' => $user->id,
            'variation_id' => $variation->id,
            'quantity' => 2,
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/cart');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'code', 'status', 'message',
                'data' => [
                    'cartItems' => [
                        '*' => [
                            'id',
                            'variation_id',
                            'quantity',
                        ]
                    ]
                ]
            ]);
    }

    /**
     * Test authenticated user can add item to cart.
     */
    public function test_authenticated_user_can_add_item_to_cart(): void
    {
        $user = User::factory()->create();
        $variation = $this->createProductWithVariation();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/cart', [
                'variation_id' => $variation->id,
                'quantity' => 2,
            ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('carts', [
            'user_id' => $user->id,
            'variation_id' => $variation->id,
            'quantity' => 2,
        ]);
    }

    /**
     * Test authenticated user can update cart item quantity.
     */
    public function test_authenticated_user_can_update_cart_item(): void
    {
        $user = User::factory()->create();
        $variation = $this->createProductWithVariation();
        
        $cart = Cart::factory()->create([
            'user_id' => $user->id,
            'variation_id' => $variation->id,
            'quantity' => 2,
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->putJson("/api/cart/{$cart->id}", [
                'quantity' => 5,
            ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('carts', [
            'id' => $cart->id,
            'quantity' => 5,
        ]);
    }

    /**
     * Test authenticated user can delete cart item.
     */
    public function test_authenticated_user_can_delete_cart_item(): void
    {
        $user = User::factory()->create();
        $variation = $this->createProductWithVariation();
        
        $cart = Cart::factory()->create([
            'user_id' => $user->id,
            'variation_id' => $variation->id,
            'quantity' => 2,
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson("/api/cart/{$cart->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('carts', [
            'id' => $cart->id,
        ]);
    }

    /**
     * Test authenticated user can clear entire cart.
     */
    public function test_authenticated_user_can_clear_cart(): void
    {
        $user = User::factory()->create();
        $variation = $this->createProductWithVariation();
        
        // Use different variations to avoid unique constraint
        $variation2 = Variation::factory()->create(['product_id' => $variation->product_id]);
        $variation3 = Variation::factory()->create(['product_id' => $variation->product_id]);
        
        Cart::factory()->create(['user_id' => $user->id, 'variation_id' => $variation->id]);
        Cart::factory()->create(['user_id' => $user->id, 'variation_id' => $variation2->id]);
        Cart::factory()->create(['user_id' => $user->id, 'variation_id' => $variation3->id]);

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson('/api/cart');

        $response->assertStatus(200);

        $this->assertDatabaseMissing('carts', [
            'user_id' => $user->id,
        ]);
    }

    /**
     * Test authenticated user can get cart summary.
     */
    public function test_authenticated_user_can_get_cart_summary(): void
    {
        $user = User::factory()->create();
        $variation = $this->createProductWithVariation();
        
        Cart::factory()->create([
            'user_id' => $user->id,
            'variation_id' => $variation->id,
            'quantity' => 2,
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/cart/summary');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'code', 'status', 'message',
                'data' => [
                    'itemCount',
                    'totalPrice',
                    'totalPointPrice',
                ]
            ]);
    }

    /**
     * Test unauthenticated user cannot access cart.
     */
    public function test_unauthenticated_user_cannot_access_cart(): void
    {
        $response = $this->getJson('/api/cart');

        $response->assertStatus(401);
    }

    /**
     * Test user cannot update another user's cart item.
     */
    public function test_user_cannot_update_another_users_cart_item(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $variation = $this->createProductWithVariation();
        
        $cart = Cart::factory()->create([
            'user_id' => $user2->id,
            'variation_id' => $variation->id,
            'quantity' => 2,
        ]);

        $response = $this->actingAs($user1, 'sanctum')
            ->putJson("/api/cart/{$cart->id}", [
                'quantity' => 5,
            ]);

        // Controller returns 400 when cart item not found for user
        $response->assertStatus(400);
    }

    /**
     * Test user cannot delete another user's cart item.
     */
    public function test_user_cannot_delete_another_users_cart_item(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $variation = $this->createProductWithVariation();
        
        $cart = Cart::factory()->create([
            'user_id' => $user2->id,
            'variation_id' => $variation->id,
            'quantity' => 2,
        ]);

        $response = $this->actingAs($user1, 'sanctum')
            ->deleteJson("/api/cart/{$cart->id}");

        // Controller returns 400 when cart item not found for user
        $response->assertStatus(400);
    }
}
