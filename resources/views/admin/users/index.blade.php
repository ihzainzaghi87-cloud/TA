@extends('admin.layouts.app')

@section('title', 'Users')

@section('content')
<div class="space-y-6">
    <!-- Header Section - Lebih Compact -->
    <div class="relative bg-gradient-to-r from-blue-600 to-purple-600 dark:from-blue-800 dark:to-purple-800 overflow-hidden shadow-lg rounded-xl">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="relative p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-bold mb-1">User Management</h1>
                    <p class="text-blue-100 text-sm">Manage user data, roles, and permissions</p>
                </div>
                @can('users.create')
                <a href="{{ route('admin.users.create') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-white bg-opacity-20 backdrop-blur-sm border border-white border-opacity-30 rounded-lg text-white hover:bg-opacity-30 transition-all duration-200 text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Create User
                </a>
                @endcan
            </div>
        </div>
    </div>

    <!-- Search & Filters Section - Compact -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-xl border border-gray-200 dark:border-gray-700 p-4">
        <form method="GET" action="{{ route('admin.users.index') }}">
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" 
                               name="q" 
                               placeholder="Cari nama atau email pengguna..." 
                               value="{{ $q }}"
                               class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200">
                    </div>
                </div>
                <div class="flex gap-2">
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors duration-200 flex items-center gap-2 text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Search
                    </button>
                    @if($q)
                    <a href="{{ route('admin.users.index') }}" 
                       class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg font-medium transition-colors duration-200 flex items-center gap-2 text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Reset
                    </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Users Table - Compact -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Users List</h3>
                <span class="text-xs text-gray-600 dark:text-gray-400">Total {{ $users->total() }} users</span>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900/40">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">#</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Users</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Contact</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Roles</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @php
                        $start = ($users->currentPage() - 1) * $users->perPage();
                    @endphp

                    @forelse ($users as $i => $u)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                        <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white">
                            <div class="w-6 h-6 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center text-white font-bold text-xs">
                                {{ $start + $i + 1 }}
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                                    <span class="text-white font-bold text-sm">{{ substr($u->name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $u->name }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $u->username ?? 'No username' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="text-sm text-gray-900 dark:text-white">{{ $u->email }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $u->phone_number ?? 'No phone' }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-1">
                                @php $roleNames = $u->getRoleNames(); @endphp
                                @forelse($roleNames as $r)
                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-gradient-to-r from-blue-100 to-purple-100 text-blue-800 dark:from-blue-900/30 dark:to-purple-900/30 dark:text-blue-200">
                                        {{ $r }}
                                    </span>
                                @empty
                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400">
                                        No roles
                                    </span>
                                @endforelse
                            </div>
                        </td>
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
                                   class="inline-flex items-center px-2 py-1 bg-gradient-to-r from-blue-600 to-purple-600 border border-transparent rounded-md text-xs font-medium text-white hover:from-blue-700 hover:to-purple-700 transition-all duration-200">
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
                        <td colspan="6" class="px-4 py-8">
                            <div class="text-center">
                                <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No users</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Start by adding the first user.</p>
                                @can('users.create')
                                <div class="mt-4">
                                    <a href="{{ route('admin.users.create') }}" 
                                       class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-blue-600 to-purple-600 border border-transparent rounded-lg text-sm font-medium text-white hover:from-blue-700 hover:to-purple-700 transition-all duration-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Create User
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
            {{ $users->onEachSide(1)->links() }}
        </div>
    </div>
</div>
@endsection