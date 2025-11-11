<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan cache permission/role bersih
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // Pastikan roles minimal tersedia (jika RoleSeeder belum/terlewat)
        foreach (['superadmin','admin','staff','user'] as $r) {
            Role::firstOrCreate(['name' => $r, 'guard_name' => 'web']);
        }

        // Default password (boleh ganti via .env: SEEDER_DEFAULT_PASSWORD=rahasia123)
        $defaultPassword = env('SEEDER_DEFAULT_PASSWORD', 'password');

        $seed = [
            [
                'name'         => 'Root Superadmin',
                'email'        => 'root@example.com',
                'username'     => 'root',
                'phone_number' => '081111111111',
                'roles'        => ['superadmin'],
            ],
            [
                'name'         => 'Admin Demo',
                'email'        => 'admin@example.com',
                'username'     => 'admin',
                'phone_number' => '081222222222',
                'roles'        => ['admin'],
            ],
            [
                'name'         => 'Staff Demo',
                'email'        => 'staff@example.com',
                'username'     => 'staff',
                'phone_number' => '081333333333',
                'roles'        => ['staff'],
            ],
            [
                'name'         => 'User Demo',
                'email'        => 'user@example.com',
                'username'     => 'userdemo',
                'phone_number' => '081444444444',
                'roles'        => ['user'],
            ],
        ];

        foreach ($seed as $row) {
            $user = User::firstOrCreate(
                ['email' => $row['email']],
                [
                    'name'         => $row['name'],
                    'username'     => $row['username'] ?? null,
                    'phone_number' => $row['phone_number'] ?? null,
                    'password'     => Hash::make($defaultPassword),
                ]
            );

            // Assign / sync roles
            $user->syncRoles($row['roles']);

            // (Opsional) jika masih pakai kolom users.role sebagai label:
            // $user->update(['role' => $row['roles'][0]]);
        }
    }
}
