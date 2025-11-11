@extends('admin.layouts.app')

@section('title', 'Edit Permission')

@section('content')
<div class="space-y-6">
    <!-- Header Section - Compact -->
    <div class="relative bg-gradient-to-r from-amber-600 to-yellow-600 dark:from-amber-800 dark:to-yellow-800 overflow-hidden shadow-lg rounded-xl">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="relative p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-bold mb-1">Edit Permission</h1>
                    <p class="text-amber-100 text-sm">Edit permission information: {{ $permission->name }}</p>
                </div>
                <a href="{{ route('admin.permissions.index') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-white bg-opacity-20 backdrop-blur-sm border border-white border-opacity-30 rounded-lg text-white hover:bg-opacity-30 transition-all duration-200 text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Permissions
                </a>
            </div>
        </div>
    </div>

    <!-- Edit Permission Form - Compact -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700">
        <div class="p-5">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-amber-500 to-yellow-600 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Permission Information</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Update permission details</p>
                </div>
            </div>
            
            <form method="POST" action="{{ route('admin.permissions.update', $permission) }}" class="space-y-6">
                @csrf
                @method('PUT')
                
                <!-- Permission Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Permission Name</label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           value="{{ old('name', $permission->name) }}" 
                           required
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent text-sm transition-colors duration-200">
                    @error('name')
                        <div class="mt-2 flex items-start">
                            <svg class="w-4 h-4 text-red-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        </div>
                    @enderror
                </div>

                <!-- Permission Stats - Compact -->
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-3">Permission Usage:</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center p-3 bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-lg border border-green-200 dark:border-green-800">
                            <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center mx-auto mb-2">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <dt class="text-xs text-gray-500 dark:text-gray-400">Roles</dt>
                            <dd class="text-lg font-bold text-gray-900 dark:text-white">{{ $permission->roles->count() }}</dd>
                        </div>
                        <div class="text-center p-3 bg-gradient-to-br from-blue-50 to-purple-50 dark:from-blue-900/20 dark:to-purple-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center mx-auto mb-2">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"></path>
                                </svg>
                            </div>
                            <dt class="text-xs text-gray-500 dark:text-gray-400">Direct Users</dt>
                            <dd class="text-lg font-bold text-gray-900 dark:text-white">{{ $permission->users->count() }}</dd>
                        </div>
                    </div>
                    
                    @if($permission->roles->count() > 0)
                        <div class="mt-4">
                            <h5 class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-2">Used by roles:</h5>
                            <div class="flex flex-wrap gap-1">
                                @foreach($permission->roles as $role)
                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200">
                                        {{ $role->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end space-x-3 pt-4">
                    <a href="{{ route('admin.permissions.index') }}" 
                       class="px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 dark:focus:ring-offset-gray-800 transition-colors duration-200">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-gradient-to-r from-amber-600 to-yellow-600 hover:from-amber-700 hover:to-yellow-700 text-white font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 dark:focus:ring-offset-gray-800 text-sm transition-all duration-200 shadow-lg">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Update Permission
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Permission Section - Compact -->
    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 rounded-xl p-5">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
            </div>
            <div class="ml-4 flex-1">
                <h3 class="text-sm font-medium text-red-800 dark:text-red-200">Delete Permission</h3>
                <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                    <p>Once you delete this permission, all roles and users with this permission will lose access. This action cannot be undone.</p>
                </div>
                <div class="mt-4">
                    <form method="POST" action="{{ route('admin.permissions.destroy', $permission) }}" 
                          onsubmit="return confirm('Are you sure you want to delete this permission? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:focus:ring-offset-red-900 text-sm transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Delete Permission
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection