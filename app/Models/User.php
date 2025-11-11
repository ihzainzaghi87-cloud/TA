<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
// Spatie:
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements CanResetPasswordContract
{
    use HasApiTokens, Notifiable, HasRoles, CanResetPassword;

    // Jika ingin pastikan guard untuk Spatie (default 'web'):
    protected $guard_name = 'web';

    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'phone_number',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // public function sendPasswordResetNotification($token)
    // {
    //     $url = url(route('password.reset', [
    //         'token' => $token,
    //         'email' => $this->email,
    //     ], false));

    //     $this->notify((new ResetPasswordNotification($token))->createUrlUsing(function() use ($url) {
    //         return $url; // atau URL custom kamu
    //     }));
    // }
}
