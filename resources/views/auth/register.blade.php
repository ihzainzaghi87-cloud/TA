@extends('layouts.auth')

@section('title', 'Register')

@section('content')

<div class="bg-white p-8 sm:p-14 rounded-[35px] shadow-[0_30px_60px_-15px_rgba(0,0,0,0.1)] border border-gray-100 w-full max-w-[650px] mx-auto relative"> <div class="flex flex-col items-center justify-center mb-8">
        <div class="flex items-center gap-4">
            <img src="{{ asset('ui/logo.png') }}" alt="Logo" class="w-12 h-12 object-contain">
            <h1 class="text-[28px] font-extrabold text-black tracking-tight font-['Poppins']">The Paranoia</h1>
        </div>
    </div>

    <div class="mb-8">
        <h2 class="text-[30px] font-bold text-black font-['Poppins']">Sign Up</h2>
    </div>

    <form method="POST" action="{{ route('register.attempt') }}" class="space-y-5">
        @csrf

        <div>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                    <i class="fas fa-user text-black text-lg group-focus-within:text-[#FCD364] transition-colors"></i>
                </div>
                <input id="name"
                       name="name"
                       type="text"
                       required
                       value="{{ old('name') }}"
                       class="block w-full pl-14 pr-6 py-4 border border-gray-200 rounded-full bg-white text-gray-900 placeholder-gray-400 font-medium focus:outline-none focus:border-[#FCD364] focus:ring-4 focus:ring-[#FCD364]/10 transition-all duration-300"
                       placeholder="Full Name">
            </div>
            @error('name')
                <p class="mt-2 text-sm text-red-600 pl-4 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                    <i class="fas fa-envelope text-black text-lg group-focus-within:text-[#FCD364] transition-colors"></i>
                </div>
                <input id="email"
                       name="email"
                       type="email"
                       required
                       value="{{ old('email') }}"
                       class="block w-full pl-14 pr-6 py-4 border border-gray-200 rounded-full bg-white text-gray-900 placeholder-gray-400 font-medium focus:outline-none focus:border-[#FCD364] focus:ring-4 focus:ring-[#FCD364]/10 transition-all duration-300"
                       placeholder="Email Address">
            </div>
            @error('email')
                <p class="mt-2 text-sm text-red-600 pl-4 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                        <i class="fas fa-at text-black text-lg group-focus-within:text-[#FCD364] transition-colors"></i>
                    </div>
                    <input id="username"
                           name="username"
                           type="text"
                           value="{{ old('username') }}"
                           class="block w-full pl-14 pr-6 py-4 border border-gray-200 rounded-full bg-white text-gray-900 placeholder-gray-400 font-medium focus:outline-none focus:border-[#FCD364] focus:ring-4 focus:ring-[#FCD364]/10 transition-all duration-300"
                           placeholder="Username (Opt)">
                </div>
            </div>

            <div>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                        <i class="fas fa-phone text-black text-lg group-focus-within:text-[#FCD364] transition-colors"></i>
                    </div>
                    <input id="phone_number"
                           name="phone_number"
                           type="tel"
                           required
                           value="{{ old('phone_number') }}"
                           class="block w-full pl-14 pr-6 py-4 border border-gray-200 rounded-full bg-white text-gray-900 placeholder-gray-400 font-medium focus:outline-none focus:border-[#FCD364] focus:ring-4 focus:ring-[#FCD364]/10 transition-all duration-300"
                           placeholder="Phone Number">
                </div>
            </div>
        </div>

        <div>
            <div class="relative group">
                <div class="absolute top-4 left-6 flex items-start pointer-events-none">
                    <i class="fas fa-map-marker-alt text-black text-lg group-focus-within:text-[#FCD364] transition-colors"></i>
                </div>
                <textarea id="address"
                        name="address"
                        required
                        rows="2"
                        class="block w-full pl-14 pr-6 py-4 border border-gray-200 rounded-[25px] bg-white text-gray-900 placeholder-gray-400 font-medium focus:outline-none focus:border-[#FCD364] focus:ring-4 focus:ring-[#FCD364]/10 transition-all duration-300 resize-none"
                        placeholder="Full Address">{{ old('address') }}</textarea>
            </div>
            @error('address')
                <p class="mt-2 text-sm text-red-600 pl-4 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5" x-data="{ showPassword: false, showConfirmPassword: false }">
            <div>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-black text-lg group-focus-within:text-[#FCD364] transition-colors"></i>
                    </div>
                    <input id="password"
                           name="password"
                           :type="showPassword ? 'text' : 'password'"
                           required
                           class="block w-full pl-14 pr-12 py-4 border border-gray-200 rounded-full bg-white text-gray-900 placeholder-gray-400 font-medium focus:outline-none focus:border-[#FCD364] focus:ring-4 focus:ring-[#FCD364]/10 transition-all duration-300"
                           placeholder="Password">
                    <button type="button"
                            @click="showPassword = !showPassword"
                            class="absolute inset-y-0 right-0 pr-5 flex items-center text-black hover:text-gray-600 transition-colors cursor-pointer outline-none">
                        <i class="far fa-eye" x-show="!showPassword"></i>
                        <i class="far fa-eye-slash" x-show="showPassword" x-cloak></i>
                    </button>
                </div>
            </div>

            <div>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                        <i class="fas fa-check-circle text-black text-lg group-focus-within:text-[#FCD364] transition-colors"></i>
                    </div>
                    <input id="password_confirmation"
                           name="password_confirmation"
                           :type="showConfirmPassword ? 'text' : 'password'"
                           required
                           class="block w-full pl-14 pr-12 py-4 border border-gray-200 rounded-full bg-white text-gray-900 placeholder-gray-400 font-medium focus:outline-none focus:border-[#FCD364] focus:ring-4 focus:ring-[#FCD364]/10 transition-all duration-300"
                           placeholder="Confirm Pass">
                    <button type="button"
                            @click="showConfirmPassword = !showConfirmPassword"
                            class="absolute inset-y-0 right-0 pr-5 flex items-center text-black hover:text-gray-600 transition-colors cursor-pointer outline-none">
                        <i class="far fa-eye" x-show="!showConfirmPassword"></i>
                        <i class="far fa-eye-slash" x-show="showConfirmPassword" x-cloak></i>
                    </button>
                </div>
            </div>
        </div>

        @if ($errors->any())
             <div class="p-4 bg-red-50 border border-red-100 rounded-2xl animate-fade-in-down">
                <div class="flex items-start gap-3">
                    <i class="fas fa-exclamation-triangle text-red-600 mt-1"></i>
                    <div>
                        <h4 class="text-sm font-bold text-red-800 mb-1">Please fix errors:</h4>
                        <ul class="text-sm text-red-700 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <button type="submit"
                class="w-full py-4 bg-[#FCD364] hover:bg-[#E5C060] text-white font-bold text-[17px] rounded-full shadow-[0_10px_30px_-10px_rgba(252,211,100,0.6)] hover:shadow-[0_15px_35px_-10px_rgba(252,211,100,0.7)] transition-all duration-300 transform hover:-translate-y-0.5 active:translate-y-0 mt-4">
            Sign Up to My Account
        </button>
    </form>

    <div class="mt-6">
        <a href="{{ route('login') }}"
           class="w-full flex justify-center items-center py-4 bg-white border border-gray-200 hover:bg-gray-50 hover:border-gray-300 text-black font-bold text-[17px] rounded-full transition-all duration-300">
            Sign In
        </a>
    </div>

</div>
@endsection
