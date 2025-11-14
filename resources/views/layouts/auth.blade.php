<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' || (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches) }" x-bind:class="{ 'dark': darkMode }" x-init="$watch('darkMode', value => localStorage.setItem('darkMode', value))">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - The Paranoia</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        /* Sembunyikan tombol 'show password' bawaan Edge/IE */
        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear {
        display: none;
        }

    </style>
    
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Figtree', 'sans-serif'],
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-up': 'slideUp 0.3s ease-out',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(10px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' },
                        }
                    }
                }
            }
        }
    </script>

    <!-- Dark mode flash prevention -->
    <script>
        (function() {
            const darkMode = localStorage.getItem('darkMode') === 'true' || 
                           (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches);
            if (darkMode) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
</head>
<body class="bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 font-sans antialiased transition-colors duration-300">
    <div class="relative min-h-svh flex items-center justify-center px-4 py-8 animate-fade-in">
        <!-- Theme Toggle -->
            <div class="absolute -top-0 right-1 z-10">
                <button @click="darkMode = !darkMode" 
                        class="group p-3 text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-gray-100 hover:bg-white/50 dark:hover:bg-gray-700/50 rounded-xl backdrop-blur-sm border border-white/30 dark:border-gray-600/30 shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105"
                        :title="darkMode ? 'Switch to light mode' : 'Switch to dark mode'">
                    <svg x-show="!darkMode" class="w-5 h-5 transform transition-transform duration-300 group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                    </svg>
                    <svg x-show="darkMode" class="w-5 h-5 transform transition-transform duration-300 group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </button>
            </div>
        <!-- Background Pattern -->
        <div class="absolute inset-0 -z-10 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-32 w-80 h-80 bg-gradient-to-br from-blue-400 to-purple-600 rounded-full opacity-10 dark:opacity-5 blur-3xl animate-pulse"></div>
            <div class="absolute -bottom-40 -left-32 w-80 h-80 bg-gradient-to-br from-purple-400 to-pink-600 rounded-full opacity-10 dark:opacity-5 blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-gradient-to-br from-emerald-400 to-blue-600 rounded-full opacity-5 dark:opacity-3 blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
        </div>

        <!-- Main Container -->
        <div class="relative w-full max-w-md animate-slide-up">
            <!-- Logo & Brand -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-600 to-purple-600 dark:from-blue-500 dark:to-purple-500 rounded-2xl shadow-xl dark:shadow-2xl mb-4 transform hover:scale-105 transition-transform duration-300">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white transition-colors duration-300">The Paranoia</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 transition-colors duration-300">Admin Panel System</p>
            </div>

            <!-- Main Content Card -->
            <div class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-xl shadow-xl dark:shadow-2xl border border-white/30 dark:border-gray-700/50 rounded-2xl p-8 transition-all duration-300 hover:shadow-2xl dark:hover:shadow-3xl">
                @if (session('success'))
                    <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-700 rounded-lg animate-slide-up">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-green-400 dark:text-green-300 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-sm text-green-800 dark:text-green-200">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-700 rounded-lg animate-slide-up">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-red-400 dark:text-red-300 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            <p class="text-sm text-red-800 dark:text-red-200">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                @yield('content')
            </div>

            <!-- Footer -->
            <div class="text-center">
                <!-- <p class="text-sm text-gray-600 dark:text-gray-400 transition-colors duration-300">
                    © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                </p> -->
                <p class="text-xs text-gray-500 dark:text-gray-500 mt-1 transition-colors duration-300">
                    Powered by Laravel & Tailwind CSS
                </p>
            </div>
        </div>
    </div>
</body>
</html>