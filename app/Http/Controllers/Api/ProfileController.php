<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password as PasswordRule;
use App\Helpers\ResponseFormatter;

class ProfileController extends Controller
{
    /**
     * GET /api/profile
     */
    public function show(Request $request)
    {
        $user = $request->user();

        return ResponseFormatter::success([
            'user' => $user->only(['id','name','email','username','phone_number','email_verified_at','created_at','updated_at']),
            'roles' => method_exists($user, 'getRoleNames') ? $user->getRoleNames() : [],
            'permissions' => method_exists($user, 'getAllPermissions') ? $user->getAllPermissions()->pluck('name') : [],
        ], 'Profile fetched.');
    }

    /**
     * PUT /api/profile
     * Body (JSON): { name, email, username?, phone_number? }
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name'         => ['required','string','max:255'],
            'email'        => ['required','email','max:255', Rule::unique('users','email')->ignore($user->id)],
            'username'     => ['nullable','string','max:255', Rule::unique('users','username')->ignore($user->id)],
            'phone_number' => ['nullable','string','max:15'],
        ]);

        $emailChanged = $data['email'] !== $user->email;

        // update fields
        $user->name         = $data['name'];
        $user->email        = $data['email'];
        $user->username     = $data['username'] ?? null;
        $user->phone_number = $data['phone_number'] ?? null;

        // jika email berubah, reset verifikasi
        if ($emailChanged) {
            $user->email_verified_at = null;

            // (opsional) kirim ulang verifikasi:
            // if (method_exists($user, 'sendEmailVerificationNotification')) {
            //     $user->sendEmailVerificationNotification();
            // }
        }

        $user->save();

        return ResponseFormatter::success([
            'user' => $user->only(['id','name','email','username','phone_number','email_verified_at','created_at','updated_at']),
            'email_verification_reset' => $emailChanged,
        ], 'Profile updated.');
    }

    /**
     * PUT /api/profile/password
     * Body (JSON): { current_password, password, password_confirmation }
     * Opsional: { revoke_all_tokens: true } untuk logout semua device (Sanctum)
     */
    public function updatePassword(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'current_password'      => ['required','current_password'], // validasi password aktif
            'password'              => ['required','confirmed', PasswordRule::min(6)],
            'password_confirmation' => ['required'],
            'revoke_all_tokens'     => ['sometimes','boolean'],
        ]);

        // ganti password
        $user->password = Hash::make($data['password']);
        $user->save();

        // opsional: revoke semua token (paksa login ulang di semua device)
        if ($request->boolean('revoke_all_tokens')) {
            $user->tokens()->delete();
        } else {
            // minimal: revoke token saat ini agar user re-login di device ini
            $request->user()->currentAccessToken()?->delete();
        }

        return ResponseFormatter::success(null, 'Password updated.');
    }
}
