<?php

namespace Tests\Unit;

use App\Models\UserAddress;
use App\Models\User;
use App\Models\Province;
use App\Models\City;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserAddressModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user address can be created
     */
    public function test_user_address_can_be_created(): void
    {
        $user = User::factory()->create();
        
        $address = UserAddress::factory()->create([
            'user_id' => $user->id,
            'label' => 'Home',
            'recipient_name' => 'John Doe',
        ]);

        $this->assertDatabaseHas('user_addresses', [
            'user_id' => $user->id,
            'label' => 'Home',
            'recipient_name' => 'John Doe',
        ]);
    }

    /**
     * Test user address belongs to user
     */
    public function test_user_address_belongs_to_user(): void
    {
        $address = new UserAddress();
        
        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\BelongsTo::class,
            $address->user()
        );
    }

    /**
     * Test is_active is cast to boolean
     */
    public function test_is_active_is_cast_to_boolean(): void
    {
        $address = UserAddress::factory()->create([
            'is_active' => 1,
        ]);

        $this->assertIsBool($address->is_active);
        $this->assertTrue($address->is_active);
    }

    /**
     * Test is_primary is cast to boolean
     */
    public function test_is_primary_is_cast_to_boolean(): void
    {
        $address = UserAddress::factory()->create([
            'is_primary' => 1,
        ]);

        $this->assertIsBool($address->is_primary);
        $this->assertTrue($address->is_primary);
    }
}
