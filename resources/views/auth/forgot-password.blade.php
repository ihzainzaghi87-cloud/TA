@extends('layouts.auth')
@section('title', 'Forgot Password')

@section('content')
<div class="text-center mb-8">
    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Forgot Password?</h2>
    <p class="text-sm text-gray-600 dark:text-gray-400">
        No worries! Enter your email and we'll send you a reset link
    </p>
</div>

@if (session('status'))
    <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-lg">
        <div class="flex items-start">
            <svg class="w-5 h-5 text-green-400 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-sm text-green-800 dark:text-green-200">{{ session('status') }}</p>
        </div>
    </div>
@endif

<form method="POST" action="{{ route('password.email') }}" class="space-y-6">
    @csrf

    <!-- Email -->
    <div>
        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email Address</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                </svg>
            </div>
            <input id="email" 
                   name="email" 
                   type="email" 
                   required 
                   autofocus
                   value="{{ old('email') }}"
                   class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200 text-sm"
                   placeholder="Enter your email address">
        </div>
        @error('email')
            <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
                {{ $message }}
            </p>
        @enderror
    </div>

    <!-- Submit Button -->
    <div class="space-y-4">
        <button type="submit" 
                class="w-full flex justify-center items-center gap-2 py-3 px-4 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 dark:focus:ring-offset-gray-800 transition-all duration-200 shadow-lg">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
            Send Reset Link
        </button>

        <!-- Back to Login -->
        <div class="text-center">
            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Login
            </a>
        </div>
    </div>
</form>

<!-- Info Section -->
<div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
    <div class="flex items-start">
        <svg class="w-5 h-5 text-blue-400 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <div>
            <h4 class="text-sm font-medium text-blue-800 dark:text-blue-200 mb-1">How it works</h4>
            <p class="text-sm text-blue-700 dark:text-blue-300">
                We'll send a secure link to your email. Click the link to create a new password for your account.
            </p>
        </div>
    </div>
</div>
@endsection