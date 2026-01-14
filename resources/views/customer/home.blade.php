@extends('customer.layouts.app')

@section('title', 'Home')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&family=Bebas+Neue&display=swap" rel="stylesheet">
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
</style>
@endpush

@section('content')
{{-- Display Flash Messages --}}
@if(session('success'))
    <div class="fixed top-20 right-4 z-50 max-w-md">
        <x-alert type="success" :message="session('success')" />
    </div>
@endif

@if(session('error'))
    <div class="fixed top-20 right-4 z-50 max-w-md">
        <x-alert type="error" :message="session('error')" />
    </div>
@endif

@if($errors->any())
    <div class="fixed top-20 right-4 z-50 max-w-md">
        <x-alert type="error" :message="$errors->first()" />
    </div>
@endif

{{-- Hero Banner Section --}}
<section class="relative pt-10">
    <div class="h-[500px] md:h-[600px] overflow-hidden mx-4 md:mx-8 my-6 rounded-2xl border-2 border-yellow-400">
        @if($banners->count() > 0)
            {{-- Database Banner --}}
            @foreach($banners as $banner)
            <div class="w-full h-full">
                <img src="{{ $banner->image_url }}"
                     alt="{{ $banner->title }}"
                     class="w-full h-full object-cover"
                     loading="eager">
                <div class="absolute inset-0 bg-gradient-to-r from-black/60 via-black/40 to-transparent flex items-center">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                        <div class="max-w-xl md:max-w-2xl pl-2 md:pl-0">
                            <span class="inline-block text-yellow-400 text-xs md:text-sm font-semibold tracking-widest uppercase mb-2 md:mb-4">New Collection</span>
                            <h2 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-3 md:mb-4 leading-tight drop-shadow-lg">
                                {{ $banner->title }}
                            </h2>
                            @if($banner->description)
                            <p class="text-white/90 text-sm md:text-base lg:text-lg mb-4 md:mb-6 max-w-md leading-relaxed hidden sm:block">
                                {{ $banner->description }}
                            </p>
                            @endif
                            <a href="{{ route('products') }}"
                               class="inline-block bg-gradient-to-r from-yellow-400 to-yellow-500 text-black px-6 md:px-8 py-2.5 md:py-3 rounded-full font-semibold hover:from-yellow-500 hover:to-yellow-600 transition duration-300 transform hover:scale-105 shadow-lg text-sm md:text-base">
                                Shop Now
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @else
            {{-- Static Banner --}}
            <div class="w-full h-full flex items-center" style="background-color: #FAD470;">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                    <div class="grid md:grid-cols-2 gap-8 items-center">
                        <div class="text-center md:text-left">
                            <div class="mb-4">
                                <span class="text-black font-bold tracking-widest text-sm">New Season Highlight</span>
                            </div>
                            <h1 class="text-5xl md:text-7xl font-black text-black mb-6 leading-tight">
                                Wear the trend, own the moment
                            </h1>
                            <p class="text-gray-600 text-lg mb-8 max-w-md">
                                Discover curated fashion that defines your style — effortless, bold, and always on trend.
                            </p>
                            <div class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start">
                                <a href="{{ route('products') }}"
                                   class="inline-block bg-black text-[#FAD470] px-8 py-4 rounded-[2vw] font-semibold hover:bg-gray-800 transition duration-300 transform hover:scale-105 shadow-lg">
                                    Explore Collection
                                </a>
                                <a href="{{ route('articles.index') }}"
                                   class="inline-block bg-white text-black px-8 py-4 rounded-[2vw] font-semibold hover:bg-black hover:text-white transition duration-300">
                                    Discover More
                                </a>
                            </div>
                        </div>
                        <div class="relative">
                            <!-- Product Image -->
                            <div class="flex justify-center md:justify-end md:items-center h-full">
                                <div class="relative mb-12 md:mb-20 lg:mt-16 lg:mb-16">
                                    <!-- Image Shadow -->
                                    <div class="absolute inset-0 bg-yellow-200/30 rounded-full blur-xl transform translate-y-4 scale-75"></div>

                                    <!-- Main Image Container -->
                                    <img src="{{ asset('ui/main_1.png') }}"
                                         alt="The Paranoia - Premium Fashion"
                                         class="w-full h-auto max-w-xs md:max-w-sm lg:max-w-md object-contain relative z-10">

                                    <!-- Floating Elements -->
                                    <div class="absolute -top-8 -right-8 w-16 h-16 bg-yellow-300/20 rounded-full animate-pulse"></div>
                                    <div class="absolute -bottom-4 -left-8 w-12 h-12 bg-yellow-300/20 rounded-full animate-pulse delay-1000"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>

