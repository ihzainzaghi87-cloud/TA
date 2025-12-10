<nav x-data="{ mobileMenuOpen: false, userMenuOpen: false }"
     class="fixed w-full top-0 z-50">
    <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 pt-5 pb-5">
        <div class=" bg-white flex justify-between items-center h-16 border-[#FFC736] rounded-[20px] border-[2px] pr-5 pl-5 ">
            {{-- Logo/Brand --}}
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center space-x-3">
                    <img src="{{ asset('ui/logo.png') }}" alt="The Paranoia Logo" class="h-10 w-10 object-contain">
                    <span class="text-2xl font-bold bg-black bg-clip-text text-transparent">
                        The Paranoia
                    </span>
                </a>
            </div>
            {{-- Desktop Navigation Menu --}}
            <div class="hidden md:flex md:items-center md:space-x-8">
                <a href="{{ route('home') }}" 
                   class="text-gray-700 hover:text-yellow-600 px-3 py-2 text-sm font-medium transition duration-300 ease-in-out">
                    Home
                </a>
                <a href="#products" 
                   class="text-gray-700 hover:text-yellow-600 px-3 py-2 text-sm font-medium transition duration-300 ease-in-out">
                    Product
                </a>
                <a href="#about" 
                   class="text-gray-700 hover:text-yellow-600 px-3 py-2 text-sm font-medium transition duration-300 ease-in-out">
                    About Us
                </a>
                <a href="#rewards" 
                   class="text-gray-700 hover:text-yellow-600 px-3 py-2 text-sm font-medium transition duration-300 ease-in-out">
                    Reward
                </a>
                <a href="#blog" 
                   class="text-gray-700 hover:text-yellow-600 px-3 py-2 text-sm font-medium transition duration-300 ease-in-out">
                    Blog
                </a>
                
                {{-- Cart Icon with Badge --}}
                @auth
                <a href="{{ route('cart.index') }}" 
                   class="text-gray-700 hover:text-yellow-600 px-3 py-2 text-sm font-medium transition duration-300 ease-in-out relative">
                    <i class="fas fa-shopping-cart text-lg"></i>
                    @if($cartCount > 0)
                    <span class="absolute -top-1 -right-1 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center shadow-md animate-pulse" style="background: linear-gradient(135deg, #FAD470 0%, #F8B500 100%);">
                        {{ $cartCount > 99 ? '99+' : $cartCount }}
                    </span>
                    @endif
                </a>
                @else
                <a href="{{ route('login') }}" 
                   class="text-gray-700 hover:text-yellow-600 px-3 py-2 text-sm font-medium transition duration-300 ease-in-out relative">
                    <i class="fas fa-shopping-cart text-lg"></i>
                </a>
                @endauth
            </div>

            {{-- Desktop Auth Buttons --}}
            <div class="hidden md:flex md:items-center md:space-x-4">
                @guest
                    <a href="{{ route('login') }}"
                       class="px-4 py-2  text-black rounded-2xl text-sm font-bold hover:bg-gray-50 transition-colors duration-200 bg-[#FAD470]">
                        Sign In
                    </a>
                    <a href="{{ route('register') }}"
                       class="px-4 py-2  text-black rounded-2xl text-sm font-bold hover:bg-gray-50 transition-colors duration-200 bg-[#FAD470]">
                        Sign Up
                    </a>
                @else
                    {{-- User Dropdown --}}
                    <div class="relative" @click.away="userMenuOpen = false">
                        <button @click="userMenuOpen = !userMenuOpen"
                                class="flex items-center space-x-2 text-gray-700 hover:text-yellow-600 focus:outline-none">
                            <div class="h-8 w-8 rounded-full flex items-center justify-center text-white font-semibold" style="background: linear-gradient(135deg, #FAD470 0%, #F8B500 100%);">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <span class="text-sm font-medium">{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{ 'rotate-180': userMenuOpen }"></i>
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
                            <a href="{{ route('customer.index') }}" 
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-yellow-50 hover:text-yellow-600 transition duration-150">
                                <i class="fas fa-user mr-2"></i> Profil Saya
                            </a>
                            <a href="{{ route('orders.index') }}" 
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-yellow-50 hover:text-yellow-600 transition duration-150">
                                <i class="fas fa-box mr-2"></i> Pesanan Saya
                            </a>
                            <a href="{{ route('cart.index') }}" 
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-yellow-50 hover:text-yellow-600 transition duration-150">
                                <i class="fas fa-shopping-cart mr-2"></i> Keranjang
                                @if($cartCount > 0)
                                <span class="ml-1 text-xs bg-yellow-100 text-yellow-600 px-2 py-0.5 rounded-full">{{ $cartCount }}</span>
                                @endif
                            </a>
                            <div class="border-t border-gray-100 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" 
                                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 transition duration-150">
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
                        class="text-gray-700 hover:text-yellow-600 focus:outline-none focus:text-yellow-600">
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
               class="block px-3 py-2 text-gray-700 hover:bg-yellow-50 hover:text-yellow-600 rounded-md transition duration-150">
                Home
            </a>
            <a href="#about" 
               class="block px-3 py-2 text-gray-700 hover:bg-yellow-50 hover:text-yellow-600 rounded-md transition duration-150">
                About Us
            </a>
            <a href="#products" 
               class="block px-3 py-2 text-gray-700 hover:bg-yellow-50 hover:text-yellow-600 rounded-md transition duration-150">
                Product
            </a>
            <a href="#rewards" 
               class="block px-3 py-2 text-gray-700 hover:bg-yellow-50 hover:text-yellow-600 rounded-md transition duration-150">
                Reward
            </a>
            <a href="#blog" 
               class="block px-3 py-2 text-gray-700 hover:bg-yellow-50 hover:text-yellow-600 rounded-md transition duration-150">
                Blog
            </a>
            
            {{-- Mobile Cart Link --}}
            @auth
            <a href="{{ route('cart.index') }}" 
               class="block px-3 py-2 text-gray-700 hover:bg-yellow-50 hover:text-yellow-600 rounded-md transition duration-150">
                <i class="fas fa-shopping-cart mr-2"></i> Cart 
                @if($cartCount > 0)
                <span class="text-white text-xs font-bold px-2 py-0.5 rounded-full" style="background: linear-gradient(135deg, #FAD470 0%, #F8B500 100%);">
                    {{ $cartCount }}
                </span>
                @else
                <span class="text-gray-400">(0)</span>
                @endif
            </a>
            @else
            <a href="{{ route('login') }}" 
               class="block px-3 py-2 text-gray-700 hover:bg-yellow-50 hover:text-yellow-600 rounded-md transition duration-150">
                <i class="fas fa-shopping-cart mr-2"></i> Cart
            </a>
            @endauth

            {{-- Mobile Auth Section --}}
            <div class="border-t border-gray-200 mt-4 pt-4">
                @guest
                    <div class="space-y-2">
                        <a href="{{ route('login') }}"
                           class="block w-full text-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors duration-200">
                            Sign In
                        </a>
                        <a href="{{ route('register') }}"
                           class="block w-full text-center text-white px-4 py-2 rounded-lg font-medium hover:opacity-90 transition-opacity duration-200" style="background: linear-gradient(135deg, #FAD470 0%, #F8B500 100%);">
                            Sign Up
                        </a>
                    </div>
                @else
                    <div class="space-y-2">
                        <div class="px-3 py-2 text-sm text-gray-500 font-medium">
                            Logged in as {{ Auth::user()->name }}
                        </div>
                        <a href="{{ route('customer.index') }}" 
                           class="block px-3 py-2 text-gray-700 hover:bg-yellow-50 hover:text-yellow-600 rounded-md transition duration-150">
                            <i class="fas fa-user mr-2"></i> Profil Saya
                        </a>
                        <a href="{{ route('customer.orders') }}" 
                           class="block px-3 py-2 text-gray-700 hover:bg-yellow-50 hover:text-yellow-600 rounded-md transition duration-150">
                            <i class="fas fa-box mr-2"></i> Pesanan Saya
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" 
                                    class="w-full text-left px-3 py-2 text-gray-700 hover:bg-red-50 hover:text-red-600 rounded-md transition duration-150">
                                <i class="fas fa-sign-out-alt mr-2"></i> Logout
                            </button>
                        </form>
                    </div>
                @endguest
            </div>
        </div>
    </div>
</nav>
