@extends('layouts.auth')
@section('title', 'Forgot Password')

@section('content')
<div class="text-center mb-8">
    <div class="w-16 h-16 bg-[#FAD470]/20 rounded-full flex items-center justify-center mx-auto mb-4">
        <i class="fas fa-key text-[#D4A84B] text-2xl"></i>
    </div>
    <h2 class="text-2xl font-bold text-gray-900 mb-2">Forgot Password?</h2>
    <p class="text-gray-500">
        No worries! Enter your email and we'll send you a reset link
    </p>
</div>

@if (session('status'))
    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-2xl">
        <div class="flex items-start">
            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                <i class="fas fa-check text-green-600"></i>
            </div>
            <p class="text-sm text-green-800 pt-1">{{ session('status') }}</p>
        </div>
    </div>
@endif

<form method="POST" action="{{ route('password.email') }}" class="space-y-6">
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
                   required 
                   autofocus
                   value="{{ old('email') }}"
                   class="block w-full pl-11 pr-4 py-3 border-2 border-gray-200 rounded-xl bg-gray-50 text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#FAD470] focus:bg-white transition-all duration-200"
                   placeholder="Enter your email address">
        </div>
        @error('email')
            <p class="mt-2 text-sm text-red-600 flex items-center">
                <i class="fas fa-exclamation-circle mr-1"></i>
                {{ $message }}
            </p>
        @enderror
    </div>

    <!-- Submit Button -->
    <button type="submit" 
            class="w-full flex justify-center items-center gap-2 py-3.5 px-4 bg-[#FAD470] hover:bg-[#E5C060] text-black font-semibold rounded-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#FAD470] transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-[1.02]">
        <i class="fas fa-paper-plane"></i>
        Send Reset Link
    </button>

    <!-- Back to Login -->
    <div class="text-center">
        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-black transition-colors">
            <i class="fas fa-arrow-left"></i>
            Back to Login
        </a>
    </div>
</form>

<!-- Info Section -->
<div class="mt-6 p-4 bg-[#FAD470]/10 border border-[#FAD470]/30 rounded-2xl">
    <div class="flex items-start">
        <div class="w-8 h-8 bg-[#FAD470]/20 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
            <i class="fas fa-info text-[#D4A84B]"></i>
        </div>
        <div>
            <h4 class="text-sm font-semibold text-gray-800 mb-1">How it works</h4>
            <p class="text-sm text-gray-600">
                We'll send a secure link to your email. Click the link to create a new password for your account.
            </p>
        </div>
    </div>
</div>
@endsection