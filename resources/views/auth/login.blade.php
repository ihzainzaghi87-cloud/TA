@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="text-center mb-8">
    <h2 class="text-2xl font-bold text-gray-900 mb-2">Welcome Back!</h2>
    <p class="text-gray-500">
        Sign in to your account to continue
    </p>
</div>

<form method="POST" action="{{ route('login.attempt') }}" class="space-y-5">
    @csrf

    <!-- Email -->
    <div>
        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <i class="fas fa-envelope text-gray-400"></i>
            </div>
            <input id="email" 
                   name="email" 
                   type="email" 
                   autocomplete="email" 
                   required 
                   value="{{ old('email') }}"
                   class="block w-full pl-11 pr-4 py-3 border-2 border-gray-200 rounded-xl bg-gray-50 text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#FAD470] focus:bg-white transition-all duration-200"
                   placeholder="Enter your email">
        </div>
        @error('email')
            <p class="mt-2 text-sm text-red-600 flex items-center">
                <i class="fas fa-exclamation-circle mr-1"></i>
                {{ $message }}
            </p>
        @enderror
    </div>

    <!-- Password -->
    <div x-data="{ showPassword: false }">
        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <i class="fas fa-lock text-gray-400"></i>
            </div>
            <input id="password" 
                   name="password" 
                   :type="showPassword ? 'text' : 'password'" 
                   autocomplete="current-password" 
                   required
                   class="block w-full pl-11 pr-12 py-3 border-2 border-gray-200 rounded-xl bg-gray-50 text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#FAD470] focus:bg-white transition-all duration-200"
                   placeholder="Enter your password">
            <button type="button" 
                    @click="showPassword = !showPassword"
                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                <i x-show="!showPassword" class="fas fa-eye"></i>
                <i x-show="showPassword" class="fas fa-eye-slash" x-cloak></i>
            </button>
        </div>
        @error('password')
            <p class="mt-2 text-sm text-red-600 flex items-center">
                <i class="fas fa-exclamation-circle mr-1"></i>
                {{ $message }}
            </p>
        @enderror
    </div>

    <!-- Remember Me & Forgot Password -->
    <div class="flex items-center justify-between">
        <label class="flex items-center cursor-pointer">
            <input id="remember" 
                   name="remember" 
                   type="checkbox" 
                   class="w-4 h-4 text-[#FAD470] bg-gray-100 border-gray-300 rounded focus:ring-[#FAD470] focus:ring-2">
            <span class="ml-2 text-sm text-gray-600">Remember me</span>
        </label>

        <a href="{{ route('password.request') }}" class="text-sm font-medium text-gray-700 hover:text-[#D4A84B] transition-colors">
            Forgot password?
        </a>
    </div>

    <!-- Submit Button -->
    <button type="submit" 
            class="w-full flex justify-center items-center gap-2 py-3.5 px-4 bg-[#FAD470] hover:bg-[#E5C060] text-black font-semibold rounded-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#FAD470] transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-[1.02]">
        <i class="fas fa-sign-in-alt"></i>
        Sign In
    </button>

    <!-- Divider -->
    <div class="relative my-6">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-200"></div>
        </div>
        <div class="relative flex justify-center text-sm">
            <span class="px-4 bg-white text-gray-500">or</span>
        </div>
    </div>

    <!-- Register Link -->
    <div class="text-center">
        <p class="text-gray-600">
            Don't have an account? 
            <a href="{{ route('register') }}" class="font-semibold text-black hover:text-[#D4A84B] transition-colors">
                Create one here
            </a>
        </p>
    </div>
</form>
@endsection