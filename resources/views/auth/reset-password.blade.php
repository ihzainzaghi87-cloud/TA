@extends('layouts.auth')
@section('title', 'Reset Password')

@section('content')
<div class="text-center mb-8">
    <div class="w-16 h-16 bg-[#FAD470]/20 rounded-full flex items-center justify-center mx-auto mb-4">
        <i class="fas fa-lock-open text-[#D4A84B] text-2xl"></i>
    </div>
    <h2 class="text-2xl font-bold text-gray-900 mb-2">Reset Password</h2>
    <p class="text-gray-500">
        Enter your new password to secure your account
    </p>
</div>

<form method="POST" action="{{ route('password.update') }}" class="space-y-5" x-data="{ showPassword: false, showConfirmPassword: false }">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">

    <!-- Email (Read-only) -->
    <div>
        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <i class="fas fa-envelope text-gray-400"></i>
            </div>
            <input id="email"
                   name="email"
                   type="email"
                   required
                   readonly
                   value="{{ old('email', $email) }}"
                   class="block w-full pl-11 pr-4 py-3 border-2 border-gray-200 rounded-xl bg-gray-100 text-gray-900 cursor-not-allowed">
        </div>
        @error('email')
            <p class="mt-2 text-sm text-red-600 flex items-center">
                <i class="fas fa-exclamation-circle mr-1"></i>
                {{ $message }}
            </p>
        @enderror
    </div>

    <!-- New Password -->
    <div>
        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">New Password</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <i class="fas fa-lock text-gray-400"></i>
            </div>
            <input id="password"
                   name="password"
                   :type="showPassword ? 'text' : 'password'"
                   required
                   class="block w-full pl-11 pr-12 py-3 border-2 border-gray-200 rounded-xl bg-gray-50 text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#FAD470] focus:bg-white transition-all duration-200"
                   placeholder="Enter new password">
            <button type="button"
                    @click="showPassword = !showPassword"
                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600">
                <i x-show="!showPassword" class="fas fa-eye"></i>
                <i x-show="showPassword" class="fas fa-eye-slash"></i>
            </button>
        </div>
        @error('password')
            <p class="mt-2 text-sm text-red-600 flex items-center">
                <i class="fas fa-exclamation-circle mr-1"></i>
                {{ $message }}
            </p>
        @enderror
    </div>

    <!-- Confirm Password -->
    <div>
        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Confirm New Password</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <i class="fas fa-shield-alt text-gray-400"></i>
            </div>
            <input id="password_confirmation"
                   name="password_confirmation"
                   :type="showConfirmPassword ? 'text' : 'password'"
                   required
                   class="block w-full pl-11 pr-12 py-3 border-2 border-gray-200 rounded-xl bg-gray-50 text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#FAD470] focus:bg-white transition-all duration-200"
                   placeholder="Confirm new password">
            <button type="button"
                    @click="showConfirmPassword = !showConfirmPassword"
                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600">
                <i x-show="!showConfirmPassword" class="fas fa-eye"></i>
                <i x-show="showConfirmPassword" class="fas fa-eye-slash"></i>
            </button>
        </div>
        @error('password_confirmation')
            <p class="mt-2 text-sm text-red-600 flex items-center">
                <i class="fas fa-exclamation-circle mr-1"></i>
                {{ $message }}
            </p>
        @enderror
    </div>

    <!-- Password Requirements -->
    <div class="p-4 bg-[#FAD470]/10 border border-[#FAD470]/30 rounded-2xl">
        <div class="flex items-start">
            <div class="w-8 h-8 bg-[#FAD470]/20 rounded-full flex items-center justify-center mr-3 shrink-0">
                <i class="fas fa-shield-alt text-[#D4A84B] text-sm"></i>
            </div>
            <div>
                <h4 class="text-sm font-semibold text-gray-800 mb-1">Password Requirements</h4>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li class="flex items-center gap-2">
                        <i class="fas fa-check text-[#D4A84B] text-xs"></i>
                        At least 8 characters long
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fas fa-check text-[#D4A84B] text-xs"></i>
                        Mix of uppercase and lowercase letters
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fas fa-check text-[#D4A84B] text-xs"></i>
                        Include numbers and special characters
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Submit Button -->
    <button type="submit"
            class="w-full flex justify-center items-center gap-2 py-3.5 px-4 bg-[#FAD470] hover:bg-[#E5C060] text-black font-semibold rounded-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#FAD470] transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-[1.02]">
        <i class="fas fa-key"></i>
        Reset Password
    </button>

    <!-- Back to Login -->
    <div class="text-center">
        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-black transition-colors">
            <i class="fas fa-arrow-left"></i>
            Back to Login
        </a>
    </div>
</form>
@endsection
