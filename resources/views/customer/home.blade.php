@extends('customer.layouts.app')

@section('title', 'The Paranoia')

@push('styles')
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&family=Bebas+Neue&display=swap"
        rel="stylesheet">
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

        /* Animation for floating elements */
        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        .animate-bounce-slow {
            animation: float 6s ease-in-out infinite;
        }
    </style>
@endpush

@section('content')
    {{-- Display Flash Messages --}}
    @if (session('success'))
        <div class="fixed top-20 right-4 z-50 max-w-md">
            <x-alert type="success" :message="session('success')" />
        </div>
    @endif

    @if (session('error'))
        <div class="fixed top-20 right-4 z-50 max-w-md">
            <x-alert type="error" :message="session('error')" />
        </div>
    @endif

    @if ($errors->any())
        <div class="fixed top-20 right-4 z-50 max-w-md">
            <x-alert type="error" :message="$errors->first()" />
        </div>
    @endif

    {{-- Hero Banner Section --}}
    <section class="w-full px-4 md:px-8">
        @if($banners && $banners->count() > 0)
            {{-- Banner Slider --}}
            <div class="relative w-full my-8 md:my-12 rounded-[2.5rem] overflow-hidden min-h-[550px] md:min-h-[650px] select-none"
                x-data="{ 
                    current: 0, 
                    total: {{ $banners->count() }},
                    autoplay: null,
                    startX: 0,
                    isDragging: false,
                    start() {
                        clearInterval(this.autoplay);
                        this.autoplay = setInterval(() => this.next(), 5000);
                    },
                    stop() { clearInterval(this.autoplay); },
                    next() { this.current = (this.current + 1) % this.total; },
                    prev() { this.current = (this.current - 1 + this.total) % this.total; },
                    handleTouchStart(e) {
                        e.preventDefault(); // Prevent default drag behavior
                        this.startX = e.touches ? e.touches[0].clientX : e.clientX;
                        this.isDragging = true;
                        this.stop();
                    },
                    handleTouchEnd(e) {
                        if (!this.isDragging) return;
                        e.preventDefault();
                        const endX = e.changedTouches ? e.changedTouches[0].clientX : e.clientX;
                        const diff = this.startX - endX;
                        if (Math.abs(diff) > 50) { // Threshold for swipe
                            if (diff > 0) this.next();
                            else this.prev();
                        }
                        this.isDragging = false;
                        this.start();
                    }
                }" 
                x-init="start()" 
                @mouseenter="stop()" 
                @mouseleave="start(); handleTouchEnd($event)" // Handle mouse leave during drag
                @touchstart="handleTouchStart($event)"
                @touchend="handleTouchEnd($event)"
                @mousedown="handleTouchStart($event)"
                @mouseup="handleTouchEnd($event)">
                :class="isDragging ? 'cursor-grabbing select-none' : 'cursor-grab'">
                
                {{-- Banner Slides --}}
                @foreach($banners as $index => $banner)
                    <div class="absolute inset-0 transition-opacity duration-700 pointer-events-none"
                        x-show="current === {{ $index }}"
                        x-transition:enter="transition ease-out duration-500"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        x-transition:leave="transition ease-in duration-500"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0">
                        {{-- Background Image --}}
                        @if($banner->image_mobile)
                            <img src="{{ Storage::url($banner->image_mobile) }}" 
                                alt="{{ $banner->title ?? 'Banner' }}"
                                class="w-full h-full object-cover pointer-events-none md:hidden">
                        @endif
                        <img src="{{ Storage::url($banner->image) }}" 
                            alt="{{ $banner->title ?? 'Banner' }}"
                            class="w-full h-full object-cover pointer-events-none {{ $banner->image_mobile ? 'hidden md:block' : '' }}">
                        {{-- Overlay --}}
                        <div class="absolute inset-0 bg-black/30"></div>
                    </div>
                @endforeach

                {{-- Content from Banner (Dynamic per banner) --}}
                @foreach($banners as $index => $banner)
                    <div class="absolute inset-0 flex items-center justify-center z-10 pointer-events-none"
                         x-show="current === {{ $index }}"
                         x-transition:enter="transition ease-out duration-500 delay-100"
                         x-transition:enter-start="opacity-0 transform translate-y-4"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         x-transition:leave="transition ease-in duration-300"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0">
                        <div class="text-center px-6 max-w-4xl">
                            @if($banner->title || $banner->subtitle)
                                <h1 class="text-2xl md:text-3xl lg:text-5xl font-black text-white leading-[1.1] mb-8 drop-shadow-lg">
                                    {{ $banner->title }}
                                </h1>
                                @if($banner->subtitle)
                                    <p class="text-sm md:text-lg lg:text-2xl font-medium text-white leading-[1.1] mb-8 drop-shadow-lg">
                                        {{ $banner->subtitle }}
                                    </p>
                                @endif
                            @else
                                <span class="text-gray-300 text-xs md:text-sm font-bold tracking-[0.2em] uppercase mb-5 block">
                                    New Season Highlight
                                </span>
                                <h1 class="text-5xl md:text-6xl lg:text-7xl font-black text-white leading-[1.1] mb-8">
                                    Wear the trend, <br>
                                    own the moment
                                </h1>
                                <p class="text-gray-300 text-base md:text-lg mb-10 max-w-lg mx-auto leading-relaxed">
                                    Discover curated fashion that defines your style — effortless, bold, and always on trend.
                                </p>
                            @endif
                            <div class="flex flex-col sm:flex-row gap-5 justify-center pointer-events-auto">
                                <a href="{{ route('products') }}"
                                    class="inline-flex justify-center items-center 
                                            bg-transparent border border-white text-white
                                            md:bg-white md:text-black md:border-0
                                            px-9 py-4 rounded-full font-bold text-base
                                            hover:bg-white/10 md:hover:bg-gray-200
                                            transition-all duration-300 transform hover:scale-105 shadow-lg">
                                    Explore Collection
                                </a>
                                <a href="{{ route('articles.index') }}"
                                    class="hidden md:block inline-flex justify-center items-center px-9 py-4 rounded-full font-bold text-base border border-white text-white hover:border-white hover:bg-white/10 transition-all duration-300">
                                    Discover More
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach

                @if($banners->count() > 1)
                    {{-- Dots Indicator --}}
                    <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex items-center gap-2 z-20 pointer-events-auto">
                        @foreach($banners as $index => $banner)
                            <button @click="current = {{ $index }}; stop(); start();"
                                    class="h-2 rounded-full transition-all duration-300"
                                    :class="current === {{ $index }} ? 'bg-white w-8' : 'bg-white/50 w-2 hover:bg-white/70'">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>
        @else
            {{-- Default Hero --}}
            <div class="bg-[#1A1A1D] rounded-[2.5rem] overflow-hidden min-h-[220px] md:min-h-[450px] relative w-full my-8 md:my-12 flex items-stretch">
                <div class="container mx-auto px-6 lg:px-16 relative z-10 w-full">
                    <div class="grid grid-cols-1 lg:grid-cols-2 h-full items-stretch">
                        <div class="py-16 lg:py-0 text-center lg:text-left self-center">
                            <span class="text-gray-400 text-xs md:text-sm font-bold tracking-[0.2em] uppercase mb-5 block">
                                New Season Highlight
                            </span>
                            <h1 class="text-5xl md:text-6xl lg:text-7xl font-black text-white leading-[1.1] mb-8">
                                Wear the trend, <br>
                                own the moment
                            </h1>
                            <p class="text-gray-400 text-base md:text-lg mb-10 max-w-lg mx-auto lg:mx-0 leading-relaxed">
                                Discover curated fashion that defines your style — effortless, bold, and always on trend.
                            </p>
                            <div class="flex flex-col sm:flex-row gap-5 justify-center lg:justify-start">
                                <a href="{{ route('products') }}"
                                    class="inline-flex justify-center items-center 
                                            bg-transparent border border-white text-white
                                            md:bg-white md:text-black md:border-0
                                            px-9 py-4 rounded-full font-bold text-base
                                            hover:bg-white/10 md:hover:bg-gray-200
                                            transition-all duration-300 transform hover:scale-105 shadow-lg">
                                    Explore Collection
                                </a>
                                <a href="{{ route('articles.index') }}"
                                    class="hidden md:block inline-flex justify-center items-center px-9 py-4 rounded-full font-bold text-base border border-gray-600 text-white hover:border-white hover:bg-white/10 transition-all duration-300">
                                    Discover More
                                </a>
                            </div>
                        </div>
                        <div class="relative w-full h-full min-h-[10px] md:min-h-[350px] lg:min-h-full flex items-end justify-center lg:justify-end pb-0">
                            <div class="relative w-full max-w-[550px] flex items-end justify-center">
                                <img src="{{ asset('ui/hero.png') }}" alt="Fashion Model"
                                    class="hidden md:block w-auto h-auto max-h-[500px] md:max-h-[650px] object-contain object-bottom relative z-10 drop-shadow-2xl block">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </section>

    {{-- Brand Carousel Section --}}
    <section class="py-4 overflow-hidden">
        <div class="relative">
            <div class="flex items-center overflow-hidden">
                {{-- First Set --}}
                <div class="flex items-center space-x-24 animate-scroll">
                    @for ($i = 0; $i < 10; $i++)
                        <span
                            class="text-3xl md:text-4xl font-bold tracking-widest uppercase text-black opacity-80 hover:opacity-100 transition-opacity duration-300 whitespace-nowrap leading-none">
                            THE PARANOIA
                        </span>
                    @endfor
                </div>
                {{-- Spacer --}}
                <div class="w-24 flex-shrink-0"></div>
                {{-- Duplicate Set --}}
                <div class="flex items-center space-x-24 animate-scroll">
                    @for ($i = 0; $i < 10; $i++)
                        <span
                            class="text-3xl md:text-4xl font-bold tracking-widest uppercase text-black opacity-80 hover:opacity-100 transition-opacity duration-300 whitespace-nowrap leading-none">
                            THE PARANOIA
                        </span>
                    @endfor
                </div>
            </div>
        </div>
    </section>

    <style>
        @keyframes scroll {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-50%);
            }
        }

        .animate-scroll {
            animation: scroll 20s linear infinite;
        }

        .flex:hover .animate-scroll {
            animation-play-state: paused;
        }
    </style>

    {{-- Categories Section --}}
    <section id="categories" class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <a href="{{ route('products', ['sort' => 'bestseller']) }}" class="block">
                    <div class="relative rounded-3xl shadow-md cursor-pointer overflow-hidden group w-full"
                        style="height: 0; padding-bottom: 108.69%; max-width: 564px; aspect-ratio: 564/600;">
                        <div class="absolute inset-0 bg-cover bg-center"
                            style="background-image: url('{{ asset('ui/main1.jpg') }}');">
                            <div
                                class="absolute inset-0 bg-black/40 group-hover:bg-black/50 transition-colors duration-300">
                            </div>
                        </div>
                        <div class="absolute inset-0 flex items-center justify-center p-8">
                            <div class="text-center">
                                <h3 class="font-bold text-white text-xl md:text-2xl drop-shadow-lg">CHECK OUR BEST
                                </h3>
                                <h3 class="font-bold text-white text-xl md:text-2xl mb-4 drop-shadow-lg">SELLER COLLECTION
                                </h3>
                                <span
                                    class="bg-white font-bold text-black px-6 py-2 rounded-full text-sm hover:bg-gray-200 transition-colors duration-200">
                                    View More
                                </span>
                            </div>
                        </div>
                    </div>
                </a>

                <a href="{{ route('products') }}" class="block">
                    <div class="relative rounded-3xl shadow-md cursor-pointer overflow-hidden group w-full"
                        style="height: 0; padding-bottom: 108.69%; max-width: 564px; aspect-ratio: 564/600;">
                        <div class="absolute inset-0 bg-cover bg-center"
                            style="background-image: url('{{ asset('ui/main2.jpg') }}');">
                            <div
                                class="absolute inset-0 bg-black/40 group-hover:bg-black/50 transition-colors duration-300">
                            </div>
                        </div>
                        <div class="absolute inset-0 flex items-center justify-center p-8">
                            <div class="text-center">
                                <h3 class="font-bold text-white text-xl md:text-2xl mb-4 drop-shadow-lg">NEW ARRIVALS</h3>
                                <span
                                    class="bg-white font-bold text-black px-6 py-2 rounded-full text-sm hover:bg-gray-200 transition-colors duration-200">
                                    View More
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </section>

    {{-- Bottom Full Width Section --}}
    <section class="w-full">
        <div class="min-h-80 flex items-center justify-center bg-[#1A1A1D]">
            <div class="text-center text-white px-4">
                <h2 class="font-bebas text-4xl md:text-6xl mb-4 text-white">
                    UP TO 60% OFF ONLINE & IN-STORE
                </h2>
                <p class="text-xl md:text-2xl mb-8 text-gray-300">
                    Further markdowns for our biggest sale
                </p>
                <a href="{{ route('products') }}"
                    class="bg-white text-black px-8 py-4 rounded-[2vw] font-semibold hover:bg-gray-200 transition-colors duration-200">
                    Get Started
                </a>
            </div>
        </div>
    </section>

    {{-- Browse Product Category --}}
    <section id="products" class="py-12 md:py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 md:mb-12">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-black">
                    Browse Product <br class="hidden sm:block" />By Category
                </h2>
                <a href="{{ route('products') }}" 
                class="inline-block bg-black text-white px-6 md:px-8 py-2.5 md:py-3 rounded-full font-bold hover:bg-gray-800 transition duration-300 transform hover:scale-105 shadow-lg text-sm md:text-base">
                    View All
                </a>
            </div>

            @php
                $categoryIcons = [
                    't-shirt' => 'fa-tshirt',
                    'tshirt' => 'fa-tshirt',
                    'kaos' => 'fa-tshirt',
                    'shirt' => 'fa-tshirt',
                    'jacket' => 'fa-vest',
                    'jaket' => 'fa-vest',
                    'hoodie' => 'fa-vest',
                    'pants' => 'fa-socks',
                    'celana' => 'fa-socks',
                    'jeans' => 'fa-socks',
                    'hat' => 'fa-hat-cowboy',
                    'topi' => 'fa-hat-cowboy',
                    'cap' => 'fa-hat-cowboy',
                    'bag' => 'fa-shopping-bag',
                    'tas' => 'fa-shopping-bag',
                    'accessories' => 'fa-gem',
                    'aksesoris' => 'fa-gem',
                    'shoes' => 'fa-shoe-prints',
                    'sepatu' => 'fa-shoe-prints',
                ];

                $hoverColors = ['hover:bg-gray-800', 'hover:bg-gray-700', 'hover:bg-gray-900'];
            @endphp

            <!-- Swiper Container for Categories -->
            <div class="swiper categorySwiper pb-12">
                <div class="swiper-wrapper">
                    @foreach ($categories->take(12) as $index => $category)
                        @php
                            $iconKey = strtolower($category->slug ?? $category->name);
                            $icon = 'fa-tag';
                            foreach ($categoryIcons as $key => $value) {
                                if (str_contains($iconKey, $key)) {
                                    $icon = $value;
                                    break;
                                }
                            }
                            $hoverColor = $hoverColors[$index % count($hoverColors)];
                        @endphp
                        
                        <div class="swiper-slide">
                            <a href="{{ route('products', ['category' => $category->id]) }}" 
                            class="group bg-white border-2 border-gray-100 rounded-2xl md:rounded-3xl shadow-sm hover:shadow-md hover:border-black transition-all duration-300 overflow-hidden flex items-center p-3 md:p-4 h-full">
                                
                                <div class="w-10 h-10 md:w-12 md:h-12 bg-black rounded-full flex items-center justify-center {{ $hoverColor }} transition-colors flex-shrink-0">
                                    <i class="fas {{ $icon }} text-white text-lg md:text-xl"></i>
                                </div>
                                
                                <div class="flex-1 pl-3 md:pl-4 min-w-0">
                                    <h3 class="text-sm md:text-base font-semibold text-gray-900 truncate">{{ $category->name }}</h3>
                                    <p class="text-xs text-gray-500">{{ $category->products_count ?? 0 }} Products</p>
                                </div>
                                
                                <div class="flex-shrink-0 ml-2">
                                    <i class="fas fa-chevron-right text-gray-400 group-hover:text-black transition-colors text-sm"></i>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- Category Navigation Bar & Catalog Section --}}
    <section class="pt-8 pb-8 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 md:mb-8 text-left">
                <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-black text-black mb-2 md:mb-4">PRODUCT</h1>
                <p class="text-sm sm:text-base md:text-lg lg:text-xl text-gray-600 max-w-4xl">
                    Redefine your wardrobe with fashion that's chic, versatile, and uniquely you
                </p>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-5">
            <!-- Swiper Container -->
            <div class="swiper productSwiper pb-12">
                <div class="swiper-wrapper">
                    @foreach ($popularProducts as $product)
                    <div class="swiper-slide">
                        <div class="group flex flex-col gap-3 border border-gray-200 bg-white rounded-[30px] p-4 hover:shadow-2xl transition-all duration-300 shadow-sm h-full">
                            
                            <a href="{{ route('product.detail', $product->slug) }}"
                                class="block relative w-full bg-[#f4f4f4] rounded-[20px] md:rounded-[30px] overflow-hidden aspect-[4/3] shadow-sm flex items-center justify-center">
                                
                                @if ($product->images && $product->images->count() > 0)
                                    <img src="{{ asset('storage/products/' . $product->images->first()->image) }}" 
                                        alt="{{ $product->name }}"
                                        class="w-full h-full object-cover p-0 group-hover:scale-110 transition-transform duration-500 mix-blend-multiply">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        <i class="fas fa-image text-3xl"></i>
                                    </div>
                                @endif
                            </a>

                            <div class="flex justify-between items-start px-1">
                                <div class="flex flex-col gap-1 pr-2">
                                    <h3 class="text-sm md:text-base font-bold text-[#0c0c25] leading-tight group-hover:text-yellow-600 transition-colors line-clamp-2">
                                        {{ $product->name }}
                                    </h3>
                                    <span class="text-xs text-gray-500">{{ $product->category->name ?? 'Gaya Hidup' }}</span>
                                </div>
                                <div class="flex-shrink-0">
                                    <p class="text-xs sm:text-sm md:text-base font-bold text-[#0c0c25] whitespace-nowrap">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>

                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="text-center mt-6 md:mt-8">
                <a href="{{ route('products') }}"
                class="inline-block bg-black text-white px-6 md:px-8 py-2.5 md:py-3 rounded-full font-semibold hover:bg-gray-800 transition duration-300 transform hover:scale-105 shadow-lg text-sm md:text-base">
                    View All Products
                </a>
            </div>
        </div>
    </section>

