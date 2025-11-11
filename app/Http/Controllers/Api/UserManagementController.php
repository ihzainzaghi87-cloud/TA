<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

// Laravel 12: daftar middleware via interface
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware as ControllerMiddleware;

// Helper respons (sesuaikan jika namanya berbeda)
use App\Helpers\ResponseFormatter;

class UserManagementController extends Controller implements HasMiddleware
{
    /**
     * Laravel 12: definisikan middleware di sini (bukan di __construct()).
     * Gunakan guard Sanctum untuk API.
     */
    public static function middleware(): array
    {
        return [
            new ControllerMiddleware('auth:sanctum'),

            // LIST
            (new ControllerMiddleware('permission:users.index|users.view'))->only(['index']),

            // TAMBAH
            (new ControllerMiddleware('permission:users.create'))->only(['store']),

            // SHOW DETAIL
            (new ControllerMiddleware('permission:users.view'))->only(['show']),

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
     * Utility: serialize user dengan roles & permissions.
     */
    protected function serializeUser(User $user): array
    {
        return [
            'id'            => $user->id,
            'name'          => $user->name,
            'email'         => $user->email,
            'username'      => $user->username,
            'phone_number'  => $user->phone_number,
            'email_verified_at' => $user->email_verified_at,
            'created_at'    => $user->created_at,
            'updated_at'    => $user->updated_at,
            'roles'         => method_exists($user, 'getRoleNames') ? $user->getRoleNames()->values() : [],
            'permissions'   => method_exists($user, 'getAllPermissions') ? $user->getAllPermissions()->pluck('name')->values() : [],
        ];
    }

    /**
     * GET /api/admin/users
     * Query: q (search), page, per_page
     */
    public function index(Request $request)
    {
        $q        = (string) $request->query('q', '');
        $perPage  = (int) ($request->query('per_page', 15)) ?: 15;

        $paginator = User::query()
            ->when($q !== '', function ($s) use ($q) {
                $s->where(function ($w) use ($q) {
                    $w->where('name', 'like', "%{$q}%")
                      ->orWhere('email', 'like', "%{$q}%");
                });
            })
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString();

        $data = [
            'items' => collect($paginator->items())->map(fn ($u) => $this->serializeUser($u))->values(),
            'meta'  => [
                'current_page' => $paginator->currentPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
                'last_page'    => $paginator->lastPage(),
                'has_more'     => $paginator->hasMorePages(),
            ],
        ];

        return ResponseFormatter::success($data, 'Users fetched.');
    }

    /**
     * POST /api/admin/users
     * Body: name, email, password(+confirmed), username?, phone_number?, roles[]?, permissions[]?
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'         => ['required','string','max:255'],
            'email'        => ['required','email','max:255','unique:users,email'],
            'username'     => ['nullable','string','max:255','unique:users,username'],
            'phone_number' => ['nullable','string','max:15'],
            'password'     => ['required','confirmed', Password::min(6)],
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

        if (!empty($data['roles'])) {
            $user->syncRoles($data['roles']);
        }
        if (!empty($data['permissions'])) {
            $user->syncPermissions($data['permissions']);
        }

        return ResponseFormatter::success($this->serializeUser($user), 'User created.');
    }

    /**
     * GET /api/admin/users/{user}
     * Detail + mapping permission via roles & direct.
     */
    public function show(User $user)
    {
        // role + permission tiap role
        $roles = $user->roles()->with('permissions')->get();

        $rolePermissionsMap = [];
        foreach ($roles as $role) {
            $rolePermissionsMap[$role->name] = $role->permissions->pluck('name')->sort()->values()->toArray();
        }

        $permissionsViaRoles = collect($rolePermissionsMap)->flatten()->unique()->sort()->values()->toArray();
        $directPermissions   = $user->getDirectPermissions()->pluck('name')->sort()->values()->toArray();
        $effectivePermissions= collect($permissionsViaRoles)->merge($directPermissions)->unique()->sort()->values()->toArray();

        $payload = [
            'user'                  => $this->serializeUser($user),
            'role_names'            => $roles->pluck('name')->values(),
            'permissions_via_roles' => $permissionsViaRoles,
            'role_permissions_map'  => $rolePermissionsMap,
            'direct_permissions'    => $directPermissions,
            'effective_permissions' => $effectivePermissions,
        ];

        return ResponseFormatter::success($payload, 'User detail fetched.');
    }

    /**
     * PUT /api/admin/users/{user}
     * Update profil (name, email, username, phone_number)
     */
    public function updateProfile(Request $request, User $user)
    {
        $data = $request->validate([
            'name'         => ['required','string','max:255'],
            'email'        => ['required','email','max:255', Rule::unique('users','email')->ignore($user->id)],
            'username'     => ['nullable','string','max:255', Rule::unique('users','username')->ignore($user->id)],
            'phone_number' => ['nullable','string','max:15'],
        ]);

        $emailChanged = $data['email'] !== $user->email;

        $user->name         = $data['name'];
        $user->email        = $data['email'];
        $user->username     = $data['username'] ?? null;
        $user->phone_number = $data['phone_number'] ?? null;

        if ($emailChanged) {
            $user->email_verified_at = null;
        }

        $user->save();

        return ResponseFormatter::success([
            'user' => $this->serializeUser($user),
            'email_verification_reset' => $emailChanged,
        ], 'User profile updated.');
    }

    /**
     * PUT /api/admin/users/{user}/password
     * Body: password, password_confirmation
     */
    public function updatePassword(Request $request, User $user)
    {
        $data = $request->validate([
            'password'              => ['required','confirmed', Password::min(6)],
            'password_confirmation' => ['required'],
        ]);

        $user->password = Hash::make($data['password']);
        $user->save();

        return ResponseFormatter::success(null, 'User password updated.');
    }

    /**
     * POST /api/admin/users/{user}/sync-roles
     * Body: roles[] (array of role names)
     */
    public function syncRoles(Request $request, User $user)
    {
        $data = $request->validate([
            'roles'   => ['array'],
            'roles.*' => ['string'],
        ]);

        $user->syncRoles($data['roles'] ?? []);

        return ResponseFormatter::success([
            'user'  => $this->serializeUser($user->fresh()),
            'roles' => $user->getRoleNames()->values(),
        ], 'User roles synchronized.');
    }

    /**
     * POST /api/admin/users/{user}/sync-permissions
     * Body: permissions[] (array of permission names)
     * Catatan: ini direct permission (bukan via role)
     */
    public function syncPermissions(Request $request, User $user)
    {
        $data = $request->validate([
            'permissions'   => ['array'],
            'permissions.*' => ['string'],
        ]);

        $user->syncPermissions($data['permissions'] ?? []);

        // hitung ulang effective permissions
        $roles = $user->roles()->with('permissions')->get();
        $permissionsViaRoles = $roles->flatMap(fn($r) => $r->permissions->pluck('name'))
                                     ->unique()->sort()->values()->toArray();

        return ResponseFormatter::success([
            'user'                => $this->serializeUser($user->fresh()),
            'direct_permissions'  => $user->getDirectPermissions()->pluck('name')->values(),
            'permissions_via_roles'=> $permissionsViaRoles,
            'effective_permissions'=> collect($permissionsViaRoles)->merge($user->getDirectPermissions()->pluck('name'))->unique()->values(),
        ], 'User permissions synchronized.');
    }
}