{{-- Brand Carousel Section --}}
<section class="py-4 overflow-hidden">
    <div class="relative">
        <div class="flex items-center overflow-hidden">

            {{-- First Set --}}
            <div class="flex items-center space-x-24 animate-scroll">
                @for ($i = 0; $i < 10; $i++)
                    <span class="text-3xl md:text-4xl font-bold tracking-widest uppercase text-gray-900 opacity-60 hover:opacity-100 transition-opacity duration-300 whitespace-nowrap leading-none">
                        THE PARANOIA
                    </span>
                @endfor
            </div>

            {{-- Spacer --}}
            <div class="w-24 flex-shrink-0"></div>

            {{-- Duplicate Set --}}
            <div class="flex items-center space-x-24 animate-scroll">
                @for ($i = 0; $i < 10; $i++)
                    <span class="text-3xl md:text-4xl font-bold tracking-widest uppercase text-gray-900 opacity-60 hover:opacity-100 transition-opacity duration-300 whitespace-nowrap leading-none">
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

    /* Pause animation on hover */
    .flex:hover .animate-scroll {
        animation-play-state: paused;
    }
</style>

{{-- Categories Section --}}
<section id="categories" class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Box 1 dengan main1.jpg -->
            <a href="{{ route('products') }}" class="block">
                <div class="relative rounded-3xl shadow-md cursor-pointer overflow-hidden group w-full"
                    style="height: 0; padding-bottom: 108.69%; max-width: 564px; aspect-ratio: 564/600;">
                    <div class="absolute inset-0 bg-cover bg-center"
                        style="background-image: url('{{ asset('ui/main1.jpg') }}');">
                        <div class="absolute inset-0 bg-black/40 group-hover:bg-black/50 transition-colors duration-300"></div>
                    </div>
                    <div class="absolute inset-0 flex items-center justify-center p-8">
                        <div class="text-center">
                            <h3 class="font-bold text-white text-xl md:text-2xl mb-4 drop-shadow-lg">SIMPLE MADE BE...</h3>
                            <span class="bg-white font-bold text-gray-900 px-6 py-2 rounded-full text-sm hover:bg-gray-100 transition-colors duration-200">
                                View More
                            </span>
                        </div>
                    </div>
                </div>
            </a>

            <!-- Box 2 dengan main2.jpg -->
            <a href="{{ route('products') }}" class="block">
                <div class="relative rounded-3xl shadow-md cursor-pointer overflow-hidden group w-full"
                    style="height: 0; padding-bottom: 108.69%; max-width: 564px; aspect-ratio: 564/600;">
                    <div class="absolute inset-0 bg-cover bg-center"
                        style="background-image: url('{{ asset('ui/main2.jpg') }}');">
                        <div class="absolute inset-0 bg-black/40 group-hover:bg-black/50 transition-colors duration-300"></div>
                    </div>
                    <div class="absolute inset-0 flex items-center justify-center p-8">
                        <div class="text-center">
                            <h3 class="font-bold text-white text-xl md:text-2xl mb-4 drop-shadow-lg">NEW ARRIVALS</h3>
                            <span class="bg-white font-bold text-gray-900 px-6 py-2 rounded-full text-sm hover:bg-gray-100 transition-colors duration-200">
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
    <!-- Full width background section -->
    <div class="min-h-80 flex items-center justify-center bg-[#FFC736B2]">
        <div class="text-center text-white px-4">
            <h2 class="font-bebas text-4xl md:text-6xl mb-4 text-black">
                UP TO 60% OFF ONLINE & IN-STORE
            </h2>
            <p class="text-xl md:text-2xl mb-8 text-black">
                Further markdowns for our biggest sale
            </p>
            <a href="{{ route('products') }}" class="bg-black text-[#FAD471] px-8 py-4 rounded-[2vw] font-semibold hover:bg-gray-100 transition-colors duration-200">
                Get Started
            </a>
        </div>
    </div>
</section>

{{-- Browse Product Category --}}
<section id="products" class="py-12 md:py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header and Button Container -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 md:mb-12">
            <!-- Header Text - Left -->
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900">
                Browse Product <br class="hidden sm:block"> By Category
            </h2>

            <!-- View More Button - Right -->
            <a href="{{ route('products') }}"
               class="inline-block bg-gradient-to-r from-yellow-400 to-yellow-500 text-black px-6 md:px-8 py-2.5 md:py-3 rounded-full font-bold hover:from-yellow-500 hover:to-yellow-600 transition duration-300 transform hover:scale-105 shadow-lg text-sm md:text-base">
                View All
            </a>
        </div>

        <!-- Category Grid - Dynamic from database -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 md:gap-4">
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
                $hoverColors = ['hover:bg-blue-600', 'hover:bg-gray-700', 'hover:bg-orange-600', 'hover:bg-red-600', 'hover:bg-purple-600', 'hover:bg-green-600', 'hover:bg-pink-600', 'hover:bg-indigo-600'];
            @endphp
            
            @foreach($categories->take(8) as $index => $category)
                @php
                    $iconKey = strtolower($category->slug ?? $category->name);
                    $icon = 'fa-tag';
                    foreach($categoryIcons as $key => $value) {
                        if(str_contains($iconKey, $key)) {
                            $icon = $value;
                            break;
                        }
                    }
                    $hoverColor = $hoverColors[$index % count($hoverColors)];
                @endphp
                <a href="{{ route('products', ['category' => $category->id]) }}"
                   class="group bg-white border-2 border-gray-100 rounded-2xl md:rounded-3xl shadow-sm hover:shadow-md hover:border-yellow-400 transition-all duration-300 overflow-hidden flex items-center p-3 md:p-4">
                    <!-- Icon Container -->
                    <div class="w-10 h-10 md:w-12 md:h-12 bg-[#FAD572] rounded-full flex items-center justify-center {{ $hoverColor }} transition-colors flex-shrink-0">
                        <i class="fas {{ $icon }} text-white text-lg md:text-xl"></i>
                    </div>

                    <!-- Category Info -->
                    <div class="flex-1 pl-3 md:pl-4 min-w-0">
                        <h3 class="text-sm md:text-base font-semibold text-gray-900 truncate">{{ $category->name }}</h3>
                        <p class="text-xs text-gray-500">{{ $category->products_count ?? 0 }} Products</p>
                    </div>

                    <!-- Arrow Icon -->
                    <div class="flex-shrink-0 ml-2">
                        <i class="fas fa-chevron-right text-gray-400 group-hover:text-yellow-500 transition-colors text-sm"></i>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>


{{-- Category Navigation Bar & Catalog Section --}}
<section class="pt-8 pb-8 bg-gray-50" x-data="productFilter()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Product Header -->
        <div class="mb-6 md:mb-8 text-left">
            <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-black text-gray-900 mb-2 md:mb-4">PRODUCT</h1>
            <p class="text-sm sm:text-base md:text-lg lg:text-xl text-gray-600 max-w-4xl">
                Redefine your wardrobe with fashion that's chic, versatile, and uniquely you
            </p>
        </div>
        <!-- Category Navigation -->
        <div class="flex flex-wrap justify-center gap-2 sm:gap-4 md:gap-6 lg:gap-10 rounded-2xl md:rounded-3xl bg-[#FAD470] p-2 md:p-3">
            <!-- All Tab -->
            <button @click="filterByCategory('all')"
               :class="activeCategory === 'all' ? 'bg-white text-black' : 'text-black hover:bg-white'"
               class="px-4 sm:px-6 md:px-8 py-2 md:py-3 rounded-full text-sm md:text-base font-semibold transition-all duration-300 transform">
                All
            </button>

            @foreach($categories->take(4) as $category)
            <button @click="filterByCategory('{{ $category->id }}')"
               :class="activeCategory === '{{ $category->id }}' ? 'bg-white text-black' : 'text-black hover:bg-white'"
               class="px-4 sm:px-6 md:px-8 py-2 md:py-3 rounded-full text-sm md:text-base font-semibold transition-all duration-300 transform">
                {{ $category->name }}
            </button>
            @endforeach
        </div>
    </div>

    <!-- Catalog Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-5">
        <!-- Loading State -->
        <div x-show="isLoading" class="flex justify-center py-12">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-yellow-500"></div>
        </div>

        <!-- Product Grid -->
        <div x-show="!isLoading" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 md:gap-4 lg:gap-5">
            <template x-for="product in filteredProducts" :key="product.id">
                <div class="group bg-white rounded-xl md:rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">
                    <!-- Image Container -->
                    <div class="relative overflow-hidden">
                        <template x-if="product.image">
                            <img :src="product.image"
                                 :alt="product.name"
                                 class="w-full h-32 sm:h-36 md:h-40 object-cover group-hover:scale-105 transition-transform duration-500">
                        </template>
                        <template x-if="!product.image">
                            <div class="w-full h-32 sm:h-36 md:h-40 bg-gray-200 flex items-center justify-center">
                                <i class="fas fa-image text-gray-400 text-2xl"></i>
                            </div>
                        </template>
                    </div>
                    
                    <!-- Product Content -->
                    <div class="p-3 md:p-4">
                        <div class="mb-2">
                            <span class="text-xs font-medium text-red-600 bg-red-50 px-1.5 py-0.5 rounded" x-text="product.category_name || 'Product'"></span>
                        </div>
                        
                        <h3 class="text-sm md:text-base font-bold text-gray-900 mb-2 line-clamp-2" x-text="product.name"></h3>
                        
                        <p class="text-base md:text-lg font-black text-gray-900 mb-3" x-text="'Rp ' + product.price_formatted"></p>
                        
                        <a :href="'/products/' + product.slug" 
                           class="block w-full bg-[#FAD470] text-black font-semibold py-2 md:py-2.5 rounded-lg hover:bg-[#FAD420] active:scale-95 transition-all duration-200 shadow-md hover:shadow-lg text-center text-xs md:text-sm">
                            View Details
                        </a>
                    </div>
                </div>
            </template>

            <!-- Empty State -->
            <template x-if="filteredProducts.length === 0 && !isLoading">
                <div class="col-span-full text-center py-12">
                    <i class="fas fa-box-open text-gray-300 text-5xl mb-4"></i>
                    <p class="text-gray-500">Belum ada produk tersedia</p>
                </div>
            </template>
        </div>

        <!-- View All Button -->
        <div class="text-center mt-6 md:mt-8" x-show="filteredProducts.length > 0">
            <a :href="activeCategory === 'all' ? '{{ route('products') }}' : '{{ route('products') }}?category=' + activeCategory" 
               class="inline-block bg-gradient-to-r from-yellow-400 to-yellow-500 text-black px-6 md:px-8 py-2.5 md:py-3 rounded-full font-semibold hover:from-yellow-500 hover:to-yellow-600 transition duration-300 transform hover:scale-105 shadow-lg text-sm md:text-base">
                View All Products
            </a>
        </div>
    </div>
</section>

