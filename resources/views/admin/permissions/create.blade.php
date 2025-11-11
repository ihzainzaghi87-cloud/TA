@extends('admin.layouts.app')

@section('title', 'Create Permission')

@section('content')
<div class="space-y-6">
    <!-- Header Section - Compact -->
    <div class="relative bg-gradient-to-r from-emerald-600 to-teal-600 dark:from-emerald-800 dark:to-teal-800 overflow-hidden shadow-lg rounded-xl">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="relative p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-bold mb-1">Create New Permission</h1>
                    <p class="text-emerald-100 text-sm">Create a new permission for your application</p>
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

    <!-- Create Permission Form - Compact -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700">
        <div class="p-5">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Permission Information</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Define new system permission</p>
                </div>
            </div>
            
            <form method="POST" action="{{ route('admin.permissions.store') }}" class="space-y-6">
                @csrf
                
                <!-- Permission Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Permission Name</label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           value="{{ old('name') }}" 
                           required
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm transition-colors duration-200"
                           placeholder="Enter permission name (e.g., users.create, posts.edit)">
                    @error('name')
                        <div class="mt-2 flex items-start">
                            <svg class="w-4 h-4 text-red-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        </div>
                    @enderror
                    
                    <div class="mt-2 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                        <div class="flex items-start">
                            <svg class="w-4 h-4 text-blue-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-sm text-blue-800 dark:text-blue-200">
                                <strong>Tips:</strong> Use descriptive names with dots for organization (e.g., users.create, users.edit, users.delete)
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Permission Examples -->
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-3">Common Permission Examples:</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h5 class="text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2">Users Management:</h5>
                            <div class="space-y-1">
                                <span class="inline-block px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200 rounded text-xs">users.index</span>
                                <span class="inline-block px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200 rounded text-xs">users.create</span>
                                <span class="inline-block px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200 rounded text-xs">users.edit</span>
                                <span class="inline-block px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200 rounded text-xs">users.delete</span>
                            </div>
                        </div>
                        <div>
                            <h5 class="text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2">Roles Management:</h5>
                            <div class="space-y-1">
                                <span class="inline-block px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200 rounded text-xs">roles.index</span>
                                <span class="inline-block px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200 rounded text-xs">roles.create</span>
                                <span class="inline-block px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200 rounded text-xs">roles.edit</span>
                                <span class="inline-block px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200 rounded text-xs">roles.delete</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end space-x-3 pt-4">
                    <a href="{{ route('admin.permissions.index') }}" 
                       class="px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 dark:focus:ring-offset-gray-800 transition-colors duration-200">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 dark:focus:ring-offset-gray-800 text-sm transition-all duration-200 shadow-lg">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Create Permission
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection