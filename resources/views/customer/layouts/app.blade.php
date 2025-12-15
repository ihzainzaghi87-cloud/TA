<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    {{-- SEO Meta Tags --}}
    <meta name="description" content="The Paranoia - Your trusted e-commerce destination for quality products and exceptional service. Shop with confidence.">
    <meta name="keywords" content="e-commerce, online shopping, products, The Paranoia">
    <meta name="author" content="The Paranoia">
    
    {{-- Open Graph Meta Tags --}}
    <meta property="og:title" content="@yield('title', 'The Paranoia - E-Commerce Platform')">
    <meta property="og:description" content="Your trusted e-commerce destination">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    
    <title>@yield('title', 'Home') - The Paranoia</title>

    <meta name="theme-color" content="#6777ef">
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
            background: #FAD470;
            border-radius: 5px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #F59E0B;
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
    
    {{-- Additional Scripts --}}
    @stack('scripts')
    
    <!-- <script src="{{ asset('/sw.js') }}"></script> -->
    <script>
    if (!navigator.serviceWorker.controller) {
        navigator.serviceWorker.register("/sw.js").then(function (reg) {
        console.log("Service worker registered: " + reg.scope);
        });
    }
    </script>
</body>
</html>
