<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionAndRoleSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // 1) Buat semua permissions
        $map = [
            'roles'       => ['index', 'view', 'create', 'update', 'delete', 'sync-permissions'],
            'permissions' => ['index', 'view', 'create', 'update', 'delete'],
            'users'       => ['index', 'view', 'create', 'update', 'delete', 'assign-roles', 'grant-permissions'],
            'banners' => ['index', 'view', 'create', 'update', 'delete'],
            'articles' => ['index', 'view', 'create', 'update', 'delete', 'publish'],
            'categories' => ['index', 'view', 'create', 'update', 'delete'],
            'products' => ['index', 'view', 'create', 'update', 'delete', 'destroy-image'],
            'orders' => ['index', 'view', 'edit', 'update'],
            'user-points' => ['index', 'view'],
            'point-transactions' => ['index', 'view'],
            'reports' => ['index', 'view', 'orders', 'products', 'users', 'points', 'sales'],
        ];

        $all = [];
        foreach ($map as $module => $actions) {
            foreach ($actions as $action) {
                $name = "{$module}.{$action}";
                Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
                $all[] = $name;
            }
        }

        // 2) (opsional) Berikan permissions ke role tertentu, bila ada
        $superadmin = Role::where('name', 'superadmin')->where('guard_name', 'web')->first();
        $owner      = Role::where('name', 'owner')->where('guard_name', 'web')->first();
        $staff      = Role::where('name', 'staff')->where('guard_name', 'web')->first();
        $user       = Role::where('name', 'user')->where('guard_name', 'web')->first();

        // superadmin: semua permission
        if ($superadmin) {
            $superadmin->syncPermissions($all);
        }

        // owner: mayoritas manajemen + posts full
        if ($owner) {
            $owner->syncPermissions([
                'roles.view','roles.create','roles.update','roles.delete','roles.sync-permissions',
                'permissions.view','permissions.create','permissions.update','permissions.delete',
                'users.view','users.create','users.update','users.delete','users.assign-roles','users.grant-permissions',
            ]);
        }

        // staff: kelola konten + view users
        if ($staff) {
            $staff->syncPermissions([
                'articles.index', 'articles.view', 'articles.create', 'articles.update', 'articles.delete', 'articles.publish',
                'banners.index', 'banners.view', 'banners.create', 'banners.update', 'banners.delete',
                'categories.index', 'categories.view', 'categories.create', 'categories.update', 'categories.delete',
                'products.index', 'products.view', 'products.create', 'products.update', 'products.delete', 'products.destroy-image',
            ]);
        }

        // user: hanya view posts
        // if ($user) {
        //     $user->syncPermissions([
        //         'roles.view',
        //     ]);
        // }
    }
}
