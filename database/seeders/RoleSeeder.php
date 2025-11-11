<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // bersihkan cache permission/role agar perubahan terdeteksi
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // daftar role awal
        $roles = [
            'superadmin',
            'admin',
            'staff',
            'user',
        ];

        foreach ($roles as $name) {
            Role::firstOrCreate([
                'name'       => $name,
                'guard_name' => 'web', // pastikan konsisten dengan default_guard
            ]);
        }
    }
}
