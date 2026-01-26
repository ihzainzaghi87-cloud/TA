@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')

<div class="bg-white p-10 sm:p-14 rounded-[35px] shadow-[0_30px_60px_-15px_rgba(0,0,0,0.1)] border border-gray-100 w-full max-w-[580px] mx-auto relative">

    <div class="flex flex-col items-center justify-center mb-12">
        <div class="flex items-center gap-4">
            <!-- <img src="{{ asset('ui/logo.png') }}" alt="Logo" class="w-12 h-12 object-contain"> -->
            <h1 class="text-[28px] font-extrabold text-black tracking-tight font-['Poppins']">The Paranoia</h1>
        </div>
    </div>

    <div class="mb-8 text-center sm:text-left">
        <h2 class="text-[30px] font-bold text-black font-['Poppins']">Reset Password</h2>
        <p class="text-gray-500 mt-1 text-sm">Enter your new password to secure your account.</p>
    </div>

    <form method="POST" action="{{ route('password.update') }}" class="space-y-6" x-data="{ showPassword: false, showConfirmPassword: false }">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <!-- Email (Read-only) -->
        <div>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                    <i class="far fa-envelope text-gray-400 text-lg"></i>
                </div>
                <input id="email"
                       name="email"
                       type="email"
                       required
                       readonly
                       value="{{ old('email', $email) }}"
                       class="block w-full pl-14 pr-6 py-4 border border-gray-200 rounded-full bg-gray-100 text-gray-900 font-medium cursor-not-allowed">
            </div>
            @error('email')
                <p class="mt-2 text-sm text-red-600 pl-4 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <!-- New Password -->
        <div>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                    <i class="fas fa-lock text-black text-lg group-focus-within:text-black transition-colors"></i>
                </div>
                <input id="password"
                       name="password"
                       :type="showPassword ? 'text' : 'password'"
                       required
                       class="block w-full pl-14 pr-14 py-4 border border-gray-200 rounded-full bg-white text-gray-900 placeholder-gray-400 font-medium focus:outline-none focus:border-black focus:ring-4 focus:ring-black/5 transition-all duration-300"
                       placeholder="Enter new password">
                <button type="button"
                        @click="showPassword = !showPassword"
                        class="absolute inset-y-0 right-0 pr-6 flex items-center text-gray-400 hover:text-black transition-colors cursor-pointer outline-none">
                    <i class="far fa-eye text-lg" x-show="!showPassword"></i>
                    <i class="far fa-eye-slash text-lg" x-show="showPassword" x-cloak></i>
                </button>
            </div>
            @error('password')
                <p class="mt-2 text-sm text-red-600 pl-4 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                    <i class="fas fa-check-circle text-black text-lg group-focus-within:text-black transition-colors"></i>
                </div>
                <input id="password_confirmation"
                       name="password_confirmation"
                       :type="showConfirmPassword ? 'text' : 'password'"
                       required
                       class="block w-full pl-14 pr-14 py-4 border border-gray-200 rounded-full bg-white text-gray-900 placeholder-gray-400 font-medium focus:outline-none focus:border-black focus:ring-4 focus:ring-black/5 transition-all duration-300"
                       placeholder="Confirm new password">
                <button type="button"
                        @click="showConfirmPassword = !showConfirmPassword"
                        class="absolute inset-y-0 right-0 pr-6 flex items-center text-gray-400 hover:text-black transition-colors cursor-pointer outline-none">
                    <i class="far fa-eye text-lg" x-show="!showConfirmPassword"></i>
                    <i class="far fa-eye-slash text-lg" x-show="showConfirmPassword" x-cloak></i>
                </button>
            </div>
            @error('password_confirmation')
                <p class="mt-2 text-sm text-red-600 pl-4 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password Requirements -->
        <div class="p-4 bg-gray-50 border border-gray-200 rounded-2xl">
            <div class="flex items-start">
                <div class="w-8 h-8 bg-black/10 rounded-full flex items-center justify-center mr-3 shrink-0">
                    <i class="fas fa-shield-alt text-black text-sm"></i>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-gray-800 mb-1">Password Requirements</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check text-black text-xs"></i>
                            At least 8 characters long
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check text-black text-xs"></i>
                            Mix of uppercase and lowercase letters
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check text-black text-xs"></i>
                            Include numbers and special characters
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit"
                class="w-full py-4 bg-black hover:bg-gray-800 text-white font-bold text-[17px] rounded-full shadow-[0_10px_30px_-10px_rgba(0,0,0,0.5)] hover:shadow-[0_15px_35px_-10px_rgba(0,0,0,0.6)] transition-all duration-300 transform hover:-translate-y-0.5 active:translate-y-0">
            Reset Password
        </button>

        <!-- Back to Login -->
        <div class="mt-6">
            <a href="{{ route('login') }}"
               class="w-full flex justify-center items-center py-4 bg-white border border-gray-200 hover:bg-gray-50 hover:border-black text-black font-bold text-[17px] rounded-full transition-all duration-300">
                Back to Login
            </a>
        </div>
    </form>

</div>
@endsection