@php
    $productsData = $popularProducts->map(function($product) {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'price' => $product->price,
            'price_formatted' => number_format($product->price, 0, ',', '.'),
            'category_id' => $product->category_id,
            'category_name' => $product->category->name ?? 'Product',
            'image' => $product->images->count() > 0 ? asset('storage/' . $product->images->first()->image_path) : null,
        ];
    })->values();
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
            
            // Simulate loading effect
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
        <!-- Section Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 md:mb-16">
            <div>
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-gray-900 mb-2">
                    ABOUT OUR BRAND
                </h2>
                <p class="text-sm md:text-lg text-gray-600 max-w-3xl">
                   About Us: Our Brand Story
                </p>
            </div>
            <a href="#" class="bg-gradient-to-r from-yellow-400 to-yellow-500 text-black px-4 md:px-6 py-2 md:py-3 rounded-full font-semibold hover:from-yellow-500 hover:to-yellow-600 transition duration-300 transform hover:scale-105 shadow-lg text-sm md:text-base">
                View More
            </a>
        </div>

        <!-- Main Content -->
        <div class="relative mb-8 md:mb-16 -mx-4 md:-mx-8 lg:-mx-16">
            <!-- Background Image Full Width -->
            <img src="{{ asset('about-us.jpg') }}"
                 alt="About Us"
                 class="w-full h-[250px] sm:h-[350px] md:h-[400px] lg:max-h-[500px] object-cover">

            <!-- Component Overlay on Right - Hidden on mobile, shown on larger screens -->
            <div class="hidden md:block absolute top-8 right-8 lg:right-16 bg-white p-4 md:p-6 rounded-lg shadow-lg max-w-sm lg:max-w-md">
                <h3 class="text-xl md:text-2xl font-bold text-gray-900 mb-3 md:mb-4">
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

        <!-- Mobile Story Content -->
        <div class="md:hidden bg-white p-4 rounded-lg shadow-sm -mt-4">
            <h3 class="text-lg font-bold text-gray-900 mb-3">
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
        <!-- Section Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 md:mb-12">
            <div>
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-gray-900 mb-2">
                    REWARD
                </h2>
                <p class="text-sm md:text-lg text-gray-600 max-w-3xl">
                   Redeem your points for exclusive reward products!
                </p>
            </div>
            <a href="{{ route('rewards') }}" class="bg-gradient-to-r from-yellow-400 to-yellow-500 text-black px-4 md:px-6 py-2 md:py-3 rounded-full font-semibold hover:from-yellow-500 hover:to-yellow-600 transition duration-300 transform hover:scale-105 shadow-lg text-sm md:text-base">
                View All
            </a>
        </div>

        <!-- Reward Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 md:gap-4 lg:gap-5">
            @forelse($rewardProducts as $product)
            <div class="group bg-white rounded-xl md:rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">
                <!-- Image Container -->
                <div class="relative overflow-hidden">
                    @if($product->images->count() > 0)
                        <img src="{{ asset('storage/' . $product->images->first()->image_path) }}"
                            alt="{{ $product->name }}"
                            class="w-full h-32 sm:h-36 md:h-40 object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                        <div class="w-full h-32 sm:h-36 md:h-40 bg-gray-200 flex items-center justify-center">
                            <i class="fas fa-gift text-gray-400 text-2xl"></i>
                        </div>
                    @endif
                    
                    <!-- Reward Badge -->
                    <div class="absolute top-2 left-2 bg-yellow-400 text-black font-bold text-xs px-2 py-1 rounded-full">
                        <i class="fas fa-star mr-1"></i>Reward
                    </div>
                </div>
                
                <!-- Reward Content -->
                <div class="p-3 md:p-4">
                    <div class="mb-2">
                        <span class="text-xs font-medium text-yellow-600 bg-yellow-50 px-1.5 py-0.5 rounded">
                            {{ $product->category->name ?? 'Reward' }}
                        </span>
                    </div>
                    
                    <h3 class="text-sm md:text-base font-bold text-gray-900 mb-2 line-clamp-2">
                        {{ $product->name }}
                    </h3>
                    
                    <p class="text-base md:text-lg font-black text-yellow-600 mb-3">
                        <i class="fas fa-coins mr-1"></i>{{ number_format($product->point_price ?? 0, 0, ',', '.') }} Poin
                    </p>
                    
                    <a href="{{ route('product.detail', $product->slug) }}" 
                       class="block w-full bg-[#FAD470] text-black font-semibold py-2 md:py-2.5 rounded-lg hover:bg-[#FAD420] text-black font-semibold py-2 md:py-2.5 rounded-lg hover:bg-[#FAD420] active:scale-95 transition-all duration-200 shadow-md hover:shadow-lg text-center text-xs md:text-sm">
                        View Details
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-12">
                <i class="fas fa-gift text-gray-300 text-5xl mb-4"></i>
                <p class="text-gray-500">Belum ada produk reward tersedia</p>
            </div>
            @endforelse
        </div>
    </div>
