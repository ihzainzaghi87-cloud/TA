<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - The Paranoia</title>

    <!-- Google Fonts - Poppins & Bebas Neue -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&family=Bebas+Neue&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" 
          integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" 
          crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] {
            display: none !important;
        }

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

        /* Hide password reveal button on Edge/IE */
        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear {
            display: none;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #FAD470;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #F59E0B;
        }

        /* Animation */
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
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        .animate-float {
            animation: float 3s ease-in-out infinite;
        }
    </style>
</head>
<body class="antialiased bg-gray-50 font-['Poppins'] overflow-x-hidden min-h-screen">
    <div class="min-h-screen flex">
        <!-- Left Side - Branding Section -->
        <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden" style="background-color: #FAD470;">
            <!-- Decorative Elements -->
            <div class="absolute top-10 left-10 w-32 h-32 bg-yellow-300/30 rounded-full blur-2xl"></div>
            <div class="absolute bottom-20 right-10 w-40 h-40 bg-yellow-500/20 rounded-full blur-3xl"></div>
            <div class="absolute top-1/3 right-20 w-24 h-24 bg-white/10 rounded-full blur-xl"></div>
            
            <!-- Content -->
            <div class="relative z-10 flex flex-col justify-center items-center w-full px-12">
                <!-- Logo/Brand -->
                <div class="text-center mb-8 animate-float">
                    <div class="w-24 h-24 bg-black rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-2xl transform hover:scale-105 transition-transform duration-300">
                        <span class="text-[#FAD470] font-bebas text-4xl">TP</span>
                    </div>
                    <h1 class="font-bebas text-6xl text-black mb-2">THE PARANOIA</h1>
                    <p class="text-black/70 text-lg font-medium">Premium Fashion Store</p>
                </div>

                <!-- Tagline -->
                <div class="text-center max-w-md">
                    <h2 class="text-3xl font-bold text-black mb-4">
                        Wear the trend, own the moment
                    </h2>
                    <p class="text-black/60 text-base leading-relaxed">
                        Discover the latest fashion trends and elevate your style with our exclusive collection.
                    </p>
                </div>
            </div>

            <!-- Bottom Decorative Pattern -->
            <div class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-black/5 to-transparent"></div>
        </div>

        <!-- Right Side - Form Section -->
        <div class="w-full lg:w-1/2 flex items-center justify-center px-6 py-12 bg-white">
            <div class="w-full max-w-md fade-in">
                <!-- Mobile Logo (visible only on mobile) -->
                <div class="lg:hidden text-center mb-8">
                    <div class="w-16 h-16 bg-[#FAD470] rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <span class="text-black font-bebas text-2xl">TP</span>
                    </div>
                    <h1 class="font-bebas text-3xl text-gray-900">THE PARANOIA</h1>
                </div>

                <!-- Session Messages -->
                @if (session('success'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-2xl">
                        <div class="flex items-start">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                <i class="fas fa-check text-green-600"></i>
                            </div>
                            <p class="text-sm text-green-800 pt-1">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-2xl">
                        <div class="flex items-start">
                            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                <i class="fas fa-exclamation text-red-600"></i>
                            </div>
                            <p class="text-sm text-red-800 pt-1">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                <!-- Form Content -->
                @yield('content')

                <!-- Footer -->
                <div class="mt-8 text-center">
                    <p class="text-xs text-gray-400">
                        © {{ date('Y') }} The Paranoia. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>