@php
    $productsData = $popularProducts
        ->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'price' => $product->price,
                'price_formatted' => number_format($product->price, 0, ',', '.'),
                'category_id' => $product->category_id,
                'category_name' => $product->category->name ?? 'Product',
                'image' =>
                    $product->images->count() > 0 ? asset('storage/products/' . $product->images->first()->image) : null,
            ];
        })
        ->values();
@endphp

<script>
    function productFilter() {
        return {
            activeCategory: 'all',
            isLoading: false,
            allProducts: @json($productsData),
            get filteredProducts() {
                if (this.activeCategory === 'all') {
                    return this.allProducts;
                }
                return this.allProducts.filter(product => product.category_id == this.activeCategory);
            },
            filterByCategory(categoryId) {
                this.isLoading = true;
                this.activeCategory = categoryId;
                setTimeout(() => {
                    this.isLoading = false;
                }, 200);
            }
        }
    }
</script>

{{-- About Section --}}
<section id="about" class="py-12 md:py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 md:mb-16">
            <div>
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-black mb-2">
                    ABOUT OUR BRAND
                </h2>
                <p class="text-sm md:text-lg text-gray-600 max-w-3xl">
                   About Us: Our Brand Story
                </p>
            </div>
            <a href="#" class="bg-black text-white px-4 md:px-6 py-2 md:py-3 rounded-full font-semibold hover:bg-gray-800 transition duration-300 transform hover:scale-105 shadow-lg text-sm md:text-base">
                View More
            </a>
        </div>

        <div class="relative mb-8 md:mb-16 -mx-4 md:-mx-8 lg:-mx-16">
            <img src="{{ asset('about-us.jpg') }}"
                 alt="About Us"
                 class="w-full h-[250px] sm:h-[350px] md:h-[400px] lg:max-h-[500px] object-cover grayscale hover:grayscale-0 transition duration-500">

            <div class="hidden md:block absolute top-8 right-8 lg:right-16 bg-white p-4 md:p-6 rounded-lg shadow-lg max-w-sm lg:max-w-md border border-gray-100">
                <h3 class="text-xl md:text-2xl font-bold text-black mb-3 md:mb-4">
                    Our Story
                </h3>
                <p class="text-gray-600 mb-3 md:mb-4 leading-relaxed text-sm md:text-base">
                    Founded with a vision to revolutionize the fashion industry, we started as a small boutique with big dreams. Today, we're proud to be one of the most trusted names in online fashion retail.
                            </p>
                            <p class="text-gray-600 leading-relaxed text-sm md:text-base">
                                Our commitment to quality, style, and customer satisfaction has helped us build a community of fashion lovers who trust us for their wardrobe needs.
                            </p>
                        </div>
                    </div>

                    <div class="md:hidden bg-white p-4 rounded-lg shadow-sm -mt-4">
                        <h3 class="text-lg font-bold text-black mb-3">
                            Our Story
                        </h3>
                        <p class="text-gray-600 mb-3 leading-relaxed text-sm">
                            Founded with a vision to revolutionize the fashion industry, we started as a small boutique with big dreams. Today, we're proud to be one of the most trusted names in online fashion retail.
                        </p>
                        <p class="text-gray-600 leading-relaxed text-sm">
                            Our commitment to quality, style, and customer satisfaction has helped us build a community of fashion lovers.
                        </p>
                    </div>
                </div>
            </section>

    {{-- Rewards Section --}}
    <section class="py-12 md:py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 md:mb-12">
                <div>
                    <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-black mb-2">REWARD</h2>
                    <p class="text-sm md:text-lg text-gray-600 max-w-3xl">
                        Redeem your points for exclusive reward products!
                    </p>
                </div>
                <a href="{{ route('rewards') }}"
                class="bg-black text-white px-4 md:px-6 py-2 md:py-3 rounded-full font-semibold hover:bg-gray-800 transition duration-300 transform hover:scale-105 shadow-lg text-sm md:text-base">
                    View All
                </a>
            </div>

            @if ($rewardProducts->count() > 0)
                <!-- Swiper Container for Rewards -->
                <div class="swiper rewardSwiper pb-12">
                    <div class="swiper-wrapper">
                        @foreach ($rewardProducts as $product)
                        <div class="swiper-slide">
                            <div class="group flex flex-col gap-3 border border-gray-200 bg-white rounded-[30px] p-4 hover:shadow-2xl transition-all duration-300 shadow-sm h-full">
                                
                                <a href="{{ route('reward.detail', $product->slug) }}"
                                    class="block relative w-full bg-[#f4f4f4] rounded-[20px] md:rounded-[30px] overflow-hidden aspect-[4/3] shadow-sm flex items-center justify-center">
                                    
                                    @if ($product->images->count() > 0)
                                        <img src="{{ asset('storage/products/' . $product->images->first()->image) }}" 
                                            alt="{{ $product->name }}"
                                            class="w-full h-full object-cover p-0 group-hover:scale-110 transition-transform duration-500 mix-blend-multiply">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                                            <i class="fas fa-gift text-3xl"></i>
                                        </div>
                                    @endif

                                    <!-- Reward Badge -->
                                    <div class="absolute top-3 left-3 bg-black text-white font-bold text-[10px] md:text-xs px-2.5 py-1 rounded-full z-10 shadow-sm">
                                        <i class="fas fa-star mr-1 text-yellow-400"></i>Reward
                                    </div>
                                </a>

                                <div class="flex justify-between items-start px-1">
                                    <div class="flex flex-col gap-1 pr-2">
                                        <h3 class="text-sm md:text-base font-bold text-[#0c0c25] leading-tight group-hover:text-yellow-600 transition-colors line-clamp-2">
                                            {{ $product->name }}
                                        </h3>
                                        <span class="text-xs text-gray-500">{{ $product->category->name ?? 'Reward' }}</span>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <p class="text-xs sm:text-sm md:text-base font-bold text-[#0c0c25] whitespace-nowrap flex items-center">
                                            <i class="fas fa-coins mr-1 text-yellow-500 text-xs"></i>
                                            {{ number_format($product->point_price ?? 0, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>

                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-gift text-gray-300 text-5xl mb-4"></i>
                    <p class="text-gray-500">Belum ada produk reward tersedia</p>
                </div>
            @endif
        </div>
    </section>

    {{-- Style Inspiration Section --}}
    <section id="style-inspiration" class="py-12 md:py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 md:mb-12">
                <div>
                    <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-black mb-2">
                        STYLE INSPIRATION
                    </h2>
                    <p class="text-gray-600 text-sm md:text-base">Get inspired by our latest fashion lookbook</p>
                </div>
                <a href="{{ route('products') }}"
                    class="bg-black text-white px-6 py-2.5 md:py-3 rounded-full font-semibold hover:bg-gray-800 transition duration-300 transform hover:scale-105 shadow-lg text-sm md:text-base">
                    Explore All
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                <a href="{{ route('products') }}"
                    class="group relative overflow-hidden rounded-2xl md:rounded-3xl shadow-lg aspect-[4/5]">
                    <img src="{{ asset('ui/casual-style.jpg') }}" alt="Casual Everyday"
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-4 md:p-6">
                        <span
                            class="inline-block bg-white text-black text-xs font-bold px-3 py-1 rounded-full mb-2 md:mb-3">TRENDING</span>
                        <h3 class="text-xl md:text-2xl font-bold text-white mb-1 md:mb-2">Casual Everyday</h3>
                        <p class="text-white/80 text-xs md:text-sm line-clamp-2">Effortless style for your daily adventures
                        </p>
                    </div>
                </a>

                <a href="{{ route('products') }}"
                    class="group relative overflow-hidden rounded-2xl md:rounded-3xl shadow-lg aspect-[4/5]">
                    <img src="{{ asset('ui/street-style.jpg') }}" alt="Street Style"
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-4 md:p-6">
                        <span
                            class="inline-block bg-black text-white text-xs font-bold px-3 py-1 rounded-full mb-2 md:mb-3">NEW
                            ARRIVAL</span>
                        <h3 class="text-xl md:text-2xl font-bold text-white mb-1 md:mb-2">Street Style</h3>
                        <p class="text-white/80 text-xs md:text-sm line-clamp-2">Bold looks that make a statement</p>
                    </div>
                </a>

                <a href="{{ route('products') }}"
                    class="group relative overflow-hidden rounded-2xl md:rounded-3xl shadow-lg aspect-[4/5] md:col-span-2 lg:col-span-1">
                    <img src="{{ asset('ui/minimalist-style.jpg') }}" alt="Minimalist Essentials"
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-4 md:p-6">
                        <span
                            class="inline-block bg-gray-800 text-white text-xs font-bold px-3 py-1 rounded-full mb-2 md:mb-3">BESTSELLER</span>
                        <h3 class="text-xl md:text-2xl font-bold text-white mb-1 md:mb-2">Minimalist Essentials</h3>
                        <p class="text-white/80 text-xs md:text-sm line-clamp-2">Timeless pieces for a polished look</p>
                    </div>
                </a>
            </div>
        </div>
    </section>

@push('styles')
<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

<style>
/* Custom Pagination */
.productSwiper .swiper-pagination {
    bottom: 0 !important;
}

.productSwiper .swiper-pagination-bullet {
    width: 10px;
    height: 10px;
    background: #d1d5db;
    opacity: 1;
    transition: all 0.3s ease;
}

.productSwiper .swiper-pagination-bullet-active {
    background: #000;
    width: 24px;
    border-radius: 5px;
}

/* Smooth cursor for dragging */
.productSwiper {
    cursor: grab;
}

.productSwiper:active {
    cursor: grabbing;
}

/* Custom Pagination for Reward Swiper */
.rewardSwiper .swiper-pagination {
    bottom: 0 !important;
}

.rewardSwiper .swiper-pagination-bullet {
    width: 10px;
    height: 10px;
    background: #d1d5db;
    opacity: 1;
    transition: all 0.3s ease;
}

.rewardSwiper .swiper-pagination-bullet-active {
    background: #000;
    width: 24px;
    border-radius: 5px;
}

/* Smooth cursor for dragging */
.rewardSwiper {
    cursor: grab;
}

.rewardSwiper:active {
    cursor: grabbing;
}

/* Custom Pagination for Category Swiper */
.categorySwiper .swiper-pagination {
    bottom: 0 !important;
}

.categorySwiper .swiper-pagination-bullet {
    width: 10px;
    height: 10px;
    background: #d1d5db;
    opacity: 1;
    transition: all 0.3s ease;
}

.categorySwiper .swiper-pagination-bullet-active {
    background: #000;
    width: 24px;
    border-radius: 5px;
}

.categorySwiper {
    cursor: grab;
}

.categorySwiper:active {
    cursor: grabbing;
}
</style>
@endpush

@push('scripts')
<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Product Swiper
    const productSwiper = new Swiper('.productSwiper', {
        slidesPerView: 2,
        spaceBetween: 16,
        loop: false,
        grabCursor: true,
        
        pagination: {
            el: '.productSwiper .swiper-pagination',
            clickable: true,
            dynamicBullets: true,
        },
        
        mousewheel: {
            enabled: true,
            forceToAxis: true,
            sensitivity: 1,
            releaseOnEdges: true,
        },
        
        freeMode: {
            enabled: true,
            sticky: true,
            momentumRatio: 0.5,
            momentumVelocityRatio: 0.5,
        },
        
        breakpoints: {
            640: {
                slidesPerView: 2,
                spaceBetween: 16,
            },
            768: {
                slidesPerView: 3,
                spaceBetween: 20,
            },
            1024: {
                slidesPerView: 4,
                spaceBetween: 24,
            },
        },
        
        touchRatio: 1,
        touchAngle: 45,
        simulateTouch: true,
        shortSwipes: true,
        longSwipes: true,
        longSwipesRatio: 0.5,
        longSwipesMs: 300,
        followFinger: true,
        threshold: 5,
        resistance: true,
        resistanceRatio: 0.85,
    });

    console.log('Product Swiper with Touchpad support initialized');

    // Reward Swiper
    const rewardSwiper = new Swiper('.rewardSwiper', {
        slidesPerView: 2,
        spaceBetween: 16,
        loop: false,
        grabCursor: true,
        
        pagination: {
            el: '.rewardSwiper .swiper-pagination',
            clickable: true,
            dynamicBullets: true,
        },
        
        mousewheel: {
            enabled: true,
            forceToAxis: true,
            sensitivity: 1,
            releaseOnEdges: true,
        },
        
        freeMode: {
            enabled: true,
            sticky: true,
            momentumRatio: 0.5,
            momentumVelocityRatio: 0.5,
        },
        
        breakpoints: {
            640: {
                slidesPerView: 2,
                spaceBetween: 16,
            },
            768: {
                slidesPerView: 3,
                spaceBetween: 20,
            },
            1024: {
                slidesPerView: 4,
                spaceBetween: 24,
            },
        },
        
        touchRatio: 1,
        touchAngle: 45,
        simulateTouch: true,
        shortSwipes: true,
        longSwipes: true,
        longSwipesRatio: 0.5,
        longSwipesMs: 300,
        followFinger: true,
        threshold: 5,
        resistance: true,
        resistanceRatio: 0.85,
    });

    console.log('Reward Swiper with Touchpad support initialized');

    // Category Swiper
    const categorySwiper = new Swiper('.categorySwiper', {
        slidesPerView: 2,
        spaceBetween: 12,
        loop: false,
        grabCursor: true,
        
        pagination: {
            el: '.categorySwiper .swiper-pagination',
            clickable: true,
            dynamicBullets: true,
        },
        
        mousewheel: {
            enabled: true,
            forceToAxis: true,
            sensitivity: 1,
            releaseOnEdges: true,
        },
        
        freeMode: {
            enabled: true,
            sticky: true,
            momentumRatio: 0.5,
            momentumVelocityRatio: 0.5,
        },
        
        breakpoints: {
            640: {
                slidesPerView: 2,
                spaceBetween: 12,
            },
            768: {
                slidesPerView: 3,
                spaceBetween: 16,
            },
            1024: {
                slidesPerView: 4,
                spaceBetween: 16,
            },
        },
        
        touchRatio: 1,
        touchAngle: 45,
        simulateTouch: true,
        shortSwipes: true,
        longSwipes: true,
        longSwipesRatio: 0.5,
        longSwipesMs: 300,
        followFinger: true,
        threshold: 5,
        resistance: true,
        resistanceRatio: 0.85,
    });

    console.log('Category Swiper with Touchpad support initialized');
});
</script>
@endpush
@endsection
