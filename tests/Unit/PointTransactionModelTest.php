<?php

namespace Tests\Unit;

use App\Models\PointTransaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PointTransactionModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test point transaction can be created
     */
    public function test_point_transaction_can_be_created(): void
    {
        $user = User::factory()->create();
        
        $transaction = PointTransaction::create([
            'user_id' => $user->id,
            'points' => 100,
            'type' => 'earned',
            'balance_after' => 100,
            'description' => 'Test transaction',
            'transactionable_type' => 'App\\Models\\Order',
            'transactionable_id' => 1,
        ]);

        $this->assertDatabaseHas('point_transactions', [
            'user_id' => $user->id,
            'points' => 100,
            'type' => 'earned',
        ]);
    }

    /**
     * Test point transaction belongs to user
     */
    public function test_point_transaction_belongs_to_user(): void
    {
        $transaction = new PointTransaction();
        
        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\BelongsTo::class,
            $transaction->user()
        );
    }

    /**
     * Test points field is cast to integer
     */
    public function test_points_field_is_cast_to_integer(): void
    {
        $user = User::factory()->create();
        
        $transaction = PointTransaction::create([
            'user_id' => $user->id,
            'points' => 150,
            'type' => 'earned',
            'balance_after' => 150,
            'description' => 'Test transaction',
            'transactionable_type' => 'App\\Models\\Order',
            'transactionable_id' => 1,
        ]);

        $this->assertIsInt($transaction->points);
        $this->assertEquals(150, $transaction->points);
    }
}
