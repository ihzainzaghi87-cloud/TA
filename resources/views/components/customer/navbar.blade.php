<nav x-data="{
    mobileMenuOpen: false,
    userMenuOpen: false,
    showNavbar: true,
    lastScrollTop: 0,
    scrollThreshold: 100
}" x-init="window.addEventListener('scroll', () => {
    let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    if (scrollTop > lastScrollTop && scrollTop > scrollThreshold) {
        showNavbar = false;
    } else {
        showNavbar = true;
    }
    lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
});" :class="showNavbar ? 'translate-y-0' : '-translate-y-full'"
    class="fixed w-full top-0 z-50 transition-transform duration-300 ease-in-out">

    <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 pt-5 pb-5">
        <div
            class="bg-white flex justify-between items-center h-16 border-[#1A1A1D] rounded-[20px] border-[2px] pr-5 pl-5 shadow-sm">

            {{-- Logo/Brand --}}
            <div class="flex-shrink-0 flex items-center">
                <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                    <img src="{{ asset('ui/logo1.png') }}" alt="The Paranoia Logo"
                        class="h-10 md:h-16 w-auto object-contain transition-transform duration-300 group-hover:scale-105">
                </a>
            </div>

            {{-- Desktop Navigation Menu --}}
            <div class="hidden md:flex md:items-center md:space-x-6">
                <a href="{{ route('home') }}"
                   class="text-gray-700 hover:text-gray-500 px-3 py-2 text-sm font-medium transition duration-300 ease-in-out">
                    Home
                </a>
                <a href="{{ route('products') }}"
                   class="text-gray-700 hover:text-gray-500 px-3 py-2 text-sm font-medium transition duration-300 ease-in-out">
                    Product
                </a>
                <a href="{{ route('about') }}"
                   class="text-gray-700 hover:text-gray-500 px-3 py-2 text-sm font-medium transition duration-300 ease-in-out">
                    About Us
                </a>
                <a href="{{ route('rewards') }}"
                   class="text-gray-700 hover:text-gray-500 px-3 py-2 text-sm font-medium transition duration-300 ease-in-out">
                    Reward
                </a>
                <a href="{{ route('articles.index') }}"
                   class="text-gray-700 hover:text-gray-500 px-3 py-2 text-sm font-medium transition duration-300 ease-in-out">
                    Blog
                </a>

                {{-- Cart Icon --}}
                @auth
                <a href="{{ route('cart.index') }}"
                   class="text-gray-700 hover:text-gray-500 px-3 py-2 text-sm font-medium transition duration-300 ease-in-out relative">
                    <i class="fas fa-shopping-cart text-lg"></i>
                    @if($cartCount > 0)
                    <span class="absolute -top-1 -right-1 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center shadow-md animate-pulse" style="background: linear-gradient(135deg, #222831 0%, #222831 100%);">
                        {{ $cartCount > 99 ? '99+' : $cartCount }}
                    </span>
                    @endif
                </a>
                @else
                <a href="{{ route('login') }}"
                   class="text-gray-700 hover:text-gray-500 px-3 py-2 text-sm font-medium transition duration-300 ease-in-out relative">
                    <i class="fas fa-shopping-cart text-lg"></i>
                </a>
                @endauth
            </div>

            {{-- Desktop Auth Buttons --}}
            <div class="hidden md:flex md:items-center md:space-x-3">
                @guest
                    <a href="{{ route('login') }}"
                        class="px-5 py-2 text-[#1A1A1D] rounded-xl text-sm font-bold border border-transparent hover:border-[#1A1A1D] hover:bg-gray-50 transition-all duration-200">
                        Login
                    </a>
                    <a href="{{ route('register') }}"
                        class="px-5 py-2 text-white rounded-xl text-sm font-bold bg-[#1A1A1D] border border-[#1A1A1D] hover:bg-white hover:text-[#1A1A1D] transition-all duration-200 shadow-md hover:shadow-none">
                        Sign Up
                    </a>
                @else
                    {{-- User Dropdown --}}
                    <div class="relative" @click.away="userMenuOpen = false">
                        <button @click="userMenuOpen = !userMenuOpen"
                                class="flex items-center space-x-2 text-gray-700 hover:text-gray-500 focus:outline-none">
                            <div class="h-8 w-8 rounded-full flex items-center justify-center text-white font-semibold" style="background: linear-gradient(135deg, #1A1A1D 0%, #374151 100%);">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <span class="text-sm font-bold max-w-[100px] truncate">{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down text-xs transition-transform duration-200"
                                :class="{ 'rotate-180': userMenuOpen }"></i>
                        </button>

                        {{-- Dropdown Menu --}}
                        <div x-show="userMenuOpen" x-cloak x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 transform scale-95"
                            x-transition:enter-end="opacity-100 transform scale-100"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 transform scale-100"
                            x-transition:leave-end="opacity-0 transform scale-95"
                            class="absolute right-0 mt-3 w-56 bg-white rounded-2xl shadow-xl py-2 border border-gray-100 overflow-hidden z-50">

                            <div class="px-4 py-3 border-b border-gray-100 bg-gray-50">
                                <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Signed in as</p>
                                <p class="text-sm font-bold text-[#1A1A1D] truncate">{{ Auth::user()->email }}</p>
                            </div>

                            <a href="{{ route('customer.index') }}"
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-500 transition duration-150">
                                <i class="fas fa-user mr-2"></i> Profil Saya
                            </a>
                            <a href="{{ route('customer.points') }}"
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-500 transition duration-150">
                                <i class="fas fa-coins mr-2"></i> Poin Saya
                            </a>
                            <a href="{{ route('customer.orders') }}"
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-500 transition duration-150">
                                <i class="fas fa-box mr-2"></i> Pesanan Saya
                            </a>
                            <a href="{{ route('cart.index') }}"
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-500 transition duration-150">
                                <i class="fas fa-shopping-cart mr-2"></i> Keranjang
                                @if($cartCount > 0)
                                <span class="ml-1 text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">{{ $cartCount }}</span>
                                @endif
                            </a>

                            <div class="border-t border-gray-100 my-1"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 font-medium transition-colors">
                                    <i class="fas fa-sign-out-alt mr-2 w-5 text-center"></i> Logout
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
                        class="text-gray-700 hover:text-gray-500 focus:outline-none focus:text-gray-500">
                    <i class="fas fa-bars text-xl" x-show="!mobileMenuOpen"></i>
                    <i class="fas fa-times text-xl" x-show="mobileMenuOpen" x-cloak></i>
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
               class="block px-3 py-2 text-gray-700 hover:bg-gray-100 hover:text-gray-500 rounded-md transition duration-150">
                Home
            </a>
            <a href="{{ route('about') }}"
               class="block px-3 py-2 text-gray-700 hover:bg-gray-100 hover:text-gray-500 rounded-md transition duration-150">
                About Us
            </a>
            <a href="{{ route('products') }}"
               class="block px-3 py-2 text-gray-700 hover:bg-gray-100 hover:text-gray-500 rounded-md transition duration-150">
                Product
            </a>
            <a href="{{ route('rewards') }}"
               class="block px-3 py-2 text-gray-700 hover:bg-gray-100 hover:text-gray-500 rounded-md transition duration-150">
                Reward
            </a>
            <a href="{{ route('articles.index') }}"
               class="block px-3 py-2 text-gray-700 hover:bg-gray-100 hover:text-gray-500 rounded-md transition duration-150">
                Blog
            </a>

            {{-- Mobile Cart Link --}}
            @auth
            <a href="{{ route('cart.index') }}"
               class="block px-3 py-2 text-gray-700 hover:bg-gray-100 hover:text-gray-500 rounded-md transition duration-150">
                <i class="fas fa-shopping-cart mr-2"></i> Cart
                @if($cartCount > 0)
                <span class="text-white text-xs font-bold px-2 py-0.5 rounded-full" style="background: linear-gradient(135deg, #E5E7EB 0%, #374151 100%);">
                    {{ $cartCount }}
                </span>
                @else
                <span class="text-gray-400">(0)</span>
                @endif
            </a>
            @else
            <a href="{{ route('login') }}"
               class="block px-3 py-2 text-gray-700 hover:bg-gray-100 hover:text-gray-500 rounded-md transition duration-150">
                <i class="fas fa-shopping-cart mr-2"></i> Cart
            </a>
            @endauth
            <div class="border-t border-gray-100 pt-4">
                @guest
                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ route('login') }}"
                            class="block w-full text-center px-4 py-3 border border-gray-300 text-gray-700 rounded-xl font-bold hover:bg-gray-50 transition">
                            Login
                        </a>
                        <a href="{{ route('register') }}"
                           class="block w-full text-center text-white px-4 py-2 rounded-lg font-medium hover:opacity-90 transition-opacity duration-200" style="background: linear-gradient(135deg, #E5E7EB 0%, #374151 100%);">
                            Sign Up
                        </a>
                    </div>
                @else
                    <div class="space-y-1">
                        <div class="px-4 py-2 flex items-center gap-3 mb-2">
                            <div
                                class="h-8 w-8 rounded-full bg-[#1A1A1D] text-white flex items-center justify-center font-bold text-sm">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-[#1A1A1D]">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                            </div>
                        </div>
                        <a href="{{ route('customer.index') }}"
                           class="block px-3 py-2 text-gray-700 hover:bg-gray-100 hover:text-gray-500 rounded-md transition duration-150">
                            <i class="fas fa-user mr-2"></i> Profil Saya
                        </a>
                        <a href="{{ route('customer.points') }}"
                           class="block px-3 py-2 text-gray-700 hover:bg-gray-100 hover:text-gray-500 rounded-md transition duration-150">
                            <i class="fas fa-coins mr-2"></i> Poin Saya
                        </a>
                        <a href="{{ route('customer.orders') }}"
                           class="block px-3 py-2 text-gray-700 hover:bg-gray-100 hover:text-gray-500 rounded-md transition duration-150">
                            <i class="fas fa-box mr-2"></i> Pesanan Saya
                        </a>
                        <a href="{{ route('cart.index') }}"
                            class="block px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-[#1A1A1D] rounded-xl font-medium transition">
                            <i class="fas fa-shopping-cart mr-3 w-5 text-center"></i> Cart ({{ $cartCount }})
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="pt-2">
                            @csrf
                            <button type="submit"
                                class="w-full text-left px-4 py-3 text-red-600 bg-red-50 hover:bg-red-100 rounded-xl font-bold transition">
                                <i class="fas fa-sign-out-alt mr-3 w-5 text-center"></i> Logout
                            </button>
                        </form>
                    </div>
                @endguest
            </div>
        </div>
    </div>
</nav>
