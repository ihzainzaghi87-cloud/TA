@extends('admin.layouts.app')

@section('title', 'Permissions Management')

@section('content')
<div class="space-y-6">
    <!-- Header Section - Compact -->
    <div class="relative bg-gradient-to-r from-yellow-600 to-orange-600 dark:from-yellow-800 dark:to-orange-800 overflow-hidden shadow-lg rounded-xl">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="relative p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-bold mb-1">Permissions Management</h1>
                    <p class="text-yellow-100 text-sm">Create and manage application permissions</p>
                </div>
                @can('permissions.create')
                <a href="{{ route('admin.permissions.create') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-white bg-opacity-20 backdrop-blur-sm border border-white border-opacity-30 rounded-lg text-white hover:bg-opacity-30 transition-all duration-200 text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Create Permission
                </a>
                @endcan
            </div>
        </div>
    </div>

    <!-- Permissions Table - Compact -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Permissions List</h3>
                <span class="text-xs text-gray-600 dark:text-gray-400">Total {{ $permissions->total() }} permissions</span>
            </div>
        </div>
        
        @if($permissions->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900/40">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Permission</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Roles Using</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Direct Users</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Created</th>
                            @canany(['permissions.update','permissions.delete'])
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                            @endcanany
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($permissions as $permission)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                                <td class="px-4 py-3">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $permission->name }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">Permission</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap gap-1">
                                        @forelse($permission->roles->take(2) as $role)
                                            <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200">
                                                {{ $role->name }}
                                            </span>
                                        @empty
                                            <span class="text-xs text-gray-500 dark:text-gray-400">No roles</span>
                                        @endforelse
                                        @if($permission->roles->count() > 2)
                                            <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                                +{{ $permission->roles->count() - 2 }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"></path>
                                        </svg>
                                        {{ $permission->users->count() }} users
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $permission->created_at->format('M d, Y') }}
                                </td>
                                @canany(['permissions.update','permissions.delete'])
                                <td class="px-4 py-3">
                                    <div class="flex justify-end gap-2">
                                        @can('permissions.update')
                                        <a href="{{ route('admin.permissions.edit', $permission) }}" 
                                           class="inline-flex items-center px-2 py-1 border border-gray-300 dark:border-gray-600 rounded-md text-xs font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors duration-200">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Edit
                                        </a>
                                        @endcan

                                        @can('permissions.delete')
                                        <form method="POST" action="{{ route('admin.permissions.destroy', $permission) }}" 
                                              class="inline"
                                              onsubmit="return confirm('Are you sure you want to delete this permission?')">
                                            @csrf
                                            @method('DELETE')
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
                                @endcanany
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($permissions->hasPages())
                <div class="px-4 py-3 bg-gray-50 dark:bg-gray-900/40 border-t border-gray-200 dark:border-gray-700">
                    {{ $permissions->links() }}
                </div>
            @endif
        @else
            <div class="px-4 py-8">
                <div class="text-center">
                    <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No permissions found</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by creating your first permission.</p>
                    @can('permissions.create')
                    <div class="mt-4">
                        <a href="{{ route('admin.permissions.create') }}" 
                           class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-yellow-600 to-orange-600 border border-transparent rounded-lg text-sm font-medium text-white hover:from-yellow-700 hover:to-orange-700 transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Create Permission
                        </a>
                    </div>
                    @endcan
                </div>
            </div>
        @endif
    </div>
</div>
@endsection