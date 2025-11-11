<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    // GET /login
    public function showLoginForm()
    {
        return view('auth.login'); // resources/views/auth/login.blade.php
    }

    // POST /login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Session-based login (guard web)
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'))->with('success', 'Login berhasil.');
        }

        return back()->withErrors(['email' => 'Kredensial tidak valid.'])->withInput();
    }

    // GET /register
    public function showRegisterForm()
    {
        return view('auth.register'); // resources/views/auth/register.blade.php
    }

    // POST /register
    public function register(Request $request)
    {
        $data = $request->validate([
            'name'         => ['required','string','max:255'],
            'email'        => ['required','email','max:255','unique:users,email'],
            'password'     => ['required','confirmed', Password::min(6)],
            'username'     => ['nullable','string','max:255','unique:users,username'],
            'phone_number' => ['nullable','string','max:30'],
        ]);

        $user = User::create([
            'name'         => $data['name'],
            'email'        => $data['email'],
            'password'     => Hash::make($data['password']),
            'username'     => $data['username'] ?? null,
            'phone_number' => $data['phone_number'] ?? null,
        ]);

        // Assign default role "user" kalau ada
        if (Role::where('name', 'user')->exists()) {
            $user->assignRole('user');
            // opsional: sinkron label kolom users.role jika kamu menyimpannya
            // $user->update(['role' => 'user']);
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard')->with('success', 'Registrasi berhasil.');
    }

    // POST /logout
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logout berhasil.');
    }
}
