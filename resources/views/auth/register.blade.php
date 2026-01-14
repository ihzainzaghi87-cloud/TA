@extends('layouts.auth')

@section('title', 'Register')

@section('content')
<div class="text-center mb-6">
    <h2 class="text-2xl font-bold text-gray-900 mb-2">Create Account</h2>
    <p class="text-gray-500">
        Join us and start shopping
    </p>
</div>

<form method="POST" action="{{ route('register.attempt') }}" class="space-y-4">
    @csrf

    <!-- Name -->
    <div>
        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Full Name <span class="text-red-500">*</span></label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <i class="fas fa-user text-gray-400"></i>
            </div>
            <input id="name" 
                   name="name" 
                   type="text" 
                   required 
                   value="{{ old('name') }}"
                   class="block w-full pl-11 pr-4 py-3 border-2 border-gray-200 rounded-xl bg-gray-50 text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#FAD470] focus:bg-white transition-all duration-200"
                   placeholder="Enter your full name">
        </div>
        @error('name')
            <p class="mt-2 text-sm text-red-600 flex items-center">
                <i class="fas fa-exclamation-circle mr-1"></i>
                {{ $message }}
            </p>
        @enderror
    </div>

    <!-- Email -->
    <div>
        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address <span class="text-red-500">*</span></label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <i class="fas fa-envelope text-gray-400"></i>
            </div>
            <input id="email" 
                   name="email" 
                   type="email" 
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

    <!-- Username & Phone -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label for="username" class="block text-sm font-semibold text-gray-700 mb-2">Username <span class="text-gray-400 font-normal">(optional)</span></label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-at text-gray-400"></i>
                </div>
                <input id="username" 
                       name="username" 
                       type="text" 
                       value="{{ old('username') }}"
                       class="block w-full pl-11 pr-4 py-3 border-2 border-gray-200 rounded-xl bg-gray-50 text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#FAD470] focus:bg-white transition-all duration-200"
                       placeholder="Username">
            </div>
        </div>

        <div>
            <label for="phone_number" class="block text-sm font-semibold text-gray-700 mb-2">Phone <span class="text-red-500">*</span></label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-phone text-gray-400"></i>
                </div>
                <input id="phone_number" 
                       name="phone_number" 
                       type="tel" 
                       required
                       value="{{ old('phone_number') }}"
                       class="block w-full pl-11 pr-4 py-3 border-2 border-gray-200 rounded-xl bg-gray-50 text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#FAD470] focus:bg-white transition-all duration-200"
                       placeholder="+62 812 3456 7890">
            </div>
        </div>
    </div>

    <!-- Address -->
    <div>
        <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">Address <span class="text-red-500">*</span></label>
        <div class="relative">
            <div class="absolute top-3.5 left-4 flex items-start pointer-events-none">
                <i class="fas fa-map-marker-alt text-gray-400"></i>
            </div>
            <textarea id="address" 
                    name="address" 
                    required 
                    rows="2"
                    class="block w-full pl-11 pr-4 py-3 border-2 border-gray-200 rounded-xl bg-gray-50 text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#FAD470] focus:bg-white transition-all duration-200 resize-none"
                    placeholder="Enter your address">{{ old('address') }}</textarea>
        </div>
        @error('address')
            <p class="mt-2 text-sm text-red-600 flex items-center">
                <i class="fas fa-exclamation-circle mr-1"></i>
                {{ $message }}
            </p>
        @enderror
    </div>

    <!-- Password Fields -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4" x-data="{ showPassword: false, showConfirmPassword: false }">
        <div>
            <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password <span class="text-red-500">*</span></label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-lock text-gray-400"></i>
                </div>
                <input id="password" 
                       name="password" 
                       :type="showPassword ? 'text' : 'password'" 
                       required
                       class="block w-full pl-11 pr-12 py-3 border-2 border-gray-200 rounded-xl bg-gray-50 text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#FAD470] focus:bg-white transition-all duration-200"
                       placeholder="Password">
                <button type="button" 
                        @click="showPassword = !showPassword"
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                    <i x-show="!showPassword" class="fas fa-eye"></i>
                    <i x-show="showPassword" class="fas fa-eye-slash" x-cloak></i>
                </button>
            </div>
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password <span class="text-red-500">*</span></label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-check-circle text-gray-400"></i>
                </div>
                <input id="password_confirmation" 
                       name="password_confirmation" 
                       :type="showConfirmPassword ? 'text' : 'password'" 
                       required
                       class="block w-full pl-11 pr-12 py-3 border-2 border-gray-200 rounded-xl bg-gray-50 text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#FAD470] focus:bg-white transition-all duration-200"
                       placeholder="Confirm password">
                <button type="button" 
                        @click="showConfirmPassword = !showConfirmPassword"
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                    <i x-show="!showConfirmPassword" class="fas fa-eye"></i>
                    <i x-show="showConfirmPassword" class="fas fa-eye-slash" x-cloak></i>
                </button>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="p-4 bg-red-50 border border-red-200 rounded-xl">
            <div class="flex items-start">
                <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                    <i class="fas fa-exclamation text-red-600"></i>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-red-800 mb-1">Please fix the following errors:</h4>
                    <ul class="text-sm text-red-700 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Submit Button -->
    <button type="submit" 
            class="w-full flex justify-center items-center gap-2 py-3.5 px-4 bg-[#FAD470] hover:bg-[#E5C060] text-black font-semibold rounded-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#FAD470] transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-[1.02]">
        <i class="fas fa-user-plus"></i>
        Create Account
    </button>

    <!-- Divider -->
    <div class="relative my-4">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-200"></div>
        </div>
        <div class="relative flex justify-center text-sm">
            <span class="px-4 bg-white text-gray-500">or</span>
        </div>
    </div>

    <!-- Login Link -->
    <div class="text-center">
        <p class="text-gray-600">
            Already have an account? 
            <a href="{{ route('login') }}" class="font-semibold text-black hover:text-[#D4A84B] transition-colors">
                Sign in here
            </a>
        </p>
    </div>
</form>
@endsection