@extends('admin.layouts.app')

@section('title', 'Detail User')

@section('content')
<div class="space-y-6">
    <!-- Header Section - Compact -->
    <div class="relative bg-gradient-to-r from-indigo-600 to-purple-600 dark:from-indigo-800 dark:to-purple-800 overflow-hidden shadow-lg rounded-xl">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="relative p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-bold mb-1">Detail User</h1>
                    <p class="text-indigo-100 text-sm">Complete user information: {{ $user->name }}</p>
                </div>
                <div class="flex gap-2">
                    @can('users.update')
                    <a href="{{ route('admin.users.edit', $user) }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 bg-white bg-opacity-20 backdrop-blur-sm border border-white border-opacity-30 rounded-lg text-white hover:bg-opacity-30 transition-all duration-200 text-sm font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit
                    </a>
                    @endcan
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
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <!-- Detail User Section - Compact -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700">
            <div class="p-5">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center">
                        <span class="text-white font-bold text-lg">{{ substr($user->name, 0, 1) }}</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $user->name }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $user->email }}</p>
                    </div>
                </div>

                <div class="space-y-3 text-sm">
                    <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-gray-500 dark:text-gray-400 font-medium">Name:</span>
                        <span class="text-gray-900 dark:text-white">{{ $user->name }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-gray-500 dark:text-gray-400 font-medium">Email:</span>
                        <span class="text-gray-900 dark:text-white">{{ $user->email }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-gray-500 dark:text-gray-400 font-medium">Username:</span>
                        <span class="text-gray-900 dark:text-white">{{ $user->username ?? '—' }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-gray-500 dark:text-gray-400 font-medium">Phone Number:</span>
                        <span class="text-gray-900 dark:text-white">{{ $user->phone_number ?? '—' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Roles & Permissions Section - Compact -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700">
            <div class="p-5">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-8 h-8 bg-gradient-to-br from-emerald-500 to-green-600 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white">Role & Permissions</h3>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Manage user roles and permissions</p>
                    </div>
                </div>

                <!-- Info Box -->
                <div class="mb-4 p-3 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg">
                    <p class="text-xs text-amber-800 dark:text-amber-200">
                        <strong>Direct permission</strong> is a permission granted directly to the user (not through a role). 
                        The system checks access rights as a combination of permissions via roles and direct permissions.
                    </p>
                </div>

                <!-- Roles -->
                <div class="mb-4">
                    <div class="flex items-center gap-2 mb-2">
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-200">Roles</h4>
                        <span class="text-xs text-gray-500 dark:text-gray-400">({{ count($roleNames) }})</span>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        @forelse ($roleNames as $r)
                            <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200">
                                {{ $r }}
                            </span>
                        @empty
                            <span class="text-sm text-gray-500 dark:text-gray-400">No roles</span>
                        @endforelse
                    </div>
                </div>

                <!-- Permissions via Role -->
                <div class="mb-4">
                    <div class="flex items-center gap-2 mb-2">
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-200">Permissions (via Role)</h4>
                        <span class="text-xs text-gray-500 dark:text-gray-400">({{ count($permissionsViaRoles) }})</span>
                    </div>
                    <div class="max-h-32 overflow-y-auto">
                        <div class="flex flex-wrap gap-1">
                            @forelse ($permissionsViaRoles as $p)
                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-200">
                                    {{ $p }}
                                </span>
                            @empty
                                <span class="text-sm text-gray-500 dark:text-gray-400">No permissions via role</span>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Direct Permissions -->
                <div class="mb-4">
                    <div class="flex items-center gap-2 mb-2">
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-200">Direct Permissions</h4>
                        <span class="text-xs text-gray-500 dark:text-gray-400">({{ count($directPermissions) }})</span>
                    </div>
                    <div class="flex flex-wrap gap-1">
                        @forelse ($directPermissions as $dp)
                            <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-200">
                                {{ $dp }}
                            </span>
                        @empty
                            <span class="text-sm text-gray-500 dark:text-gray-400">No direct permissions</span>
                        @endforelse
                    </div>
                </div>

                @isset($effectivePermissions)
                <!-- Effective Permissions -->
                <div class="mb-4">
                    <div class="flex items-center gap-2 mb-2">
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-200">Effective Permissions</h4>
                        <span class="text-xs text-gray-500 dark:text-gray-400">({{ count($effectivePermissions) }})</span>
                    </div>
                    <div class="max-h-32 overflow-y-auto">
                        <div class="flex flex-wrap gap-1">
                            @forelse ($effectivePermissions as $ep)
                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-sky-100 text-sky-800 dark:bg-sky-900/30 dark:text-sky-200">
                                    {{ $ep }}
                                </span>
                            @empty
                                <span class="text-sm text-gray-500 dark:text-gray-400">No effective permissions</span>
                            @endforelse
                        </div>
                    </div>
                </div>
                @endisset

                <!-- Role Details -->
                <div>
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Permission Details per Role</h4>
                    <div class="space-y-3 max-h-48 overflow-y-auto">
                        @forelse ($rolePermissionsMap as $roleName => $perms)
                            <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">{{ $roleName }}</div>
                                <div class="flex flex-wrap gap-1">
                                    @if (count($perms))
                                        @foreach ($perms as $perm)
                                            <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-rose-100 text-rose-800 dark:bg-rose-900/30 dark:text-rose-200">
                                                {{ $perm }}
                                            </span>
                                        @endforeach
                                    @else
                                        <span class="text-xs text-gray-500 dark:text-gray-400">Role does not have permission</span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-sm text-gray-500 dark:text-gray-400">User does not have a role.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection