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
            'categories' => ['index', 'view', 'create', 'update', 'delete'],
            'products' => ['index', 'view', 'create', 'update', 'delete', 'destroy-image'],
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
        $admin      = Role::where('name', 'admin')->where('guard_name', 'web')->first();
        $staff      = Role::where('name', 'staff')->where('guard_name', 'web')->first();
        $user       = Role::where('name', 'user')->where('guard_name', 'web')->first();

        // superadmin: semua permission
        if ($superadmin) {
            $superadmin->syncPermissions($all);
        }

        // admin: mayoritas manajemen + posts full
        if ($admin) {
            $admin->syncPermissions([
                'roles.view','roles.create','roles.update','roles.delete','roles.sync-permissions',
                'permissions.view','permissions.create','permissions.update','permissions.delete',
                'users.view','users.create','users.update','users.delete','users.assign-roles','users.grant-permissions',
            ]);
        }

        // staff: kelola konten + view users
        if ($staff) {
            $staff->syncPermissions([
                'roles.view',
            ]);
        }

        // user: hanya view posts
        if ($user) {
            $user->syncPermissions([
                'roles.view',
            ]);
        }
    }
}
