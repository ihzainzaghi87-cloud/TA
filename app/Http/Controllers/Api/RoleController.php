<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

// L12: daftar middleware via interface
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware as ControllerMiddleware;

// Helper respons (sesuaikan jika berbeda)
use App\Helpers\ResponseFormatter;

class RoleController extends Controller implements HasMiddleware
{
    /**
     * Middleware untuk API (auth:sanctum + permission per aksi).
     */
    public static function middleware(): array
    {
        return [
            new ControllerMiddleware('auth:sanctum'),

            // LIST roles
            (new ControllerMiddleware('permission:roles.index|roles.view'))->only(['index']),

            // CREATE
            (new ControllerMiddleware('permission:roles.create'))->only(['store']),

            // SHOW detail
            (new ControllerMiddleware('permission:roles.view'))->only(['show']),

            // UPDATE nama role
            (new ControllerMiddleware('permission:roles.update'))->only(['update']),

            // DELETE role
            (new ControllerMiddleware('permission:roles.delete'))->only(['destroy']),

            // SYNC permissions
            (new ControllerMiddleware('permission:roles.sync-permissions|roles.update'))->only(['syncPermissions']),
        ];
    }

    /**
     * Serializer ringkas untuk Role.
     */
    protected function serializeRole(Role $role): array
    {
        return [
            'id'                 => $role->id,
            'name'               => $role->name,
            'guard_name'         => $role->guard_name,
            'permissions_count'  => $role->permissions()->count(),
            'users_count'        => $role->users()->count(),
            'created_at'         => $role->created_at,
            'updated_at'         => $role->updated_at,
        ];
    }

    /**
     * GET /api/admin/roles
     * Query: q (filter by name), per_page (default 15)
     */
    public function index(Request $request)
    {
        $q       = (string) $request->query('q', '');
        $perPage = (int) ($request->query('per_page', 15)) ?: 15;

        $paginator = Role::query()
            ->when($q !== '', fn($s) => $s->where('name', 'like', "%{$q}%"))
            ->withCount('permissions')
            ->withCount(['users as users_count' => function($sub){ $sub->select(DB::raw('count(*)')); }])
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString();

        $data = [
            'items' => collect($paginator->items())->map(function ($role) {
                // pastikan konsisten bentuknya
                return [
                    'id'                 => $role->id,
                    'name'               => $role->name,
                    'guard_name'         => $role->guard_name,
                    'permissions_count'  => $role->permissions_count ?? $role->permissions()->count(),
                    'users_count'        => $role->users_count ?? $role->users()->count(),
                    'created_at'         => $role->created_at,
                    'updated_at'         => $role->updated_at,
                ];
            })->values(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
                'last_page'    => $paginator->lastPage(),
                'has_more'     => $paginator->hasMorePages(),
            ],
        ];

        return ResponseFormatter::success($data, 'Roles fetched.');
    }

    /**
     * POST /api/admin/roles
     * Body: { name }
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:100','unique:roles,name'],
        ]);

        $role = Role::create([
            'name' => $data['name'],
            // 'guard_name' => 'web', // opsional (default 'web')
        ]);

        return ResponseFormatter::success($this->serializeRole($role), 'Role created.');
    }

    /**
     * GET /api/admin/roles/{role}
     * Detail role + daftar permission & users yang memiliki role tsb.
     * Query: q (filter users by name/email), per_page (users)
     */
    public function show(Request $request, Role $role)
    {
        $q       = (string) $request->query('q', '');
        $perPage = (int) ($request->query('per_page', 12)) ?: 12;

        // permissions yg sudah melekat pada role
        $assignedPermissions = $role->permissions()->orderBy('name')->pluck('name')->values();

        // semua permission (jika ingin dipakai untuk form di client)
        $allPermissions = Permission::orderBy('name')->pluck('name')->values();

        // users yang punya role ini
        $usersPaginator = User::query()
            ->role($role->name)
            ->when($q !== '', function ($s) use ($q) {
                $s->where(function($w) use ($q) {
                    $w->where('name','like',"%{$q}%")
                      ->orWhere('email','like',"%{$q}%");
                });
            })
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString();

        $users = collect($usersPaginator->items())->map(function (User $u) {
            return [
                'id'           => $u->id,
                'name'         => $u->name,
                'email'        => $u->email,
                'username'     => $u->username,
                'phone_number' => $u->phone_number,
                'created_at'   => $u->created_at,
            ];
        })->values();

        return ResponseFormatter::success([
            'role'               => $this->serializeRole($role),
            'assigned_permissions'=> $assignedPermissions,
            'all_permissions'    => $allPermissions, // boleh diabaikan di client jika tidak perlu
            'users' => [
                'items' => $users,
                'meta'  => [
                    'current_page' => $usersPaginator->currentPage(),
                    'per_page'     => $usersPaginator->perPage(),
                    'total'        => $usersPaginator->total(),
                    'last_page'    => $usersPaginator->lastPage(),
                    'has_more'     => $usersPaginator->hasMorePages(),
                ],
            ],
        ], 'Role detail fetched.');
    }

    /**
     * PUT /api/admin/roles/{role}
     * Body: { name }
     */
    public function update(Request $request, Role $role)
    {
        $data = $request->validate([
            'name' => ['required','string','max:100', Rule::unique('roles','name')->ignore($role->id)],
        ]);

        $role->update(['name' => $data['name']]);

        return ResponseFormatter::success($this->serializeRole($role->fresh()), 'Role updated.');
    }

    /**
     * DELETE /api/admin/roles/{role}
     */
    public function destroy(Role $role)
    {
        $role->delete();

        return ResponseFormatter::success(null, 'Role deleted.');
    }

    /**
     * POST /api/admin/roles/{role}/sync-permissions
     * Body: { permissions: ["perm.a","perm.b", ...] }
     */
    public function syncPermissions(Request $request, Role $role)
    {
        $data = $request->validate([
            'permissions'   => ['array'],
            'permissions.*' => ['string'],
        ]);

        $role->syncPermissions($data['permissions'] ?? []);

        return ResponseFormatter::success([
            'role'        => $this->serializeRole($role->fresh()),
            'permissions' => $role->permissions()->orderBy('name')->pluck('name')->values(),
        ], 'Role permissions synchronized.');
    }
}
