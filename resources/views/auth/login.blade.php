@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="text-center mb-8">
    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2 transition-colors duration-300">Welcome Back!</h2>
    <p class="text-sm text-gray-600 dark:text-gray-400 transition-colors duration-300">
        Sign in to your account to continue
    </p>
</div>

<form method="POST" action="{{ route('login.attempt') }}" class="space-y-6">
    @csrf

    <!-- Email -->
    <div class="group">
        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 transition-colors duration-300">Email Address</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 group-focus-within:text-blue-500 dark:group-focus-within:text-blue-400 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                </svg>
            </div>
            <input id="email" 
                   name="email" 
                   type="email" 
                   autocomplete="email" 
                   required 
                   value="{{ old('email') }}"
                   class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition-all duration-200 text-sm hover:border-gray-400 dark:hover:border-gray-500"
                   placeholder="Enter your email">
        </div>
        @error('email')
            <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center animate-slide-up">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
                {{ $message }}
            </p>
        @enderror
    </div>

    <!-- Password -->
    <div x-data="{ showPassword: false }" class="group">
        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 transition-colors duration-300">Password</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 group-focus-within:text-blue-500 dark:group-focus-within:text-blue-400 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
            <input id="password" 
                   name="password" 
                   :type="showPassword ? 'text' : 'password'" 
                   autocomplete="current-password" 
                   required
                   class="block w-full pl-10 pr-10 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition-all duration-200 text-sm hover:border-gray-400 dark:hover:border-gray-500"
                   placeholder="Enter your password">
            <button type="button" 
                    @click="showPassword = !showPassword"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200">
                <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                </svg>
            </button>
        </div>
        @error('password')
            <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center animate-slide-up">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
                {{ $message }}
            </p>
        @enderror
    </div>

    <!-- Remember Me & Forgot Password -->
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <input id="remember" 
                   name="remember" 
                   type="checkbox" 
                   class="w-4 h-4 text-blue-600 dark:text-blue-500 bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded focus:ring-blue-500 dark:focus:ring-blue-400 dark:ring-offset-gray-800 focus:ring-2 transition-colors duration-200">
            <label for="remember" class="ml-2 text-sm text-gray-700 dark:text-gray-300 transition-colors duration-300">
                Remember me
            </label>
        </div>

        <div class="text-sm">
            <a href="{{ route('password.request') }}" class="font-medium text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300 transition-colors duration-200">
                Forgot password?
            </a>
        </div>
    </div>

    <!-- Submit Button -->
    <div class="space-y-4">
        <button type="submit" 
                class="w-full flex justify-center items-center gap-2 py-3 px-4 bg-gradient-to-r from-blue-600 to-purple-600 dark:from-blue-500 dark:to-purple-500 hover:from-blue-700 hover:to-purple-700 dark:hover:from-blue-600 dark:hover:to-purple-600 text-white font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-blue-400 dark:focus:ring-offset-gray-800 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-[1.02]">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
            </svg>
            Sign In
        </button>

        <!-- Register Link -->
        <div class="text-center">
            <p class="text-sm text-gray-600 dark:text-gray-400 transition-colors duration-300">
                Don't have an account? 
                <a href="{{ route('register') }}" class="font-medium text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300 transition-colors duration-200">
                    Create one here
                </a>
            </p>
        </div>
    </div>
</form>
@endsection