</section>  

{{-- Style Inspiration Section --}}
<section id="style-inspiration" class="py-12 md:py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 md:mb-12">
            <div>
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-gray-900 mb-2">
                    STYLE INSPIRATION
                </h2>
                <p class="text-gray-600 text-sm md:text-base">Get inspired by our latest fashion lookbook</p>
            </div>
            <a href="{{ route('products') }}" class="bg-gradient-to-r from-yellow-400 to-yellow-500 text-black px-6 py-2.5 md:py-3 rounded-full font-semibold hover:from-yellow-500 hover:to-yellow-600 transition duration-300 transform hover:scale-105 shadow-lg text-sm md:text-base">
                Explore All
            </a>
        </div>

        <!-- Style Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
            <!-- Style Card 1 - Casual Everyday -->
            <a href="{{ route('products') }}" class="group relative overflow-hidden rounded-2xl md:rounded-3xl shadow-lg aspect-[4/5]">
                <img src="{{ asset('ui/casual-style.jpg') }}"
                     alt="Casual Everyday"
                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                <div class="absolute bottom-0 left-0 right-0 p-4 md:p-6">
                    <span class="inline-block bg-yellow-400 text-black text-xs font-bold px-3 py-1 rounded-full mb-2 md:mb-3">TRENDING</span>
                    <h3 class="text-xl md:text-2xl font-bold text-white mb-1 md:mb-2">Casual Everyday</h3>
                    <p class="text-white/80 text-xs md:text-sm line-clamp-2">Effortless style for your daily adventures</p>
                </div>
            </a>

            <!-- Style Card 2 - Street Style -->
            <a href="{{ route('products') }}" class="group relative overflow-hidden rounded-2xl md:rounded-3xl shadow-lg aspect-[4/5]">
                <img src="{{ asset('ui/street-style.jpg') }}"
                     alt="Street Style"
                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                <div class="absolute bottom-0 left-0 right-0 p-4 md:p-6">
                    <span class="inline-block bg-white text-black text-xs font-bold px-3 py-1 rounded-full mb-2 md:mb-3">NEW ARRIVAL</span>
                    <h3 class="text-xl md:text-2xl font-bold text-white mb-1 md:mb-2">Street Style</h3>
                    <p class="text-white/80 text-xs md:text-sm line-clamp-2">Bold looks that make a statement</p>
                </div>
            </a>

            <!-- Style Card 3 - Minimalist Essentials -->
            <a href="{{ route('products') }}" class="group relative overflow-hidden rounded-2xl md:rounded-3xl shadow-lg aspect-[4/5] md:col-span-2 lg:col-span-1">
                <img src="{{ asset('ui/minimalist-style.jpg') }}"
                     alt="Minimalist Essentials"
                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                <div class="absolute bottom-0 left-0 right-0 p-4 md:p-6">
                    <span class="inline-block bg-gray-800 text-white text-xs font-bold px-3 py-1 rounded-full mb-2 md:mb-3">BESTSELLER</span>
                    <h3 class="text-xl md:text-2xl font-bold text-white mb-1 md:mb-2">Minimalist Essentials</h3>
                    <p class="text-white/80 text-xs md:text-sm line-clamp-2">Timeless pieces for a polished look</p>
                </div>
            </a>
        </div>
    </div>
</section>
@endsection
