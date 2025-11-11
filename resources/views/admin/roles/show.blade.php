@extends('admin.layouts.app')

@section('title', 'Detail Role')

@section('content')
<div class="space-y-6">
    <!-- Header Section - Compact -->
    <div class="relative bg-gradient-to-r from-indigo-600 to-purple-600 dark:from-indigo-800 dark:to-purple-800 overflow-hidden shadow-lg rounded-xl">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="relative p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-bold mb-1">Role Details</h1>
                    <p class="text-indigo-100 text-sm">Role: {{ $role->name }}</p>
                </div>
                <div class="flex gap-2">
                    @can('roles.update')
                    <a href="{{ route('admin.roles.edit', $role) }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 bg-white bg-opacity-20 backdrop-blur-sm border border-white border-opacity-30 rounded-lg text-white hover:bg-opacity-30 transition-all duration-200 text-sm font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit
                    </a>
                    @endcan
                    <a href="{{ route('admin.roles.index') }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 bg-white bg-opacity-20 backdrop-blur-sm border border-white border-opacity-30 rounded-lg text-white hover:bg-opacity-30 transition-all duration-200 text-sm font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Roles
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-5 gap-6">
        <!-- Left Side: Summary + Permission Management -->
        <section class="xl:col-span-2 space-y-6">
            <!-- Summary Stats - Compact -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700 p-5">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white">Role Summary</h3>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Statistics & overview</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="text-center p-3 bg-gradient-to-br from-blue-50 to-purple-50 dark:from-blue-900/20 dark:to-purple-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center mx-auto mb-2">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <div class="text-lg font-bold text-gray-900 dark:text-white">{{ $assignedPermissions->count() }}</div>
                        <div class="text-xs text-gray-600 dark:text-gray-400">Permissions</div>
                    </div>
                    <div class="text-center p-3 bg-gradient-to-br from-emerald-50 to-green-50 dark:from-emerald-900/20 dark:to-green-900/20 rounded-lg border border-emerald-200 dark:border-emerald-800">
                        <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-green-600 rounded-lg flex items-center justify-center mx-auto mb-2">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"></path>
                            </svg>
                        </div>
                        <div class="text-lg font-bold text-gray-900 dark:text-white">{{ $users->total() }}</div>
                        <div class="text-xs text-gray-600 dark:text-gray-400">Users</div>
                    </div>
                </div>

                @if($assignedPermissions->isNotEmpty())
                    <div class="mt-4">
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Assigned Permissions</h4>
                        <div class="max-h-32 overflow-y-auto">
                            <div class="flex flex-wrap gap-1">
                                @foreach ($assignedPermissions as $p)
                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-200">
                                        {{ $p->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @else
                    <div class="mt-4 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg text-center">
                        <p class="text-sm text-gray-500 dark:text-gray-400">This role does not yet have permissions.</p>
                    </div>
                @endif
            </div>

            <!-- Permission Management - Compact -->
            @can('roles.sync-permissions')
            <div x-data="{ all: false, q: '' }" class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700 p-5">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-8 h-8 bg-gradient-to-br from-emerald-500 to-green-600 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white">Kelola Permissions</h3>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Sync permissions for this roles</p>
                    </div>
                </div>

                <div class="flex items-center gap-2 mb-4">
                    <input type="text" 
                           x-model="q" 
                           placeholder="Cari permission..." 
                           class="flex-1 px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                    <label class="flex items-center gap-2 text-xs text-gray-600 dark:text-gray-300">
                        <input type="checkbox" 
                               x-model="all"
                               @change="document.querySelectorAll('input[name=\'permissions[]\']').forEach(cb => cb.checked = all)"
                               class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500 dark:bg-gray-700 dark:border-gray-600">
                        Select all
                    </label>
                </div>

                <form action="{{ route('admin.roles.sync-permissions', $role) }}" method="POST">
                    @csrf
                    <div class="max-h-64 overflow-y-auto border border-gray-200 dark:border-gray-700 rounded-lg p-3 space-y-2 mb-4">
                        @foreach ($allPermissions as $perm)
                            <label class="flex items-center p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200 cursor-pointer"
                                   x-show="'{{ strtolower($perm->name) }}'.includes(q.toLowerCase())" x-cloak>
                                <input type="checkbox" 
                                       name="permissions[]" 
                                       value="{{ $perm->name }}" 
                                       class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500 dark:bg-gray-700 dark:border-gray-600"
                                       @checked(in_array($perm->name, $assignedNames))>
                                <span class="ml-3 text-sm text-gray-700 dark:text-gray-200">{{ $perm->name }}</span>
                            </label>
                        @endforeach
                    </div>

                    <button type="submit" 
                            class="w-full px-6 py-3 bg-gradient-to-r from-emerald-600 to-green-600 hover:from-emerald-700 hover:to-green-700 text-white font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 dark:focus:ring-offset-gray-800 text-sm transition-all duration-200">
                        <span class="flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Save Permissions
                        </span>
                    </button>
                </form>

                <p class="mt-3 text-xs text-gray-500 dark:text-gray-400 text-center">
                    This change will affect all users who have the role <strong>{{ $role->name }}</strong>.
                </p>
            </div>
            @endcan
        </section>

        <!-- Right Side: Users List - Compact -->
        <section class="xl:col-span-3 bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700 p-5">
            <div class="flex items-center justify-between gap-3 mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-pink-600 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white">Users with this role</h3>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Total {{ $users->total() }} users</p>
                    </div>
                </div>
                <form method="GET" action="{{ route('admin.roles.show', $role) }}" class="flex items-center gap-2">
                    <input type="text" 
                           name="q" 
                           value="{{ $q }}" 
                           placeholder="Cari nama/email..."
                           class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    <button type="submit" 
                            class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                        Cari
                    </button>
                    @if($q)
                        <a href="{{ route('admin.roles.show', $role) }}" 
                           class="text-sm text-gray-600 dark:text-gray-300 hover:underline">Reset</a>
                    @endif
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900/40">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">User</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Email</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($users as $u)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                            <td class="px-4 py-3">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-pink-600 rounded-lg flex items-center justify-center">
                                        <span class="text-white font-bold text-sm">{{ substr($u->name, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $u->name }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">Member</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">{{ $u->email }}</td>
                            <td class="px-4 py-3">
                                <div class="flex justify-end gap-2">
                                    @can('users.view')
                                    <a href="{{ route('admin.users.show', $u) }}"
                                       class="inline-flex items-center px-2 py-1 border border-gray-300 dark:border-gray-600 rounded-md text-xs font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors duration-200">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        View
                                    </a>
                                    @endcan
                                    @can('users.update')
                                    <a href="{{ route('admin.users.edit', $u) }}"
                                       class="inline-flex items-center px-2 py-1 bg-gradient-to-r from-purple-600 to-pink-600 border border-transparent rounded-md text-xs font-medium text-white hover:from-purple-700 hover:to-pink-700 transition-all duration-200">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Edit
                                    </a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-4 py-8">
                                <div class="text-center">
                                    <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"></path>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No users</h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">There are no users with this role.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $users->onEachSide(1)->links() }}
            </div>
        </section>
    </div>
</div>
@endsection