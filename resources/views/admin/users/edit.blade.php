@extends('admin.layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="space-y-6">
    <!-- Header Section - Compact -->
    <div class="relative bg-gradient-to-r from-orange-600 to-red-600 dark:from-orange-800 dark:to-red-800 overflow-hidden shadow-lg rounded-xl">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="relative p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-bold mb-1">Edit User</h1>
                    <p class="text-orange-100 text-sm">Update user data: {{ $user->name }}</p>
                </div>
                <a href="{{ route('admin.users.index') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-white bg-opacity-20 backdrop-blur-sm border border-white border-opacity-30 rounded-lg text-white hover:bg-opacity-30 transition-all duration-200 text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Users
                </a>
            </div>
        </div>
    </div>

    <!-- Error Messages - Compact -->
    @if ($errors->any())
    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 rounded-lg p-4">
        <div class="flex items-start">
            <svg class="w-4 h-4 text-red-400 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
            <div>
                <h4 class="text-sm font-medium text-red-800 dark:text-red-200 mb-1">There is an error:</h4>
                <ul class="text-sm text-red-700 dark:text-red-300 space-y-1">
                    @foreach ($errors->all() as $err)
                        <li>• {{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-2">
        <!-- Profile & Password Section -->
        <div class="space-y-6">
            <!-- Edit Profile - Compact -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700">
                <div class="p-5">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">Edit Profile</h3>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Update user information</p>
                        </div>
                    </div>

                    <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Full Name</label>
                            <input type="text" 
                                   name="name" 
                                   value="{{ old('name', $user->name) }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200" 
                                   required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email Address</label>
                            <input type="email" 
                                   name="email" 
                                   value="{{ old('email', $user->email) }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200" 
                                   required>
                        </div>

                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Username <span class="text-gray-400">(optional)</span></label>
                                <input type="text" 
                                       name="username" 
                                       value="{{ old('username', $user->username) }}"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone Number <span class="text-gray-400">(optional)</span></label>
                                <input type="tel" 
                                       name="phone_number" 
                                       value="{{ old('phone_number', $user->phone_number) }}"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200" 
                                       maxlength="15">
                            </div>
                        </div>

                        <div class="pt-2">
                            <button type="submit" 
                                    class="px-6 py-2 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-medium rounded-lg transition-all duration-200 text-sm">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Save Profil
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Change Password - Compact -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700" x-data="{ showNew: false, showConf: false }">
                <div class="p-5">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 bg-gradient-to-br from-red-500 to-pink-600 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m0 0a2 2 0 012 2m0 0a2 2 0 01-2 2m0 0a2 2 0 01-2 2M9 7a2 2 0 00-2 2m0 0a2 2 0 002 2m0 0a2 2 0 002 2M9 7a2 2 0 012-2m0 0a2 2 0 012 2m0 0a2 2 0 01-2 2M9 7a2 2 0 00-2 2m0 0a2 2 0 002 2m0 0a2 2 0 002 2"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">Change Password</h3>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Update user password</p>
                        </div>
                    </div>

                    <form action="{{ route('admin.users.password.update', $user) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">New Password</label>
                                <div class="relative">
                                    <input :type="showNew ? 'text' : 'password'" 
                                           name="password"
                                           class="w-full px-3 py-2 pr-10 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-colors duration-200" 
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
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Confirm Password</label>
                                <div class="relative">
                                    <input :type="showConf ? 'text' : 'password'" 
                                           name="password_confirmation"
                                           class="w-full px-3 py-2 pr-10 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-colors duration-200" 
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
                        </div>

                        <div class="pt-2">
                            <button type="submit" 
                                    class="px-6 py-2 bg-gradient-to-r from-red-600 to-pink-600 hover:from-red-700 hover:to-pink-700 text-white font-medium rounded-lg transition-all duration-200 text-sm">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m0 0a2 2 0 012 2m0 0a2 2 0 01-2 2m0 0a2 2 0 01-2 2M9 7a2 2 0 00-2 2m0 0a2 2 0 002 2m0 0a2 2 0 002 2M9 7a2 2 0 012-2m0 0a2 2 0 012 2m0 0a2 2 0 01-2 2M9 7a2 2 0 00-2 2m0 0a2 2 0 002 2m0 0a2 2 0 002 2"></path>
                                    </svg>
                                    Update Password
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Roles & Permissions Section - Compact -->
        <div class="space-y-6" x-data="{ checkAllRoles: false, checkAllPerm: false }">
            <!-- Roles -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700">
                <div class="p-5">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 dark:text-white">Roles</h3>
                                <p class="text-xs text-gray-600 dark:text-gray-400">Manage user roles</p>
                            </div>
                        </div>
                        <label class="flex items-center gap-2 text-xs text-gray-600 dark:text-gray-300 cursor-pointer">
                            <input type="checkbox" 
                                   x-model="checkAllRoles"
                                   @change="document.querySelectorAll('input[name=\'roles[]\']').forEach(cb => cb.checked = checkAllRoles)"
                                   class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500 dark:bg-gray-700 dark:border-gray-600">
                            Select All
                        </label>
                    </div>

                    <form action="{{ route('admin.users.sync-roles', $user) }}" method="POST">
                        @csrf
                        <div class="max-h-56 overflow-y-auto border border-gray-200 dark:border-gray-700 rounded-lg p-3 space-y-2 mb-4">
                            @foreach ($roles as $r)
                            <label class="flex items-center p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200 cursor-pointer">
                                <input type="checkbox" 
                                       name="roles[]" 
                                       value="{{ $r->name }}" 
                                       class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500 dark:bg-gray-700 dark:border-gray-600"
                                       @checked(in_array($r->name, $userRoles))>
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-200">{{ $r->name }}</span>
                            </label>
                            @endforeach
                        </div>

                        <button type="submit" 
                                class="px-6 py-2 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-medium rounded-lg transition-all duration-200 text-sm">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Save Roles
                            </span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Direct Permissions -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700">
                <div class="p-5">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 dark:text-white">Direct Permissions</h3>
                                <p class="text-xs text-gray-600 dark:text-gray-400">Specific permissions for this user</p>
                            </div>
                        </div>
                        <label class="flex items-center gap-2 text-xs text-gray-600 dark:text-gray-300 cursor-pointer">
                            <input type="checkbox" 
                                   x-model="checkAllPerm"
                                   @change="document.querySelectorAll('input[name=\'permissions[]\']').forEach(cb => cb.checked = checkAllPerm)"
                                   class="w-4 h-4 text-yellow-600 border-gray-300 rounded focus:ring-yellow-500 dark:bg-gray-700 dark:border-gray-600">
                            Select All
                        </label>
                    </div>

                    <div class="mb-4 p-3 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg">
                        <p class="text-xs text-amber-800 dark:text-amber-200">
                            <strong>Info:</strong> Direct permission is granted directly to the user (not through a role). Suitable for exceptions/temporary access.
                        </p>
                    </div>

                    <form action="{{ route('admin.users.sync-permissions', $user) }}" method="POST">
                        @csrf
                        <div class="max-h-56 overflow-y-auto border border-gray-200 dark:border-gray-700 rounded-lg p-3 space-y-2 mb-4">
                            @foreach ($permissions as $p)
                            <label class="flex items-center p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200 cursor-pointer">
                                <input type="checkbox" 
                                       name="permissions[]" 
                                       value="{{ $p->name }}" 
                                       class="w-4 h-4 text-yellow-600 border-gray-300 rounded focus:ring-yellow-500 dark:bg-gray-700 dark:border-gray-600"
                                       @checked(in_array($p->name, $userPerms))>
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-200">{{ $p->name }}</span>
                            </label>
                            @endforeach
                        </div>

                        <button type="submit" 
                                class="px-6 py-2 bg-gradient-to-r from-yellow-600 to-orange-600 hover:from-yellow-700 hover:to-orange-700 text-white font-medium rounded-lg transition-all duration-200 text-sm">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Save Direct Permissions
                            </span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection