{{-- Customer Navbar Component - Fixed/Sticky Navigation --}}
<nav x-data="{ mobileMenuOpen: false, userMenuOpen: false }" 
     class="bg-white shadow-lg fixed w-full top-0 z-50 border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            {{-- Logo/Brand --}}
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center space-x-2">
                    <span class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">
                        The Paranoia
                    </span>
                </a>
            </div>

            {{-- Desktop Navigation Menu --}}
            <div class="hidden md:flex md:items-center md:space-x-8">
                <a href="{{ route('home') }}" 
                   class="text-gray-700 hover:text-purple-600 px-3 py-2 text-sm font-medium transition duration-300 ease-in-out">
                    Home
                </a>
                <a href="#about" 
                   class="text-gray-700 hover:text-purple-600 px-3 py-2 text-sm font-medium transition duration-300 ease-in-out">
                    About Us
                </a>
                <a href="#products" 
                   class="text-gray-700 hover:text-purple-600 px-3 py-2 text-sm font-medium transition duration-300 ease-in-out">
                    Product
                </a>
                <a href="#rewards" 
                   class="text-gray-700 hover:text-purple-600 px-3 py-2 text-sm font-medium transition duration-300 ease-in-out">
                    Reward
                </a>
                <a href="#blog" 
                   class="text-gray-700 hover:text-purple-600 px-3 py-2 text-sm font-medium transition duration-300 ease-in-out">
                    Blog
                </a>
                <a href="#cart" 
                   class="text-gray-700 hover:text-purple-600 px-3 py-2 text-sm font-medium transition duration-300 ease-in-out relative">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="absolute -top-1 -right-1 bg-purple-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">0</span>
                </a>
            </div>

            {{-- Desktop Auth Buttons --}}
            <div class="hidden md:flex md:items-center md:space-x-4">
                @guest
                    <a href="{{ route('login') }}" 
                       class="bg-gradient-to-r from-purple-600 to-pink-600 text-white px-6 py-2 rounded-full text-sm font-semibold hover:from-purple-700 hover:to-pink-700 transition duration-300 ease-in-out transform hover:scale-105 shadow-md">
                        Login
                    </a>
                @else
                    {{-- User Dropdown --}}
                    <div class="relative" @click.away="userMenuOpen = false">
                        <button @click="userMenuOpen = !userMenuOpen" 
                                class="flex items-center space-x-2 text-gray-700 hover:text-purple-600 focus:outline-none">
                            <div class="h-8 w-8 rounded-full bg-gradient-to-r from-purple-600 to-pink-600 flex items-center justify-center text-white font-semibold">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <span class="text-sm font-medium">{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down text-xs" :class="{ 'rotate-180': userMenuOpen }"></i>
                        </button>

                        {{-- Dropdown Menu --}}
                        <div x-show="userMenuOpen" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform scale-95"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 transform scale-100"
                             x-transition:leave-end="opacity-0 transform scale-95"
                             class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl py-2 border border-gray-200"
                             style="display: none;">
                            <a href="{{ url('/admin') }}" 
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition duration-150">
                                <i class="fas fa-user mr-2"></i> Profile
                            </a>
                            <div class="border-t border-gray-100 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" 
                                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition duration-150">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @endguest
            </div>

            {{-- Mobile Menu Button --}}
            <div class="md:hidden">
                <button @click="mobileMenuOpen = !mobileMenuOpen" 
                        type="button" 
                        class="text-gray-700 hover:text-purple-600 focus:outline-none focus:text-purple-600">
                    <i class="fas fa-bars text-xl" x-show="!mobileMenuOpen"></i>
                    <i class="fas fa-times text-xl" x-show="mobileMenuOpen" style="display: none;"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform -translate-y-4"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform -translate-y-4"
         class="md:hidden bg-white border-t border-gray-200 shadow-lg"
         style="display: none;">
        <div class="px-4 pt-2 pb-4 space-y-2">
            <a href="{{ route('home') }}" 
               class="block px-3 py-2 text-gray-700 hover:bg-purple-50 hover:text-purple-600 rounded-md transition duration-150">
                Home
            </a>
            <a href="#about" 
               class="block px-3 py-2 text-gray-700 hover:bg-purple-50 hover:text-purple-600 rounded-md transition duration-150">
                About Us
            </a>
            <a href="#products" 
               class="block px-3 py-2 text-gray-700 hover:bg-purple-50 hover:text-purple-600 rounded-md transition duration-150">
                Product
            </a>
            <a href="#rewards" 
               class="block px-3 py-2 text-gray-700 hover:bg-purple-50 hover:text-purple-600 rounded-md transition duration-150">
                Reward
            </a>
            <a href="#blog" 
               class="block px-3 py-2 text-gray-700 hover:bg-purple-50 hover:text-purple-600 rounded-md transition duration-150">
                Blog
            </a>
            <a href="#cart" 
               class="block px-3 py-2 text-gray-700 hover:bg-purple-50 hover:text-purple-600 rounded-md transition duration-150">
                <i class="fas fa-shopping-cart mr-2"></i> Cart (0)
            </a>

            {{-- Mobile Auth Section --}}
            <div class="border-t border-gray-200 mt-4 pt-4">
                @guest
                    <a href="{{ route('login') }}" 
                       class="block w-full text-center bg-gradient-to-r from-purple-600 to-pink-600 text-white px-4 py-2 rounded-full font-semibold hover:from-purple-700 hover:to-pink-700 transition duration-300">
                        Login
                    </a>
                @else
                    <div class="space-y-2">
                        <div class="px-3 py-2 text-sm text-gray-500 font-medium">
                            Logged in as {{ Auth::user()->name }}
                        </div>
                        <a href="{{ url('/admin') }}" 
                           class="block px-3 py-2 text-gray-700 hover:bg-purple-50 hover:text-purple-600 rounded-md transition duration-150">
                            <i class="fas fa-user mr-2"></i> Profile
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" 
                                    class="w-full text-left px-3 py-2 text-gray-700 hover:bg-purple-50 hover:text-purple-600 rounded-md transition duration-150">
                                <i class="fas fa-sign-out-alt mr-2"></i> Logout
                            </button>
                        </form>
                    </div>
                @endguest
            </div>
        </div>
    </div>
</nav>
