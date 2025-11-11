<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
// ① import trait & class Middleware untuk controller
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware as ControllerMiddleware;

class PermissionController extends Controller implements HasMiddleware
{
    /**
     * Laravel 12: definisikan middleware di sini per aksi.
     */
    public static function middleware(): array
    {
        return [
            new ControllerMiddleware('auth'),

            // List / index
            (new ControllerMiddleware('permission:permissions.index|permissions.view'))->only(['index']),

            // Create + Store
            (new ControllerMiddleware('permission:permissions.create'))->only(['create','store']),

            // Edit + Update
            (new ControllerMiddleware('permission:permissions.update'))->only(['edit','update']),

            // Delete
            (new ControllerMiddleware('permission:permissions.delete'))->only(['destroy']),
        ];
    }

    public function index()
    {
        $permissions = Permission::orderBy('name')->paginate(20);
        return view('admin.permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('admin.permissions.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:150','unique:permissions,name'],
        ]);

        Permission::create([
            'name' => $data['name'],
            // 'guard_name' => 'web',
        ]);

        return redirect()->route('admin.permissions.index')->with('success', 'Permission berhasil dibuat.');
    }

    public function edit(Permission $permission)
    {
        return view('admin.permissions.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        $data = $request->validate([
            'name' => ['required','string','max:150','unique:permissions,name,'.$permission->id],
        ]);

        $permission->update(['name' => $data['name']]);

        return redirect()->route('admin.permissions.index')->with('success', 'Permission berhasil diupdate.');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();
        return redirect()->route('admin.permissions.index')->with('success', 'Permission berhasil dihapus.');
    }
}
