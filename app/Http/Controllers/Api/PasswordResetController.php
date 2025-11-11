<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Validator;

// Ganti namespace ini kalau file-mu beda.
use App\Helpers\ResponseFormatter;

class PasswordResetController extends Controller
{
    /**
     * POST /api/password/forgot
     * Body JSON: { "email": "user@example.com" }
     */
    public function sendResetLink(Request $request)
    {
        $v = Validator::make($request->all(), [
            'email' => ['required', 'email'],
        ]);

        if ($v->fails()) {
            return ResponseFormatter::error('Validation error', 422, $v->errors());
        }

        $status = Password::sendResetLink($request->only('email'));

        // Jika kena rate limit dari Laravel, beri 429 agar client tahu harus tunggu.
        if ($status === Password::RESET_THROTTLED) {
            // 'passwords.throttled' tersedia di lang bawaan Laravel
            return ResponseFormatter::error(__('passwords.throttled'), 429);
        }

        // Untuk keamanan, kita selalu balas sukses tanpa bocorkan apakah email ada/tidak.
        return ResponseFormatter::success(
            null,
            __('If the account exists, a password reset link has been sent to the email.')
        );
    }

    /**
     * POST /api/password/reset
     * Body JSON:
     * {
     *   "token":"<token-dari-email>",
     *   "email":"user@example.com",
     *   "password":"new-pass",
     *   "password_confirmation":"new-pass"
     * }
     */
    public function resetPassword(Request $request)
    {
        $v = Validator::make($request->all(), [
            'token'    => ['required', 'string'],
            'email'    => ['required', 'email'],
            'password' => ['required', 'confirmed', PasswordRule::defaults()],
        ]);

        if ($v->fails()) {
            return ResponseFormatter::error('Validation error', 422, $v->errors());
        }

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                ])->setRememberToken(Str::random(60));

                $user->save();
                event(new PasswordReset($user));

                // Opsional: kalau pakai Sanctum dan ingin paksa logout dari semua device:
                // $user->tokens()->delete();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return ResponseFormatter::success(null, __('Your password has been reset.'));
        }

        // Token/email invalid/expired, dsb.
        return ResponseFormatter::error(__($status), 400);
    }
}
