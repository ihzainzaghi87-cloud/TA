<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    {{-- SEO Meta Tags --}}
    <meta name="description" content="The Paranoia - Your trusted e-commerce destination for quality products and exceptional service. Shop with confidence.">
    <meta name="keywords" content="e-commerce, online shopping, products, The Paranoia">
    <meta name="author" content="The Paranoia">
    
    {{-- Open Graph Meta Tags --}}
    <meta property="og:title" content="@yield('title', 'The Paranoia - E-Commerce Platform')">
    <meta property="og:description" content="Your trusted e-commerce destination for quality products and exceptional service. Shop with confidence.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    
    <title>@yield('title', 'The Paranoia Store') - The Paranoia</title>

    <meta name="theme-color" content="#6777ef">
    <meta name="user-authenticated" content="{{ auth()->check() ? 'true' : 'false' }}">
    <link rel="apple-touch-icon" href="{{ asset('logo.PNG') }}">
    <link rel="manifest" href="/manifest.json">
    
    {{-- Tailwind CSS CDN - Latest Version --}}
    <script src="https://cdn.tailwindcss.com"></script>
    
    {{-- Font Awesome CDN for Icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" 
          integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" 
          crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    {{-- Google Fonts - Poppins & Bebas Neue --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&family=Bebas+Neue&display=swap" rel="stylesheet">
    
    {{-- Alpine.js for Interactive Components --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @laravelPWA
    
    {{-- Custom Styles --}}
    <style>
        /* Alpine.js cloak */
        [x-cloak] {
            display: none !important;
        }
        
        /* Font Family */
        body {
            font-family: 'Poppins', sans-serif;
        }
        
        .font-bebas {
            font-family: 'Bebas Neue', cursive;
        }
        
        /* Prevent horizontal scroll */
        html, body {
            overflow-x: hidden;
            max-width: 100vw;
        }
        
        /* Smooth Scroll Behavior */
        html {
            scroll-behavior: smooth;
        }
        
        /* Custom Scrollbar - No Gradient */
        ::-webkit-scrollbar {
            width: 10px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #000000;
            border-radius: 5px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #374151;
        }
        
        /* Animation Classes */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .fade-in {
            animation: fadeIn 0.6s ease-out;
        }
    </style>
    
    @stack('styles')
</head>
<body class="antialiased bg-gray-50 text-gray-900 font-['Poppins'] overflow-x-hidden">
    {{-- Include Navbar Component --}}
    @include('components.customer.navbar')
    
    {{-- Main Content Area - Add padding-top to account for fixed navbar --}}
    <main class="pt-16">
        @yield('content')
    </main>
    
    {{-- Include Footer Component --}}
    @include('components.customer.footer')
    
    {{-- Push Notification Modal for PWA --}}
    <div id="pushNotificationModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-[1.5rem] shadow-2xl p-8 max-w-md mx-4 border border-gray-100">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 bg-[#1A1A1D] rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-bell text-white text-lg"></i>
                </div>
                <h2 class="text-2xl font-black text-[#1A1A1D] tracking-tight">Aktifkan Notifikasi</h2>
            </div>
            <p class="text-gray-600 mb-8 leading-relaxed">Dapatkan notifikasi terbaru tentang pesanan dan promo menarik langsung di aplikasi Anda.</p>
            <div class="flex gap-3">
                <button id="declinePush" class="flex-1 px-6 py-3 bg-white border border-gray-300 text-gray-700 rounded-xl font-bold hover:border-[#1A1A1D] hover:text-[#1A1A1D] transition-all shadow-sm">
                    Nanti Saja
                </button>
                <button id="allowPush" class="flex-1 px-6 py-3 bg-[#1A1A1D] text-white rounded-xl font-bold hover:bg-gray-800 transition-all shadow-lg">
                    Izinkan
                </button>
            </div>
        </div>
    </div>
    
    {{-- Additional Scripts --}}
    @stack('scripts')
    
    <!-- <script src="{{ asset('/sw.js') }}"></script> -->
    <script>
    if (!navigator.serviceWorker.controller) {
        navigator.serviceWorker.register("/serviceworker.js").then(function (reg) {
        console.log("Service worker registered: " + reg.scope);
        });
    }
    </script>
    <!-- Push Notification Script -->
    <script src="/js/push-notification.js"></script>
    
    {{-- PWA Detection and Modal Script --}}
    <script>
        // Function to detect if running as PWA
        function isPWA() {
            const isStandalone = window.matchMedia('(display-mode: standalone)').matches;
            const isIOSStandalone = window.navigator.standalone === true;
            const isAndroidApp = document.referrer.includes('android-app://');
            console.log('PWA Detection:', { isStandalone, isIOSStandalone, isAndroidApp });
            return isStandalone || isIOSStandalone || isAndroidApp;
        }

        // Show modal if in PWA and user is logged in
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, checking PWA...');
            if (isPWA()) {
                console.log('Running as PWA');
                const isLoggedIn = document.querySelector('meta[name="user-authenticated"]');
                console.log('User authenticated meta:', isLoggedIn ? isLoggedIn.content : 'not found');
                if (isLoggedIn && isLoggedIn.content === 'true') {
                    console.log('User is logged in');
                    console.log('Notification permission:', Notification.permission);
                    // Check if already subscribed or permission granted
                    if (Notification.permission === 'default') {
                        console.log('Showing modal');
                        document.getElementById('pushNotificationModal').classList.remove('hidden');
                    } else {
                        console.log('Permission not default, skipping modal');
                    }
                } else {
                    console.log('User not logged in, skipping modal');
                }
            } else {
                console.log('Not running as PWA');
            }
        });

        // Modal button handlers
        document.getElementById('allowPush').addEventListener('click', function() {
            document.getElementById('pushNotificationModal').classList.add('hidden');
            // Trigger push notification permission request
            if (typeof requestNotificationPermission === 'function') {
                requestNotificationPermission();
            }
        });

        document.getElementById('declinePush').addEventListener('click', function() {
            document.getElementById('pushNotificationModal').classList.add('hidden');
        });
    </script>
</body>
</html>
