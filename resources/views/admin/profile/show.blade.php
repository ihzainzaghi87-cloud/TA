@extends('admin.layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="space-y-6">
    <!-- Header Section - Compact -->
    <div class="relative bg-gradient-to-r from-teal-600 to-cyan-600 dark:from-teal-800 dark:to-cyan-800 overflow-hidden shadow-lg rounded-xl">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="relative p-6 text-white">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-white bg-opacity-20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                        <span class="text-2xl font-bold text-white">{{ substr($user->name, 0, 1) }}</span>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold mb-1">My Profile</h1>
                        <p class="text-teal-100 text-sm">Manage profile information and account security</p>
                    </div>
                </div>
                <a href="{{ route('dashboard') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-white bg-opacity-20 backdrop-blur-sm border border-white border-opacity-30 rounded-lg text-white hover:bg-opacity-30 transition-all duration-200 text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Dashboard
                </a>
            </div>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <!-- Profile Data Card - Compact -->
        <section class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700">
            <div class="p-5">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-gradient-to-br from-teal-500 to-cyan-600 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Profile Data</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Update your personal information</p>
                    </div>
                </div>

                <form action="{{ route('admin.profile.update') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Full Name</label>
                        <input type="text" 
                               name="name" 
                               value="{{ old('name', $user->name) }}"
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-colors duration-200"
                               required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email Address</label>
                        <input type="email" 
                               name="email" 
                               value="{{ old('email', $user->email) }}"
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-colors duration-200"
                               required>
                    </div>

                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Username <span class="text-gray-400">(optional)</span></label>
                            <input type="text" 
                                   name="username" 
                                   value="{{ old('username', $user->username) }}"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-colors duration-200"
                                   maxlength="255">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Phone Number <span class="text-gray-400">(optional)</span></label>
                            <input type="tel" 
                                   name="phone_number" 
                                   value="{{ old('phone_number', $user->phone_number) }}"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-colors duration-200"
                                   maxlength="15"
                                   placeholder="+62 812 3456 7890">
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" 
                                class="w-full px-6 py-3 bg-gradient-to-r from-teal-600 to-cyan-600 hover:from-teal-700 hover:to-cyan-700 text-white font-medium rounded-lg transition-all duration-200 text-sm shadow-lg">
                            <span class="flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Save Profile
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </section>

        <!-- Change Password Card - Compact -->
        <section class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700" x-data="{ showCur: false, showNew: false, showConf: false }">
            <div class="p-5">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-pink-600 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m0 0a2 2 0 012 2m0 0a2 2 0 01-2 2m0 0a2 2 0 01-2 2M9 7a2 2 0 00-2 2m0 0a2 2 0 002 2m0 0a2 2 0 002 2M9 7a2 2 0 012-2m0 0a2 2 0 012 2m0 0a2 2 0 01-2 2M9 7a2 2 0 00-2 2m0 0a2 2 0 002 2m0 0a2 2 0 002 2"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Change Password</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Use a strong and unique password</p>
                    </div>
                </div>

                <form action="{{ route('admin.profile.password.update') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Current Password</label>
                        <div class="relative">
                            <input :type="showCur ? 'text' : 'password'" 
                                   name="current_password"
                                   class="w-full px-4 py-3 pr-12 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-colors duration-200"
                                   required>
                            <button type="button" 
                                    @click="showCur = !showCur"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200">
                                <svg x-show="!showCur" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <svg x-show="showCur" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">New Password</label>
                        <div class="relative">
                            <input :type="showNew ? 'text' : 'password'" 
                                   name="password"
                                   class="w-full px-4 py-3 pr-12 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-colors duration-200"
                                   required>
                            <button type="button" 
                                    @click="showNew = !showNew"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200">
                                <svg x-show="!showNew" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <svg x-show="showNew" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Confirm New Password</label>
                        <div class="relative">
                            <input :type="showConf ? 'text' : 'password'" 
                                   name="password_confirmation"
                                   class="w-full px-4 py-3 pr-12 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-colors duration-200"
                                   required>
                            <button type="button" 
                                    @click="showConf = !showConf"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200">
                                <svg x-show="!showConf" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <svg x-show="showConf" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Password Strength Indicator -->
                    <div class="p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                        <div class="flex items-start">
                            <svg class="w-4 h-4 text-blue-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-sm text-blue-800 dark:text-blue-200">
                                <strong>Security tips:</strong> Use a combination of uppercase letters, lowercase letters, numbers, and symbols. Minimum 8 characters.
                            </p>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" 
                                class="w-full px-6 py-3 bg-gradient-to-r from-red-600 to-pink-600 hover:from-red-700 hover:to-pink-700 text-white font-medium rounded-lg transition-all duration-200 text-sm shadow-lg">
                            <span class="flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m0 0a2 2 0 012 2m0 0a2 2 0 01-2 2m0 0a2 2 0 01-2 2M9 7a2 2 0 00-2 2m0 0a2 2 0 002 2m0 0a2 2 0 002 2M9 7a2 2 0 012-2m0 0a2 2 0 012 2m0 0a2 2 0 01-2 2M9 7a2 2 0 00-2 2m0 0a2 2 0 002 2m0 0a2 2 0 002 2"></path>
                                </svg>
                                Change Password
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </div>

    <!-- Additional Profile Information - Compact -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700 p-5">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <h3 class="font-semibold text-gray-900 dark:text-white">Account Information</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Account details and last activity</p>
            </div>
        </div>

        <div class="grid md:grid-cols-3 gap-4">
            <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">Created At</div>
                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->created_at->format('d M Y') }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $user->created_at->diffForHumans() }}</div>
            </div>
            <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">Last Updated</div>
                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->updated_at->format('d M Y') }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $user->updated_at->diffForHumans() }}</div>
            </div>
            <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">Account Role</div>
                <div class="flex flex-wrap gap-1 mt-1">
                    @forelse($user->getRoleNames() as $role)
                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-200">
                            {{ $role }}
                        </span>
                    @empty
                        <span class="text-xs text-gray-500 dark:text-gray-400">No roles assigned</span>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection