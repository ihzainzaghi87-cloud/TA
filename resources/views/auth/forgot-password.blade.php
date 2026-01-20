@extends('layouts.auth')

@section('title', 'Forgot Password')

@section('content')

<div class="bg-white p-10 sm:p-14 rounded-[35px] shadow-[0_30px_60px_-15px_rgba(0,0,0,0.1)] border border-gray-100 w-full max-w-[580px] mx-auto relative">

    <div class="flex flex-col items-center justify-center mb-10">
        <div class="flex items-center gap-4">
            <!-- <img src="{{ asset('ui/logo.png') }}" alt="Logo" class="w-12 h-12 object-contain"> -->
            <h1 class="text-[28px] font-extrabold text-black tracking-tight font-['Poppins']">The Paranoia</h1>
        </div>
    </div>

    <div class="mb-8 text-center sm:text-left">
        <h2 class="text-[30px] font-bold text-black font-['Poppins']">Forgot Password?</h2>
        <p class="text-gray-500 mt-2 text-sm leading-relaxed">
            No worries! Enter your email and we'll send you a reset link.
        </p>
    </div>

    @if (session('status'))
        <div class="mb-6 p-4 rounded-[20px] bg-green-50 border border-green-100 text-green-800 text-sm font-medium flex items-start gap-3 animate-fade-in-down">
            <div class="mt-0.5 bg-green-200 rounded-full p-1">
                <i class="fas fa-check text-green-700 text-xs"></i>
            </div>
            <span class="pt-0.5">{{ session('status') }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf

        <div>
            <label for="email" class="block text-sm font-bold text-gray-700 mb-2 ml-4">Email Address</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                    <i class="fas fa-envelope text-gray-400 text-lg group-focus-within:text-black transition-colors"></i>
                </div>
                <input id="email"
                       name="email"
                       type="email"
                       required
                       autofocus
                       value="{{ old('email') }}"
                       class="block w-full pl-14 pr-6 py-4 border border-gray-200 rounded-full bg-white text-gray-900 placeholder-gray-400 font-medium focus:outline-none focus:border-black focus:ring-4 focus:ring-black/5 transition-all duration-300"
                       placeholder="Enter your email address">
            </div>
            @error('email')
                <p class="mt-2 text-sm text-red-600 pl-4 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
                class="w-full py-4 bg-black hover:bg-gray-800 text-white font-bold text-[17px] rounded-full shadow-[0_10px_30px_-10px_rgba(0,0,0,0.5)] hover:shadow-[0_15px_35px_-10px_rgba(0,0,0,0.6)] transition-all duration-300 transform hover:-translate-y-0.5 active:translate-y-0 flex justify-center items-center gap-2">
            <i class="fas fa-paper-plane text-sm"></i>
            Send Reset Link
        </button>

        <div class="text-center mt-2">
            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-500 hover:text-black transition-colors py-2 px-4 rounded-full hover:bg-gray-50">
                <i class="fas fa-arrow-left text-xs"></i>
                Back to Login
            </a>
        </div>
    </form>

    <div class="mt-8 p-5 bg-gray-50 border border-gray-100 rounded-[25px]">
        <div class="flex items-start gap-3">
            <div class="w-8 h-8 bg-black rounded-full flex items-center justify-center shrink-0">
                <i class="fas fa-info text-white text-sm"></i>
            </div>
            <div>
                <h4 class="text-sm font-bold text-black mb-1">How it works</h4>
                <p class="text-xs text-gray-500 leading-relaxed font-medium">
                    We'll send a secure link to your email. Click the link inside to create a new password for your account instantly.
                </p>
            </div>
        </div>
    </div>

</div>
@endsection
