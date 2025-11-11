<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

// Ganti jika helper-mu berbeda (mis. ResponsesFormatter)
use App\Helpers\ResponseFormatter;

class DashboardController extends Controller
{
    /**
     * GET /api/dashboard
     * Guard: auth:sanctum
     */
    public function index(Request $request)
    {
        $user = $request->user();

        try {
            // Stats
            $totalUsers        = User::count();
            $totalRoles        = Role::count();
            $totalPermissions  = Permission::count();

            // Hitung sesi aktif hanya jika table sessions ada (driver database)
            $activeSessions = null;
            if (Schema::hasTable('sessions')) {
                // Sesuaikan kriteria aktif sesuai kebutuhanmu
                $activeSessions = DB::table('sessions')->count();
                // atau: DB::table('sessions')->whereNotNull('user_id')->count();
            }

            // Quick actions (pakai permission Spatie -> Gate)
            $canUsersIndex        = $user->can('users.index');
            $canRolesIndex        = $user->can('roles.index');
            $canPermissionsIndex  = $user->can('permissions.index');
            $canRolesCreate       = $user->can('roles.create');

            // (opsional) tautan rute web untuk dipakai di UI
            $links = [
                'users_index'        => route('admin.users.index'),
                'roles_index'        => route('admin.roles.index'),
                'permissions_index'  => route('admin.permissions.index'),
                'roles_create'       => route('admin.roles.create'),
            ];

            return ResponseFormatter::success([
                'greeting' => [
                    'message' => 'Welcome back, '.$user->name.'!',
                    'user'    => [
                        'id'    => $user->id,
                        'name'  => $user->name,
                        'email' => $user->email,
                    ],
                ],
                'stats' => [
                    'total_users'       => $totalUsers,
                    'total_roles'       => $totalRoles,
                    'total_permissions' => $totalPermissions,
                    'active_sessions'   => $activeSessions, // bisa null jika bukan driver database
                ],
                'quick_actions' => [
                    'users_index'        => $canUsersIndex,
                    'roles_index'        => $canRolesIndex,
                    'permissions_index'  => $canPermissionsIndex,
                    'roles_create'       => $canRolesCreate,
                ],
                'links' => $links,
                // (opsional) kirim juga role & permission user yg login
                'me' => [
                    'roles'       => method_exists($user, 'getRoleNames') ? $user->getRoleNames() : [],
                    'permissions' => method_exists($user, 'getAllPermissions') ? $user->getAllPermissions()->pluck('name') : [],
                ],
            ], 'Dashboard data fetched.');
        } catch (\Throwable $e) {
            return ResponseFormatter::error('Failed to fetch dashboard data', 500, [
                'exception' => $e->getMessage(),
            ]);
        }
    }
}
