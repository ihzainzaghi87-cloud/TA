@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Section - Lebih Kecil -->
    <div class="relative bg-gradient-to-r from-blue-600 to-purple-600 dark:from-blue-800 dark:to-purple-800 overflow-hidden shadow-lg rounded-xl">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="relative p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-bold mb-1">Welcome back, {{ auth()->user()->name }}!</h1>
                    <p class="text-blue-100 text-sm">Here's what's happening with your admin panel today.</p>
                </div>
                <div class="hidden md:block">
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards - Lebih Kompak -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Users -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-shadow duration-200">
            <div class="p-4">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Total Users</p>
                        <p class="text-xl font-bold text-gray-900 dark:text-white">{{ \App\Models\User::count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Roles -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-shadow duration-200">
            <div class="p-4">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Total Roles</p>
                        <p class="text-xl font-bold text-gray-900 dark:text-white">{{ \Spatie\Permission\Models\Role::count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Permissions -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-shadow duration-200">
            <div class="p-4">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Permissions</p>
                        <p class="text-xl font-bold text-gray-900 dark:text-white">{{ \Spatie\Permission\Models\Permission::count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Sessions -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-shadow duration-200">
            <div class="p-4">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Active Sessions</p>
                        <p class="text-xl font-bold text-gray-900 dark:text-white">1</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions - Lebih Minimalis -->
     @canany(['users.index', 'roles.index', 'permissions.index', 'roles.create'])
    <div class="bg-white dark:bg-gray-800 shadow rounded-xl border border-gray-200 dark:border-gray-700">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Quick Actions</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Manage your system efficiently</p>
                </div>
            </div>
            
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                @can('users.index')
                <a href="{{ route('admin.users.index') }}" 
                   class="group p-4 bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-600 rounded-lg hover:shadow-md transition-all duration-200 border border-blue-100 dark:border-gray-600">
                    <div class="flex flex-col items-center text-center">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center mb-3">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"></path>
                            </svg>
                        </div>
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Users</h4>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Manage users</p>
                    </div>
                </a>
                @endcan
                
                @can('roles.index')
                <a href="{{ route('admin.roles.index') }}" 
                   class="group p-4 bg-gradient-to-br from-green-50 to-emerald-50 dark:from-gray-700 dark:to-gray-600 rounded-lg hover:shadow-md transition-all duration-200 border border-green-100 dark:border-gray-600">
                    <div class="flex flex-col items-center text-center">
                        <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center mb-3">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Roles</h4>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Manage roles</p>
                    </div>
                </a>
                @endcan

                @can('permissions.index')
                <a href="{{ route('admin.permissions.index') }}" 
                   class="group p-4 bg-gradient-to-br from-yellow-50 to-orange-50 dark:from-gray-700 dark:to-gray-600 rounded-lg hover:shadow-md transition-all duration-200 border border-yellow-100 dark:border-gray-600">
                    <div class="flex flex-col items-center text-center">
                        <div class="w-10 h-10 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-lg flex items-center justify-center mb-3">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Permissions</h4>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Manage permissions</p>
                    </div>
                </a>
                @endcan

                @can('roles.create')
                <a href="{{ route('admin.roles.create') }}" 
                   class="group p-4 bg-gradient-to-br from-purple-50 to-pink-50 dark:from-gray-700 dark:to-gray-600 rounded-lg hover:shadow-md transition-all duration-200 border border-purple-100 dark:border-gray-600">
                    <div class="flex flex-col items-center text-center">
                        <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-lg flex items-center justify-center mb-3">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Create Role</h4>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Add new role</p>
                    </div>
                </a>
                @endcan
            </div>
        </div>
    </div>
    @endcanany
</div>
@endsection