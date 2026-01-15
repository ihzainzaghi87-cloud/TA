@extends('layouts.auth')

@section('title', 'Login')

@section('content')

<div class="bg-white p-10 sm:p-14 rounded-[35px] shadow-[0_30px_60px_-15px_rgba(0,0,0,0.1)] border border-gray-100 w-full max-w-[580px] mx-auto relative">

    @if (session('status') || session('success'))
        <div class="mb-6 p-4 rounded-2xl bg-green-50 text-green-700 text-sm font-medium flex items-center gap-3">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('status') ?? session('success') }}</span>
        </div>
    @endif
    @if (session('error'))
        <div class="mb-6 p-4 rounded-2xl bg-red-50 text-red-600 text-sm font-medium flex items-center gap-3">
            <i class="fas fa-exclamation-circle"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <div class="flex flex-col items-center justify-center mb-12">
        <div class="flex items-center gap-4">
            <img src="{{ asset('ui/logo.png') }}" alt="Logo" class="w-12 h-12 object-contain">
            <h1 class="text-[28px] font-extrabold text-black tracking-tight font-['Poppins']">The Paranoia</h1>
        </div>
    </div>

    <div class="mb-8">
        <h2 class="text-[30px] font-bold text-black font-['Poppins']">Sign In</h2>
    </div>

    <form method="POST" action="{{ route('login.attempt') }}" class="space-y-6">
        @csrf

        <div>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                    <i class="far fa-envelope text-black text-lg group-focus-within:text-[#FCD364] transition-colors"></i>
                </div>
                <input id="email"
                       name="email"
                       type="email"
                       autocomplete="email"
                       required
                       value="{{ old('email') }}"
                       class="block w-full pl-14 pr-6 py-4 border border-gray-200 rounded-full bg-white text-gray-900 placeholder-gray-400 font-medium focus:outline-none focus:border-[#FCD364] focus:ring-4 focus:ring-[#FCD364]/10 transition-all duration-300"
                       placeholder="example@gmail.com">
            </div>
            @error('email')
                <p class="mt-2 text-sm text-red-600 pl-4 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <div x-data="{ showPassword: false }">
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                    <i class="fas fa-lock text-black text-lg group-focus-within:text-[#FCD364] transition-colors"></i>
                </div>
                <input id="password"
                       name="password"
                       :type="showPassword ? 'text' : 'password'"
                       autocomplete="current-password"
                       required
                       class="block w-full pl-14 pr-14 py-4 border border-gray-200 rounded-full bg-white text-gray-900 placeholder-gray-400 font-medium focus:outline-none focus:border-[#FCD364] focus:ring-4 focus:ring-[#FCD364]/10 transition-all duration-300"
                       placeholder="12345678">

                <button type="button"
                        @click="showPassword = !showPassword"
                        class="absolute inset-y-0 right-0 pr-6 flex items-center text-black hover:text-gray-600 transition-colors cursor-pointer outline-none">
                    <i class="far fa-eye text-lg" x-show="!showPassword"></i>
                    <i class="far fa-eye-slash text-lg" x-show="showPassword" x-cloak></i>
                </button>
            </div>
            @error('password')
                <p class="mt-2 text-sm text-red-600 pl-4 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end pt-1">
            <a href="{{ route('password.request') }}" class="text-[15px] font-medium text-gray-500 underline decoration-gray-300 hover:text-black hover:decoration-black transition-all">
                Forgot Password
            </a>
        </div>

        <button type="submit"
                class="w-full py-4 bg-[#FCD364] hover:bg-[#E5C060] text-white font-bold text-[17px] rounded-full shadow-[0_10px_30px_-10px_rgba(252,211,100,0.6)] hover:shadow-[0_15px_35px_-10px_rgba(252,211,100,0.7)] transition-all duration-300 transform hover:-translate-y-0.5 active:translate-y-0">
            Sign In to My Account
        </button>
    </form>

    <div class="mt-6">
        <a href="{{ route('register') }}"
           class="w-full flex justify-center items-center py-4 bg-white border border-gray-200 hover:bg-gray-50 hover:border-gray-300 text-black font-bold text-[17px] rounded-full transition-all duration-300">
            Sign Up
        </a>
    </div>

</div>
@endsection
