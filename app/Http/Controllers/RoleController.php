<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\DB;

// ✅ L12: daftarkan middleware via interface HasMiddleware
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware as ControllerMiddleware;

class RoleController extends Controller implements HasMiddleware
{
    /**
     * Laravel 12: definisikan middleware di sini per aksi.
     */
    public static function middleware(): array
    {
        return [
            // selalu butuh login
            new ControllerMiddleware('auth'),

            // LIST roles
            (new ControllerMiddleware('permission:roles.index|roles.view'))->only(['index']),

            // FORM create + store
            (new ControllerMiddleware('permission:roles.create'))->only(['create','store']),

            // FORM edit + update nama role
            (new ControllerMiddleware('permission:roles.update'))->only(['edit','update']),

            // Hapus role
            (new ControllerMiddleware('permission:roles.delete'))->only(['destroy']),

            // Sinkronisasi permissions ke role
            (new ControllerMiddleware('permission:roles.sync-permissions|roles.update'))->only(['syncPermissions']),
        ];
    }

    public function index()
    {
        $roles = Role::query()
            ->withCount('permissions')
            ->withCount(['users as users_count' => function($q){ $q->select(DB::raw('count(*)')); }])
            ->orderBy('name')
            ->paginate(15);

        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        return view('admin.roles.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:roles,name'],
        ]);

        $role = Role::create([
            'name' => $data['name'],
            // 'guard_name' => 'web', // (opsional) default guard
        ]);

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Role berhasil dibuat.');
    }

    public function show(Request $request, Role $role)
    {
        $q = $request->string('q')->toString();

        // permission yang sudah melekat pada role
        $assignedPermissions = $role->permissions()->orderBy('name')->get();
        $assignedNames       = $assignedPermissions->pluck('name')->toArray();

        // semua permission (untuk form sinkron)
        $allPermissions = Permission::orderBy('name')->get();

        // daftar user yang memiliki role ini
        $users = User::query()
            ->role($role->name)
            ->when($q, fn($s) => $s->where(function($w) use ($q) {
                $w->where('name','like',"%{$q}%")
                ->orWhere('email','like',"%{$q}%");
            }))
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('admin.roles.show', compact(
            'role',
            'assignedPermissions',
            'assignedNames',
            'allPermissions',
            'users',
            'q'
        ));
    }

    public function edit(Role $role)
    {
        $permissions = Permission::orderBy('name')->get();
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:roles,name,'.$role->id],
        ]);

        $role->update(['name' => $data['name']]);

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Role berhasil diupdate.');
    }

    public function destroy(Role $role)
    {
        $role->delete();

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Role berhasil dihapus.');
    }

    // POST: sinkronisasi permission ke role
    public function syncPermissions(Request $request, Role $role)
    {
        $data = $request->validate([
            'permissions'   => ['array'],
            'permissions.*' => ['string'], // kirim array nama permission
        ]);

        $role->syncPermissions($data['permissions'] ?? []);

        return redirect()
            ->route('admin.roles.edit', $role)
            ->with('success', 'Permission role berhasil disinkron.');
    }
}
