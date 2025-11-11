<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware as ControllerMiddleware;

class ProfileController extends Controller implements HasMiddleware
{
    /**
     * Laravel 12: daftar middleware di sini.
     */
    public static function middleware(): array
    {
        return [
            new ControllerMiddleware('auth'), // wajib login
        ];
    }

    /**
     * GET /profile
     */
    public function show(Request $request)
    {
        $user = $request->user();
        return view('admin.profile.show', compact('user'));
    }

    /**
     * PUT /profile
     * Update data dasar profil: name, email, username, phone_number.
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'email'        => [
                'required', 'email', 'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'username'     => [
                'nullable', 'string', 'max:255',
                Rule::unique('users', 'username')->ignore($user->id),
            ],
            'phone_number' => ['nullable', 'string', 'max:15'],
        ]);

        // Jika email berubah, reset verifikasi email
        if ($data['email'] !== $user->email) {
            $user->email = $data['email'];
            $user->email_verified_at = null;
            // Jika ingin kirim ulang email verifikasi, aktifkan:
            // if (method_exists($user, 'sendEmailVerificationNotification')) {
            //     $user->sendEmailVerificationNotification();
            // }
        }

        $user->name         = $data['name'];
        $user->username     = $data['username'] ?? null;
        $user->phone_number = $data['phone_number'] ?? null;
        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * PUT /profile/password
     * Ganti password (perlu password saat ini & konfirmasi).
     */
    public function updatePassword(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'current_password'      => ['required', 'current_password'], // cek password aktif
            'password'              => ['required', 'confirmed', Password::min(6)],
            'password_confirmation' => ['required'],
        ]);

        $user->password = Hash::make($data['password']);
        $user->save();

        return back()->with('success', 'Password berhasil diganti.');
    }
}
