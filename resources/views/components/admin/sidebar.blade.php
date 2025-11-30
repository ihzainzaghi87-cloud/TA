<!-- Sidebar - Lebih Minimalis -->
<div class="fixed inset-y-0 left-0 z-50 w-56 bg-white dark:bg-gray-800 shadow-lg transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 border-r border-gray-200 dark:border-gray-700" 
     :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
    
    <!-- Logo - Lebih Kecil -->
    <div class="flex items-center justify-center h-14 px-4 bg-gradient-to-r from-indigo-600 to-purple-600 dark:from-indigo-700 dark:to-purple-700">
        <div class="flex items-center space-x-2">
            <div class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
            <a href="{{ route('dashboard') }}" class="text-lg font-bold text-white">The Paranoia</a>
        </div>
    </div>
    
    <!-- Navigation - Lebih Kompak -->
    <nav class="flex-1 px-3 py-4 overflow-y-auto">
        <div class="space-y-1">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" 
               class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-md' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                <div class="flex items-center justify-center w-7 h-7 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-white bg-opacity-20' : 'bg-gray-100 dark:bg-gray-700 group-hover:bg-indigo-100 dark:group-hover:bg-indigo-900' }} mr-3 transition-colors duration-200">
                    <svg class="w-4 h-4 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-gray-600 dark:text-gray-400 group-hover:text-indigo-600 dark:group-hover:text-indigo-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                    </svg>
                </div>
                <span>Dashboard</span>
                @if(request()->routeIs('dashboard'))
                    <div class="ml-auto w-1.5 h-1.5 bg-white rounded-full animate-pulse"></div>
                @endif
            </a>

            <!-- Divider -->
            <div class="my-3 border-t border-gray-200 dark:border-gray-700"></div>

            <!-- Category -->
            @can('categories.index')
            <a href="{{ route('admin.categories.index') }}"" 
               class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                <div class="flex items-center justify-center w-7 h-7 rounded-lg bg-gray-100 dark:bg-gray-700 group-hover:bg-pink-100 dark:group-hover:bg-pink-900 mr-3 transition-colors duration-200">
                    <svg class="w-4 h-4 text-gray-600 dark:text-gray-400 group-hover:text-pink-600 dark:group-hover:text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <span>Category</span>
                @if(request()->routeIs('admin.categories.*'))
                    <div class="ml-auto w-1 h-1 bg-white rounded-full animate-pulse"></div>
                @endif
            </a>
            @endcan

            <!-- Product -->
            @can('products.index')
            <a href="{{ route('admin.products.index') }}"" 
               class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                <div class="flex items-center justify-center w-7 h-7 rounded-lg bg-gray-100 dark:bg-gray-700 group-hover:bg-pink-100 dark:group-hover:bg-pink-900 mr-3 transition-colors duration-200">
                    <svg class="w-4 h-4 text-gray-600 dark:text-gray-400 group-hover:text-pink-600 dark:group-hover:text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <span>Products</span>
                @if(request()->routeIs('admin.products.*'))
                    <div class="ml-auto w-1 h-1 bg-white rounded-full animate-pulse"></div>
                @endif
            </a>
            @endcan

            <!-- Order -->
            @can('orders.index')
            <a href="{{ route('admin.orders.index') }}"" 
               class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                <div class="flex items-center justify-center w-7 h-7 rounded-lg bg-gray-100 dark:bg-gray-700 group-hover:bg-pink-100 dark:group-hover:bg-pink-900 mr-3 transition-colors duration-200">
                    <svg class="w-4 h-4 text-gray-600 dark:text-gray-400 group-hover:text-pink-600 dark:group-hover:text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <span>Orders</span>
                @if(request()->routeIs('admin.orders.*'))
                    <div class="ml-auto w-1 h-1 bg-white rounded-full animate-pulse"></div>
                @endif
            </a>
            @endcan
            
            <!-- Analytics -->
            <a href="#" 
               class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                <div class="flex items-center justify-center w-7 h-7 rounded-lg bg-gray-100 dark:bg-gray-700 group-hover:bg-pink-100 dark:group-hover:bg-pink-900 mr-3 transition-colors duration-200">
                    <svg class="w-4 h-4 text-gray-600 dark:text-gray-400 group-hover:text-pink-600 dark:group-hover:text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <span>Analytics</span>
            </a>

            <!-- System Logs -->
            <a href="#" 
               class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                <div class="flex items-center justify-center w-7 h-7 rounded-lg bg-gray-100 dark:bg-gray-700 group-hover:bg-red-100 dark:group-hover:bg-red-900 mr-3 transition-colors duration-200">
                    <svg class="w-4 h-4 text-gray-600 dark:text-gray-400 group-hover:text-red-600 dark:group-hover:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <span>System Logs</span>
            </a>

            <!-- Divider -->
            <div class="my-3 border-t border-gray-200 dark:border-gray-700"></div>
            
            <!-- System Settings Dropdown -->
            <div class="space-y-1" x-data="{ open: {{ request()->routeIs('admin.banners.*') || request()->routeIs('admin.roles.*') || request()->routeIs('admin.permissions.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" 
                        class="group w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                    <div class="flex text-xs items-center">
                        <div class="flex items-center justify-center w-7 h-7 rounded-lg bg-gray-100 dark:bg-gray-700 group-hover:bg-emerald-100 dark:group-hover:bg-emerald-900 mr-3 transition-colors duration-200">
                            <svg class="w-4 h-4 text-gray-600 dark:text-gray-400 group-hover:text-emerald-600 dark:group-hover:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <span>System Settings</span>
                    </div>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <!-- Dropdown Menu -->
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-2"
                     class="ml-6 space-y-1">
                    
                    <!-- Banners -->
                    @can('banners.index')
                    <a href="{{ route('admin.banners.index') }}" 
                       class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.banners.*') ? 'bg-gradient-to-r from-blue-500 to-cyan-600 text-white shadow-md' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        <div class="flex items-center justify-center w-5 h-5 rounded-md {{ request()->routeIs('admin.banners.*') ? 'bg-white bg-opacity-20' : 'bg-gray-100 dark:bg-gray-700 group-hover:bg-blue-100 dark:group-hover:bg-blue-900' }} mr-3 transition-colors duration-200">
                            <svg class="w-3 h-3 {{ request()->routeIs('admin.banners.*') ? 'text-white' : 'text-gray-500 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"></path>
                            </svg>
                        </div>
                        <span>Banner Hero</span>
                        @if(request()->routeIs('admin.banners.*'))
                            <div class="ml-auto w-1 h-1 bg-white rounded-full animate-pulse"></div>
                        @endif
                    </a>
                    @endcan
                </div>
            </div>

            <!-- Divider -->
            <div class="my-3 border-t border-gray-200 dark:border-gray-700"></div>
            
            <!-- Users Management Dropdown -->
            <div class="space-y-1" x-data="{ open: {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.roles.*') || request()->routeIs('admin.permissions.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" 
                        class="group w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                    <div class="flex text-xs items-center">
                        <div class="flex items-center justify-center w-7 h-7 rounded-lg bg-gray-100 dark:bg-gray-700 group-hover:bg-emerald-100 dark:group-hover:bg-emerald-900 mr-3 transition-colors duration-200">
                            <svg class="w-4 h-4 text-gray-600 dark:text-gray-400 group-hover:text-emerald-600 dark:group-hover:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <span>Users Management</span>
                    </div>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <!-- Dropdown Menu -->
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-2"
                     class="ml-6 space-y-1">
                    
                    <!-- Users -->
                    @can('users.index')
                    <a href="{{ route('admin.users.index') }}" 
                       class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-gradient-to-r from-blue-500 to-cyan-600 text-white shadow-md' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        <div class="flex items-center justify-center w-5 h-5 rounded-md {{ request()->routeIs('admin.users.*') ? 'bg-white bg-opacity-20' : 'bg-gray-100 dark:bg-gray-700 group-hover:bg-blue-100 dark:group-hover:bg-blue-900' }} mr-3 transition-colors duration-200">
                            <svg class="w-3 h-3 {{ request()->routeIs('admin.users.*') ? 'text-white' : 'text-gray-500 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"></path>
                            </svg>
                        </div>
                        <span>Users</span>
                        @if(request()->routeIs('admin.users.*'))
                            <div class="ml-auto w-1 h-1 bg-white rounded-full animate-pulse"></div>
                        @endif
                    </a>
                    @endcan

                    <!-- Roles -->
                    @can('roles.index')
                    <a href="{{ route('admin.roles.index') }}" 
                       class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.roles.*') ? 'bg-gradient-to-r from-green-500 to-emerald-600 text-white shadow-md' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        <div class="flex items-center justify-center w-5 h-5 rounded-md {{ request()->routeIs('admin.roles.*') ? 'bg-white bg-opacity-20' : 'bg-gray-100 dark:bg-gray-700 group-hover:bg-green-100 dark:group-hover:bg-green-900' }} mr-3 transition-colors duration-200">
                            <svg class="w-3 h-3 {{ request()->routeIs('admin.roles.*') ? 'text-white' : 'text-gray-500 dark:text-gray-400 group-hover:text-green-600 dark:group-hover:text-green-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <span>Roles</span>
                        @if(request()->routeIs('admin.roles.*'))
                            <div class="ml-auto w-1 h-1 bg-white rounded-full animate-pulse"></div>
                        @endif
                    </a>
                    @endcan

                    <!-- Permissions -->
                    @can('permissions.index')
                    <a href="{{ route('admin.permissions.index') }}" 
                       class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.permissions.*') ? 'bg-gradient-to-r from-yellow-500 to-orange-500 text-white shadow-md' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        <div class="flex items-center justify-center w-5 h-5 rounded-md {{ request()->routeIs('admin.permissions.*') ? 'bg-white bg-opacity-20' : 'bg-gray-100 dark:bg-gray-700 group-hover:bg-yellow-100 dark:group-hover:bg-yellow-900' }} mr-3 transition-colors duration-200">
                            <svg class="w-3 h-3 {{ request()->routeIs('admin.permissions.*') ? 'text-white' : 'text-gray-500 dark:text-gray-400 group-hover:text-yellow-600 dark:group-hover:text-yellow-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <span>Permissions</span>
                        @if(request()->routeIs('admin.permissions.*'))
                            <div class="ml-auto w-1 h-1 bg-white rounded-full animate-pulse"></div>
                        @endif
                    </a>
                    @endcan
                </div>
            </div>
        </div>
    </nav>

    <!-- User Info Card at Bottom - Lebih Kecil -->
    <div class="p-3 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 sticky bottom-0">
        <div class="p-3 bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 rounded-lg border border-gray-200 dark:border-gray-600">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                    <span class="text-white font-bold text-xs">{{ substr(auth()->user()->name, 0, 1) }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <a href="{{ route('admin.profile.show') }}" class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ auth()->user()->name }}</a>
                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ auth()->user()->getRoleNames()->implode(', ') }}</p>
                </div>
            </div>
            <div class="mt-2 flex items-center justify-between">
                <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                    <div class="w-1.5 h-1.5 bg-green-500 rounded-full mr-2 animate-pulse"></div>
                    <span>Online</span>
                </div>
            </div>
        </div>
    </div>
</div>