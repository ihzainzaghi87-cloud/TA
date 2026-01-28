<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\UserPoint;
use App\Models\Cart;
use App\Models\Order;
use App\Models\UserAddress;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user can be created
     */
    public function test_user_can_be_created(): void
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'username' => 'testuser',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'username' => 'testuser',
        ]);

        $this->assertEquals('Test User', $user->name);
    }

    /**
     * Test user has fillable attributes
     */
    public function test_user_has_fillable_attributes(): void
    {
        $user = new User();
        
        $expected = [
            'name',
            'email',
            'password',
            'username',
            'phone_number',
            'date_of_birth',
            'gender',
        ];

        $this->assertEquals($expected, $user->getFillable());
    }

    /**
     * Test password is hidden
     */
    public function test_password_is_hidden(): void
    {
        $user = User::factory()->create();
        
        $array = $user->toArray();
        
        $this->assertArrayNotHasKey('password', $array);
        $this->assertArrayNotHasKey('remember_token', $array);
    }

    /**
     * Test user has one user point relationship
     */
    public function test_user_has_one_user_point(): void
    {
        $user = User::factory()->create();
        
        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\HasOne::class,
            $user->userPoint()
        );
    }

    /**
     * Test user has many carts relationship
     */
    public function test_user_has_many_carts(): void
    {
        $user = User::factory()->create();
        
        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\HasMany::class,
            $user->carts()
        );
    }

    /**
     * Test user has many orders relationship
     */
    public function test_user_has_many_orders(): void
    {
        $user = User::factory()->create();
        
        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\HasMany::class,
            $user->orders()
        );
    }

    /**
     * Test user has many addresses relationship
     */
    public function test_user_has_many_addresses(): void
    {
        $user = User::factory()->create();
        
        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\HasMany::class,
            $user->addresses()
        );
    }

    /**
     * Test user has active addresses relationship
     */
    public function test_user_has_active_addresses(): void
    {
        $user = User::factory()->create();
        
        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\HasMany::class,
            $user->activeAddresses()
        );
    }

    /**
     * Test email verified at is cast to datetime
     */
    public function test_email_verified_at_is_cast_to_datetime(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $this->assertInstanceOf(\DateTime::class, $user->email_verified_at);
    }
}
