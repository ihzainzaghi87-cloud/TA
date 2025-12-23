<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" x-bind:class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>The Paranoia - @yield('title')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Trix Editor Assets -->
    @trixassets
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-up': 'slideUp 0.3s ease-out',
                        'bounce-slow': 'bounce 2s infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(20px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' },
                        }
                    }
                }
            }
        }
    </script>

    @stack('styles')

    <style>
        [x-cloak] { display: none !important; }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }
        
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }
        
        .dark ::-webkit-scrollbar-thumb {
            background: #475569;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        
        .dark ::-webkit-scrollbar-thumb:hover {
            background: #64748b;
        }

        /* Trix Editor Styling */
        trix-editor {
            min-height: 300px;
            max-height: 500px;
            overflow-y: auto;
        }
        
        /* Trix Content Display - untuk halaman show */
        .prose figure {
            margin: 1.5rem 0;
        }
        
        .prose figure img {
            max-width: 100%;
            height: auto;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        .prose figure figcaption {
            margin-top: 0.5rem;
            font-size: 0.875rem;
            color: #6b7280;
            text-align: center;
        }
        
        .dark .prose figure figcaption {
            color: #9ca3af;
        }
        
        /* Trix Attachment */
        .attachment {
            display: inline-block;
            margin: 0.5rem 0;
        }
        
        .attachment__caption {
            font-size: 0.875rem;
            color: #6b7280;
        }
        
        .dark .attachment__caption {
            color: #9ca3af;
        }

        /* Hide Trix Image Caption (filename and filesize) */
        .attachment__caption,
        figcaption.attachment__caption {
            display: none !important;
        }
        
        /* Style untuk gambar Trix di content display */
        .prose figure.attachment {
            margin: 1.5rem 0;
        }
        
        .prose figure.attachment img {
            max-width: 100%;
            height: auto;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        /* Dark mode */
        .dark .prose figure.attachment img {
            border-color: #374151;
        }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 font-sans antialiased transition-colors duration-300" x-data="{ sidebarOpen: false }">
    <div class="min-h-screen flex">
        <!-- Include Sidebar Component -->
        <x-admin.sidebar />

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Include Navbar Component -->
            <x-admin.navbar />

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
                <div class="container mx-auto px-6 py-8 animate-fade-in">
                    @if (session('success'))
                        <x-alert type="success" :message="session('success')" />
                    @endif

                    @if (session('error'))
                        <x-alert type="error" :message="session('error')" />
                    @endif

                    @stack('scripts')

                    @yield('content')
                </div>
            </main>

            <!-- Include Footer Component -->
            <x-admin.footer />
        </div>
    </div>

    <!-- Sidebar Overlay -->
    <div x-show="sidebarOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="sidebarOpen = false" 
         class="fixed inset-0 z-40 bg-black bg-opacity-50 lg:hidden"></div>
    
    @stack('scripts')
</body>
</html>