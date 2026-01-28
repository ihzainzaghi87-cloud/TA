<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function createAdminUser()
    {
        $admin = User::factory()->create();
        $adminRole = Role::create(['name' => 'admin']);
        
        // Create permissions
        Permission::create(['name' => 'users.index']);
        Permission::create(['name' => 'users.view']);
        Permission::create(['name' => 'users.create']);
        Permission::create(['name' => 'users.update']);
        Permission::create(['name' => 'users.assign-roles']);
        Permission::create(['name' => 'users.grant-permissions']);
        
        $adminRole->givePermissionTo([
            'users.index',
            'users.view',
            'users.create',
            'users.update',
            'users.assign-roles',
            'users.grant-permissions',
        ]);
        
        $admin->assignRole($adminRole);
        
        return $admin;
    }

    /**
     * Test admin can list all users.
     */
    public function test_admin_can_list_all_users(): void
    {
        $admin = $this->createAdminUser();
        User::factory()->count(5)->create();

        $response = $this->actingAs($admin, 'sanctum')
            ->getJson('/api/admin/users');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'code', 'status', 'message',
                'data' => [
                    'items' => [
                        '*' => [
                            'id',
                            'name',
                            'email',
                        ]
                    ]
                ]
            ]);
    }

    /**
     * Test admin can view specific user.
     */
    public function test_admin_can_view_specific_user(): void
    {
        $admin = $this->createAdminUser();
        $user = User::factory()->create();

        $response = $this->actingAs($admin, 'sanctum')
            ->getJson("/api/admin/users/{$user->id}");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'email' => $user->email,
                    ]
                ]
            ]);
    }

    /**
     * Test admin can create new user.
     */
    public function test_admin_can_create_new_user(): void
    {
        $admin = $this->createAdminUser();

        $userData = [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->actingAs($admin, 'sanctum')
            ->postJson('/api/admin/users', $userData);

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'email' => 'newuser@example.com',
            'name' => 'New User',
        ]);
    }

    /**
     * Test admin can update user profile.
     */
    public function test_admin_can_update_user_profile(): void
    {
        $admin = $this->createAdminUser();
        $user = User::factory()->create([
            'name' => 'Old Name'
        ]);

        $response = $this->actingAs($admin, 'sanctum')
            ->putJson("/api/admin/users/{$user->id}", [
                'name' => 'Updated Name',
                'email' => $user->email,
            ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
        ]);
    }

    /**
     * Test admin can update user password.
     */
    public function test_admin_can_update_user_password(): void
    {
        $admin = $this->createAdminUser();
        $user = User::factory()->create();

        $response = $this->actingAs($admin, 'sanctum')
            ->putJson("/api/admin/users/{$user->id}/password", [
                'password' => 'newpassword123',
                'password_confirmation' => 'newpassword123',
            ]);

        $response->assertStatus(200);
    }

    /**
     * Test admin can assign roles to user.
     */
    public function test_admin_can_assign_roles_to_user(): void
    {
        $admin = $this->createAdminUser();
        $user = User::factory()->create();
        $role = Role::create(['name' => 'editor']);

        $response = $this->actingAs($admin, 'sanctum')
            ->postJson("/api/admin/users/{$user->id}/sync-roles", [
                'roles' => ['editor'],
            ]);

        $response->assertStatus(200);

        $this->assertTrue($user->hasRole('editor'));
    }

    /**
     * Test admin can grant permissions to user.
     */
    public function test_admin_can_grant_permissions_to_user(): void
    {
        $admin = $this->createAdminUser();
        $user = User::factory()->create();
        $permission = Permission::create(['name' => 'posts.create']);

        $response = $this->actingAs($admin, 'sanctum')
            ->postJson("/api/admin/users/{$user->id}/sync-permissions", [
                'permissions' => ['posts.create'],
            ]);

        $response->assertStatus(200);

        $this->assertTrue($user->hasPermissionTo('posts.create'));
    }

    /**
     * Test non-admin user cannot access user management.
     */
    public function test_non_admin_user_cannot_access_user_management(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/admin/users');

        $response->assertStatus(403);
    }

    /**
     * Test unauthenticated user cannot access user management.
     */
    public function test_unauthenticated_user_cannot_access_user_management(): void
    {
        $response = $this->getJson('/api/admin/users');

        $response->assertStatus(401);
    }

    /**
     * Test admin cannot create user with duplicate email.
     */
    public function test_admin_cannot_create_user_with_duplicate_email(): void
    {
        $admin = $this->createAdminUser();
        $existingUser = User::factory()->create(['email' => 'existing@example.com']);

        $response = $this->actingAs($admin, 'sanctum')
            ->postJson('/api/admin/users', [
                'name' => 'New User',
                'email' => 'existing@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }
}
