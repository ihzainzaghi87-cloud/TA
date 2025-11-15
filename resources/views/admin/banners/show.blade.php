@extends('admin.layouts.app')

@section('title', 'Banner Detail')

@section('content')
<div class="space-y-6">
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
        <a href="{{ route('admin.banners.index') }}" class="hover:text-blue-600 dark:hover:text-blue-400">Banners</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
        <span class="text-gray-900 dark:text-white font-medium">Detail</span>
    </div>

    <!-- Header -->
    <div class="relative bg-gradient-to-r from-purple-600 to-pink-600 dark:from-purple-800 dark:to-pink-800 overflow-hidden shadow-lg rounded-xl">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="relative p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-bold mb-1">Banner Detail</h1>
                    <p class="text-purple-100 text-sm">View detailed information about this banner</p>
                </div>
                <div class="flex gap-2">
                    @can('banners.update')
                    <a href="{{ route('admin.banners.edit', $banner) }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 bg-white bg-opacity-20 backdrop-blur-sm border border-white border-opacity-30 rounded-lg text-white hover:bg-opacity-30 transition-all duration-200 text-sm font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Banner
                    </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Banner Image -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Banner Image</h2>
                </div>
                <div class="p-6">
                    <div class="relative">
                        <img src="{{ asset('storage/' . $banner->image) }}" 
                             alt="{{ $banner->title }}" 
                             class="w-full rounded-lg border-2 border-gray-200 dark:border-gray-600 object-cover">
                        
                        <!-- Status Badge on Image -->
                        <div class="absolute top-4 right-4">
                            @if($banner->is_active)
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-green-100 text-green-800 border-2 border-green-200 backdrop-blur-sm">
                                <span class="w-2 h-2 mr-2 rounded-full bg-green-500"></span>
                                Active
                            </span>
                            @else
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-gray-100 text-gray-800 border-2 border-gray-200 backdrop-blur-sm">
                                <span class="w-2 h-2 mr-2 rounded-full bg-gray-500"></span>
                                Inactive
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Banner Information -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Banner Information</h2>
                </div>
                <div class="p-6">
                    <dl class="space-y-4">
                        <div class="p-4 bg-gray-50 dark:bg-gray-900/40 rounded-lg">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Title</dt>
                            <dd class="text-base font-medium text-gray-900 dark:text-white">
                                {{ $banner->title ?? 'Untitled Banner' }}
                            </dd>
                        </div>
                        
                        <div class="p-4 bg-gray-50 dark:bg-gray-900/40 rounded-lg">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Image Path</dt>
                            <dd class="text-sm text-gray-900 dark:text-white font-mono break-all bg-white dark:bg-gray-800 px-3 py-2 rounded border border-gray-200 dark:border-gray-600">
                                {{ $banner->image }}
                            </dd>
                        </div>
                        
                        <div class="p-4 bg-gray-50 dark:bg-gray-900/40 rounded-lg">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Full URL</dt>
                            <dd class="text-sm break-all">
                                <a href="{{ asset('storage/' . $banner->image) }}" 
                                   target="_blank" 
                                   class="text-blue-600 dark:text-blue-400 hover:underline inline-flex items-center gap-1">
                                    {{ asset('storage/' . $banner->image) }}
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                </a>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Statistics -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Statistics</h2>
                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Quick overview</p>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between p-3 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-2 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">Status</p>
                            </div>
                        </div>
                        @if($banner->is_active)
                        <span class="text-sm font-semibold text-green-600 dark:text-green-400">Active</span>
                        @else
                        <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">Inactive</span>
                        @endif
                    </div>

                    <div class="p-3 bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-2 bg-gradient-to-br from-purple-500 to-pink-600 rounded-lg">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">Created</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $banner->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-3 bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-2 bg-gradient-to-br from-amber-500 to-orange-600 rounded-lg">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">Last Updated</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $banner->updated_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Quick Actions</h2>
                </div>
                <div class="p-6 space-y-3">
                    @can('banners.update')
                    <form action="{{ route('admin.banners.toggle-status', $banner) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                                class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                            @if($banner->is_active)
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                            </svg>
                            Deactivate Banner
                            @else
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Activate Banner
                            @endif
                        </button>
                    </form>
                    @endcan

                    <a href="{{ asset('storage/' . $banner->image) }}" 
                       download
                       class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Download Image
                    </a>
                </div>
            </div>

            <!-- Delete Section -->
            @can('banners.delete')
            <div class="bg-white dark:bg-gray-800 shadow rounded-xl border border-red-200 dark:border-red-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-red-200 dark:border-red-700 bg-red-50 dark:bg-red-900/20">
                    <h2 class="text-lg font-semibold text-red-900 dark:text-red-400">Danger Zone</h2>
                </div>
                <div class="p-6">
                    <p class="text-sm text-red-600 dark:text-red-300 mb-4">
                        Once you delete this banner, the image will be permanently removed. This action cannot be undone.
                    </p>
                    <form action="{{ route('admin.banners.destroy', $banner) }}" 
                          method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete this banner? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full flex items-center justify-center px-4 py-2 bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 text-white text-sm font-medium rounded-lg transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Delete Banner
                        </button>
                    </form>
                </div>
            </div>
            @endcan
        </div>
    </div>
</div>
@endsection
