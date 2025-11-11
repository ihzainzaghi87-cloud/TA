@extends('admin.layouts.app')

@section('title', 'Roles')

@section('content')
<div class="space-y-6">
    <!-- Header Section - Compact -->
    <div class="relative bg-gradient-to-r from-green-600 to-emerald-600 dark:from-green-800 dark:to-emerald-800 overflow-hidden shadow-lg rounded-xl">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="relative p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-bold mb-1">Role Management</h1>
                    <p class="text-green-100 text-sm">Manage roles and permissions</p>
                </div>
                @can('roles.create')
                <a href="{{ route('admin.roles.create') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-white bg-opacity-20 backdrop-blur-sm border border-white border-opacity-30 rounded-lg text-white hover:bg-opacity-30 transition-all duration-200 text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Create Role
                </a>
                @endcan
            </div>
        </div>
    </div>

    <!-- Success Alert -->
    @if (session('success'))
        <x-alert type="success" :message="session('success')" />
    @endif

    <!-- Roles Table - Compact -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Role List</h3>
                <span class="text-xs text-gray-600 dark:text-gray-400">Total {{ $roles->total() }} roles</span>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900/40">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">#</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Role</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Permissions</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Users</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @php
                        $start = ($roles->currentPage() - 1) * $roles->perPage();
                    @endphp

                    @forelse ($roles as $i => $role)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                        <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white">
                            <div class="w-6 h-6 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center text-white font-bold text-xs">
                                {{ $start + $i + 1 }}
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $role->name }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Role system</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center">
                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                    {{ $role->permissions_count ?? $role->permissions()->count() }} permissions
                                </span>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center">
                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-200">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"></path>
                                    </svg>
                                    {{ $role->users_count ?? \App\Models\User::role($role->name)->count() }} users
                                </span>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-2">
                                @can('roles.view')
                                <a href="{{ route('admin.roles.show', $role) }}" 
                                   class="inline-flex items-center px-2 py-1 border border-gray-300 dark:border-gray-600 rounded-md text-xs font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors duration-200">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    View
                                </a>
                                @endcan

                                @can('roles.update')
                                <a href="{{ route('admin.roles.edit', $role) }}" 
                                   class="inline-flex items-center px-2 py-1 bg-gradient-to-r from-green-600 to-emerald-600 border border-transparent rounded-md text-xs font-medium text-white hover:from-green-700 hover:to-emerald-700 transition-all duration-200">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Edit
                                </a>
                                @endcan

                                @can('roles.delete')
                                <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Hapus role {{ $role->name }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center px-2 py-1 bg-gradient-to-r from-red-600 to-rose-600 border border-transparent rounded-md text-xs font-medium text-white hover:from-red-700 hover:to-rose-700 transition-all duration-200">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Delete
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8">
                            <div class="text-center">
                                <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No roles yet</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Start by adding your first role.</p>
                                @can('roles.create')
                                <div class="mt-4">
                                    <a href="{{ route('admin.roles.create') }}" 
                                       class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-green-600 to-emerald-600 border border-transparent rounded-lg text-sm font-medium text-white hover:from-green-700 hover:to-emerald-700 transition-all duration-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Create Role
                                    </a>
                                </div>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-4 py-3 bg-gray-50 dark:bg-gray-900/40 border-t border-gray-200 dark:border-gray-700">
            {{ $roles->onEachSide(1)->links() }}
        </div>
    </div>
</div>
@endsection