<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware as ControllerMiddleware;

class UserManagementController extends Controller implements HasMiddleware
{
    /**
     * Laravel 12: definisikan middleware di sini (bukan di __construct()).
     */
    public static function middleware(): array
    {
        return [
            new ControllerMiddleware('auth'),

            // LIST
            (new ControllerMiddleware('permission:users.index|users.view'))->only(['index']),

            // TAMBAH
            (new ControllerMiddleware('permission:users.create'))->only(['create','store']),

            // SHOW DETAIL
            (new ControllerMiddleware('permission:users.view'))->only(['show']),

            // FORM EDIT
            (new ControllerMiddleware('permission:users.update|users.assign-roles|users.grant-permissions'))->only(['edit']),

            // UPDATE PROFIL
            (new ControllerMiddleware('permission:users.update'))->only(['updateProfile']),

            // UPDATE PASSWORD
            (new ControllerMiddleware('permission:users.update'))->only(['updatePassword']),

            // SINKRON ROLE/PERMISSION
            (new ControllerMiddleware('permission:users.assign-roles|users.update'))->only(['syncRoles']),
            (new ControllerMiddleware('permission:users.grant-permissions|users.update'))->only(['syncPermissions']),
        ];
    }

    /**
     * GET /admin/users
     */
    public function index(Request $request)
    {
        $q = $request->string('q')->toString();

        $users = User::query()
            ->when($q, fn ($s) => $s->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                  ->orWhere('email', 'like', "%{$q}%");
            }))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.index', compact('users', 'q'));
    }

    /**
     * GET /admin/users/create
     */
    public function create()
    {
        $roles       = Role::orderBy('name')->get();
        $permissions = Permission::orderBy('name')->get();

        return view('admin.users.create', compact('roles','permissions'));
    }

    /**
     * POST /admin/users
     * Tambah user baru (+ opsional assign role/permission).
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'         => ['required','string','max:255'],
            'email'        => ['required','email','max:255','unique:users,email'],
            'username'     => ['nullable','string','max:255','unique:users,username'],
            'phone_number' => ['nullable','string','max:15'],
            'password'     => ['required','confirmed', Password::min(6)],
            // opsional input dari form
            'roles'        => ['array'],
            'roles.*'      => ['string'],
            'permissions'  => ['array'],
            'permissions.*'=> ['string'],
        ]);

        $user = User::create([
            'name'         => $data['name'],
            'email'        => $data['email'],
            'username'     => $data['username'] ?? null,
            'phone_number' => $data['phone_number'] ?? null,
            'password'     => Hash::make($data['password']),
        ]);

        // opsional mapping role/permission
        if (!empty($data['roles'])) {
            $user->syncRoles($data['roles']);
        }
        if (!empty($data['permissions'])) {
            $user->syncPermissions($data['permissions']);
        }

        return redirect()
            ->route('admin.users.edit', $user)
            ->with('success', 'User baru berhasil dibuat.');
    }

    /**
     * GET /admin/users/{user}
     */
    public function show(User $user)
    {
        // Roles + permissions milik setiap role
        $roles = $user->roles()->with('permissions')->get();

        $rolePermissionsMap = [];
        foreach ($roles as $role) {
            $rolePermissionsMap[$role->name] = $role->permissions
                ->pluck('name')
                ->sort()
                ->values()
                ->toArray();
        }

        // Agregat permissions via role (unik)
        $permissionsViaRoles = collect($rolePermissionsMap)
            ->flatten()
            ->unique()
            ->sort()
            ->values()
            ->toArray();

        // ← NEW: direct permissions (izin yang menempel langsung ke user, bukan lewat role)
        $directPermissions = $user->getDirectPermissions()
            ->pluck('name')
            ->sort()
            ->values()
            ->toArray();

        // (opsional) gabungan efektif: via role + direct
        $effectivePermissions = collect($permissionsViaRoles)
            ->merge($directPermissions)
            ->unique()
            ->sort()
            ->values()
            ->toArray();

        $roleNames = $roles->pluck('name')->values()->toArray();

        return view('admin.users.show', compact(
            'user',
            'roleNames',
            'permissionsViaRoles',
            'rolePermissionsMap',
            'directPermissions',
            'effectivePermissions'
        ));
    }

    /**
     * GET /admin/users/{user}/edit
     * Form edit: profil + password + (di view Anda) role/permission.
     */
    public function edit(User $user)
    {
        $roles         = Role::orderBy('name')->get();
        $permissions   = Permission::orderBy('name')->get();
        $userRoles     = $user->getRoleNames()->toArray();
        $userPerms     = $user->getPermissionNames()->toArray();

        return view('admin.users.edit', compact('user','roles','permissions','userRoles','userPerms'));
    }

    /**
     * PUT /admin/users/{user}
     * Update data profil: name, email, username, phone_number.
     */
    public function updateProfile(Request $request, User $user)
    {
        $data = $request->validate([
            'name'         => ['required','string','max:255'],
            'email'        => ['required','email','max:255', Rule::unique('users','email')->ignore($user->id)],
            'username'     => ['nullable','string','max:255', Rule::unique('users','username')->ignore($user->id)],
            'phone_number' => ['nullable','string','max:15'],
        ]);

        if ($data['email'] !== $user->email) {
            $user->email = $data['email'];
            $user->email_verified_at = null; // reset verifikasi jika email berubah
        }

        $user->name         = $data['name'];
        $user->username     = $data['username'] ?? null;
        $user->phone_number = $data['phone_number'] ?? null;
        $user->save();

        return back()->with('success', 'Profil user berhasil diperbarui.');
    }

    /**
     * PUT /admin/users/{user}/password
     * Update password user (admin tidak perlu current_password).
     */
    public function updatePassword(Request $request, User $user)
    {
        $data = $request->validate([
            'password'              => ['required','confirmed', Password::min(6)],
            'password_confirmation' => ['required'],
        ]);

        $user->password = Hash::make($data['password']);
        $user->save();

        return back()->with('success', 'Password user berhasil diganti.');
    }

    /**
     * POST: sinkron role (checkbox multiple)
     */
    public function syncRoles(Request $request, User $user)
    {
        $data = $request->validate([
            'roles'   => ['array'],
            'roles.*' => ['string'], // nama role
        ]);

        $user->syncRoles($data['roles'] ?? []);

        return redirect()
            ->route('admin.users.edit', $user)
            ->with('success', 'Role user berhasil disinkron.');
    }

    /**
     * POST: grant/revoke direct permissions (checkbox multiple)
     */
    public function syncPermissions(Request $request, User $user)
    {
        $data = $request->validate([
            'permissions'   => ['array'],
            'permissions.*' => ['string'], // nama permission
        ]);

        $user->syncPermissions($data['permissions'] ?? []);

        return redirect()
            ->route('admin.users.edit', $user)
            ->with('success', 'Permission user berhasil disinkron.');
    }
}
