<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

// Ganti namespace ini jika file kamu berbeda:
// mis. use App\Helpers\ResponsesFormatter as ResponseFormatter;
use App\Helpers\ResponseFormatter;

class AuthController extends Controller
{
    /**
     * POST /api/auth/register
     */
    public function register(Request $request)
    {
        $v = Validator::make($request->all(), [
            'name'         => ['required','string','max:255'],
            'email'        => ['required','email','max:255','unique:users,email'],
            'password'     => ['required','confirmed', Password::min(6)],
            'username'     => ['nullable','string','max:255','unique:users,username'],
            'phone_number' => ['nullable','string','max:30'],
        ]);

        if ($v->fails()) {
            return ResponseFormatter::error('Validation error', 422, $v->errors());
        }

        try {
            $user = null;

            DB::transaction(function () use ($request, &$user) {
                $user = User::create([
                    'name'         => $request->name,
                    'email'        => $request->email,
                    'password'     => Hash::make($request->password),
                    'username'     => $request->username,
                    'phone_number' => $request->phone_number,
                ]);

                // assign default role "user" jika ada
                if (Role::where('name', 'user')->exists()) {
                    $user->assignRole('user');
                }
            });

            $token = $user->createToken('api')->plainTextToken;

            return ResponseFormatter::success([
                'access_token' => $token,
                'token_type'   => 'Bearer',
                'user'         => $user->only(['id','name','email','username','phone_number','created_at']),
                'roles'        => $user->getRoleNames(),
                'permissions'  => $user->getAllPermissions()->pluck('name'),
            ], 'Registration successful.');
        } catch (\Throwable $e) {
            return ResponseFormatter::error('Registration failed', 500, ['exception' => $e->getMessage()]);
        }
    }

    /**
     * POST /api/auth/login
     */
    public function login(Request $request)
    {
        $v = Validator::make($request->all(), [
            'email'    => ['required','email'],
            'password' => ['required','string'],
            'device'   => ['nullable','string','max:100'],
            'single'   => ['sometimes','boolean'], // kalau true: hapus token-token lama
        ]);

        if ($v->fails()) {
            return ResponseFormatter::error('Validation error', 422, $v->errors());
        }

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return ResponseFormatter::error('Invalid credentials.', 401);
        }

        if ($request->boolean('single')) {
            // opsional: paksa single device - hapus token lama
            $user->tokens()->delete();
        }

        $tokenName = $request->device ?: 'api';
        $token = $user->createToken($tokenName)->plainTextToken;

        return ResponseFormatter::success([
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => $user->only(['id','name','email','username','phone_number','created_at']),
            'roles'        => $user->getRoleNames(),
            'permissions'  => $user->getAllPermissions()->pluck('name'),
        ], 'Login successful.');
    }

    /**
     * GET /api/auth/me
     */
    public function me(Request $request)
    {
        $u = $request->user();

        return ResponseFormatter::success([
            'user'         => $u->only(['id','name','email','username','phone_number','created_at']),
            'roles'        => $u->getRoleNames(),
            'permissions'  => $u->getAllPermissions()->pluck('name'),
        ], 'Profile fetched.');
    }

    /**
     * POST /api/auth/logout
     * - default: revoke token saat ini
     * - dengan ?all=true: revoke semua token user
     */
    public function logout(Request $request)
    {
        try {
            $user = $request->user();

            if ($request->boolean('all')) {
                $user->tokens()->delete();
            } else {
                $request->user()->currentAccessToken()?->delete();
            }

            return ResponseFormatter::success(null, 'Logged out.');
        } catch (\Throwable $e) {
            return ResponseFormatter::error('Logout failed', 500, ['exception' => $e->getMessage()]);
        }
    }
}
