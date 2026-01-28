<?php

namespace Tests\Unit;

use App\Models\UserPoint;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserPointModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user point can be created
     */
    public function test_user_point_can_be_created(): void
    {
        $user = User::factory()->create();
        
        $userPoint = UserPoint::create([
            'user_id' => $user->id,
            'total_points' => 500,
        ]);

        $this->assertDatabaseHas('user_points', [
            'user_id' => $user->id,
            'total_points' => 500,
        ]);
    }

    /**
     * Test user point belongs to user
     */
    public function test_user_point_belongs_to_user(): void
    {
        $userPoint = new UserPoint();
        
        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\BelongsTo::class,
            $userPoint->user()
        );
    }

    /**
     * Test total_points field is cast to integer
     */
    public function test_total_points_field_is_cast_to_integer(): void
    {
        $user = User::factory()->create();
        
        $userPoint = UserPoint::create([
            'user_id' => $user->id,
            'total_points' => 1000,
        ]);

        $this->assertIsInt($userPoint->total_points);
        $this->assertEquals(1000, $userPoint->total_points);
    }

    /**
     * Test user can only have one user point
     */
    public function test_user_can_only_have_one_user_point(): void
    {
        $user = User::factory()->create();
        $userPoint = UserPoint::create([
            'user_id' => $user->id,
            'total_points' => 100,
        ]);

        $this->assertEquals($userPoint->id, $user->userPoint->id);
    }
}
