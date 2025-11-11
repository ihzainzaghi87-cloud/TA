<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;

// Laravel 12: daftar middleware via interface
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware as ControllerMiddleware;

// Helper respons JSON
use App\Helpers\ResponseFormatter;

class PermissionController extends Controller implements HasMiddleware
{
    /**
     * Middleware untuk API (auth:sanctum + permission per aksi).
     */
    public static function middleware(): array
    {
        return [
            new ControllerMiddleware('auth:sanctum'),

            // List / index
            (new ControllerMiddleware('permission:permissions.index|permissions.view'))->only(['index','show']),

            // Create
            (new ControllerMiddleware('permission:permissions.create'))->only(['store']),

            // Update
            (new ControllerMiddleware('permission:permissions.update'))->only(['update']),

            // Delete
            (new ControllerMiddleware('permission:permissions.delete'))->only(['destroy']),
        ];
    }

    /**
     * Serializer ringkas untuk Permission.
     */
    protected function serializePermission(Permission $p): array
    {
        return [
            'id'            => $p->id,
            'name'          => $p->name,
            'guard_name'    => $p->guard_name,
            'roles_count'   => $p->roles()->count(),
            'users_count'   => $p->users()->count(), // direct users
            'created_at'    => $p->created_at,
            'updated_at'    => $p->updated_at,
        ];
    }

    /**
     * GET /api/admin/permissions
     * Query: q (search by name), per_page (default 20)
     */
    public function index(Request $request)
    {
        $q       = (string) $request->query('q', '');
        $perPage = (int) ($request->query('per_page', 20)) ?: 20;

        $paginator = Permission::query()
            ->when($q !== '', fn($s) => $s->where('name', 'like', "%{$q}%"))
            ->withCount(['roles','users'])
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString();

        $data = [
            'items' => collect($paginator->items())->map(function (Permission $p) {
                return [
                    'id'          => $p->id,
                    'name'        => $p->name,
                    'guard_name'  => $p->guard_name,
                    'roles_count' => $p->roles_count ?? $p->roles()->count(),
                    'users_count' => $p->users_count ?? $p->users()->count(),
                    'created_at'  => $p->created_at,
                    'updated_at'  => $p->updated_at,
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

        return ResponseFormatter::success($data, 'Permissions fetched.');
    }

    /**
     * POST /api/admin/permissions
     * Body: { name }
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:150','unique:permissions,name'],
        ]);

        $perm = Permission::create([
            'name' => $data['name'],
            // 'guard_name' => 'web', // biarkan default, atau set manual jika perlu
        ]);

        return ResponseFormatter::success($this->serializePermission($perm), 'Permission created.');
    }

    /**
     * GET /api/admin/permissions/{permission}
     * Detail permission + daftar role & ringkasan direct users.
     */
    public function show(Permission $permission)
    {
        $data = $this->serializePermission($permission);

        // role-role yang memakai permission ini
        $data['roles'] = $permission->roles()->orderBy('name')->pluck('name')->values();

        // jumlah direct users (bukan listing penuh supaya ringan)
        $data['direct_users_count'] = $permission->users()->count();

        return ResponseFormatter::success($data, 'Permission detail fetched.');
    }

    /**
     * PUT /api/admin/permissions/{permission}
     * Body: { name }
     */
    public function update(Request $request, Permission $permission)
    {
        $data = $request->validate([
            'name' => ['required','string','max:150', Rule::unique('permissions','name')->ignore($permission->id)],
        ]);

        $permission->update(['name' => $data['name']]);

        return ResponseFormatter::success($this->serializePermission($permission->fresh()), 'Permission updated.');
    }

    /**
     * DELETE /api/admin/permissions/{permission}
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();

        return ResponseFormatter::success(null, 'Permission deleted.');
    }
}
