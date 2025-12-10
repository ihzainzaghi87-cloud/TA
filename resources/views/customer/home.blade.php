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
                <div class="absolute inset-0 bg-gradient-to-r from-black/50 to-transparent flex items-center">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                        <div class="max-w-2xl">
                            <h2 class="text-4xl md:text-6xl font-bold text-white mb-4">
                                {{ $banner->title }}
                            </h2>
                            <a href="#products"
                               class="inline-block bg-gradient-to-r from-yellow-400 to-yellow-500 text-black px-8 py-3 rounded-full font-semibold hover:from-yellow-500 hover:to-yellow-600 transition duration-300 transform hover:scale-105 shadow-lg">
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
                                <span class="text-black font-bold tracking-widest text-sm">Most Popular 100th Product in Belanja</span>
                            </div>
                            <h1 class="text-5xl md:text-7xl font-black text-black mb-6 leading-tight">
                                Wear the trend, own the moment
                            </h1>
                            <p class="text-gray-600 text-lg mb-8 max-w-md">
                                Dolor si amet lorem super-power features riches than any other platform devices AI integrated.
                            </p>
                            <div class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start">
                                <a href="#products"
                                   class="inline-block bg-black text-[#FAD470] px-8 py-4 rounded-[2vw] font-semibold hover:bg-gray-800 transition duration-300 transform hover:scale-105 shadow-lg">
                                    Shop Now
                                </a>
                                <a href="#"
                                   class="inline-block bg-white text-black px-8 py-4 rounded-[2vw] font-semibold hover:bg-black hover:text-white transition duration-300">
                                    Learn More
                                </a>
                            </div>
                        </div>
                        <div class="relative">
                            <!-- Perfume Bottle Illustration -->
                            <div class="flex justify-center md:justify-end">
                                <div class="relative">
                                    <!-- Bottle Shadow -->
                                    <div class="absolute inset-0 bg-yellow-200/30 rounded-full blur-xl transform translate-y-4 scale-75"></div>

                                    <!-- Main Bottle -->
                                    <div class="relative">
                                        <!-- Bottle Cap -->
                                        <div class="w-16 h-8 bg-gradient-to-b from-yellow-600 to-yellow-500 rounded-t-full mx-auto"></div>
                                        <div class="w-12 h-4 bg-yellow-700 mx-auto"></div>

                                        <!-- Bottle Body -->
                                        <div class="w-32 h-48 bg-gradient-to-b from-yellow-400 to-yellow-500 rounded-lg relative overflow-hidden">
                                            <!-- Bottle Label -->
                                            <div class="absolute inset-x-4 top-8 bottom-8 bg-black/90 rounded flex items-center justify-center">
                                                <div class="text-white text-center p-4">
                                                    <div class="text-xs font-bold mb-2">PARANOIA</div>
                                                    <div class="text-xs opacity-75">PREMIUM</div>
                                                    <div class="text-xs opacity-75">FRAGRANCE</div>
                                                </div>
                                            </div>

                                            <!-- Bottle Shine Effect -->
                                            <div class="absolute top-4 left-4 w-8 h-32 bg-white/20 rounded-full transform rotate-12"></div>
                                        </div>

                                        <!-- Bottle Base -->
                                        <div class="w-32 h-2 bg-yellow-600 rounded-lg"></div>

                                        <!-- Spray Top -->
                                        <div class="absolute -top-6 left-1/2 -translate-x-1/2 w-4 h-4 bg-yellow-600 rounded-full"></div>
                                        <div class="absolute -top-8 left-1/2 -translate-x-1/2 w-1 h-4 bg-gray-400"></div>
                                    </div>

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
<section class="py-12 overflow-hidden">
    <div class="relative">
        <!-- Brand Logos Animation -->
        <div class="flex items-center overflow-hidden">
            <!-- First Set of Brands -->
            <div class="flex items-center space-x-32 animate-scroll">
                <img src="{{ asset('ui/brand/logo_1.svg') }}" alt="Brand 1" class="h-12 md:h-16 w-16 md:w-20 max-w-[80px] object-contain opacity-60 hover:opacity-100 transition-opacity duration-300">
                <img src="{{ asset('ui/brand/logo_2.svg') }}" alt="Brand 2" class="h-12 md:h-16 w-16 md:w-20 max-w-[80px] object-contain opacity-60 hover:opacity-100 transition-opacity duration-300">
                <img src="{{ asset('ui/brand/logo_3.svg') }}" alt="Brand 3" class="h-12 md:h-16 w-16 md:w-20 max-w-[80px] object-contain opacity-60 hover:opacity-100 transition-opacity duration-300">
                <img src="{{ asset('ui/brand/logo_4.svg') }}" alt="Brand 4" class="h-12 md:h-16 w-16 md:w-20 max-w-[80px] object-contain opacity-60 hover:opacity-100 transition-opacity duration-300">
                <img src="{{ asset('ui/brand/logo_5.svg') }}" alt="Brand 5" class="h-12 md:h-16 w-16 md:w-20 max-w-[80px] object-contain opacity-60 hover:opacity-100 transition-opacity duration-300">
                <img src="{{ asset('ui/brand/logo_6.svg') }}" alt="Brand 6" class="h-12 md:h-16 w-16 md:w-20 max-w-[80px] object-contain opacity-60 hover:opacity-100 transition-opacity duration-300">
                <img src="{{ asset('ui/brand/logo_7.svg') }}" alt="Brand 7" class="h-12 md:h-16 w-16 md:w-20 max-w-[80px] object-contain opacity-60 hover:opacity-100 transition-opacity duration-300">
                <img src="{{ asset('ui/brand/logo_8.svg') }}" alt="Brand 8" class="h-12 md:h-16 w-16 md:w-20 max-w-[80px] object-contain opacity-60 hover:opacity-100 transition-opacity duration-300">
                <img src="{{ asset('ui/brand/logo_9.svg') }}" alt="Brand 9" class="h-12 md:h-16 w-16 md:w-20 max-w-[80px] object-contain opacity-60 hover:opacity-100 transition-opacity duration-300">
                <img src="{{ asset('ui/brand/logo_10.svg') }}" alt="Brand 10" class="h-12 md:h-16 w-16 md:w-20 max-w-[80px] object-contain opacity-60 hover:opacity-100 transition-opacity duration-300">
            </div>
            <!-- Spacer untuk memisahkan set pertama dan kedua -->
            <div class="w-32 flex-shrink-0"></div>
            <!-- Duplicate Set for Infinite Loop -->
            <div class="flex items-center space-x-32 animate-scroll">
                <img src="{{ asset('ui/brand/logo_1.svg') }}" alt="Brand 1" class="h-12 md:h-16 w-16 md:w-20 max-w-[80px] object-contain opacity-60 hover:opacity-100 transition-opacity duration-300">
                <img src="{{ asset('ui/brand/logo_2.svg') }}" alt="Brand 2" class="h-12 md:h-16 w-16 md:w-20 max-w-[80px] object-contain opacity-60 hover:opacity-100 transition-opacity duration-300">
                <img src="{{ asset('ui/brand/logo_3.svg') }}" alt="Brand 3" class="h-12 md:h-16 w-16 md:w-20 max-w-[80px] object-contain opacity-60 hover:opacity-100 transition-opacity duration-300">
                <img src="{{ asset('ui/brand/logo_4.svg') }}" alt="Brand 4" class="h-12 md:h-16 w-16 md:w-20 max-w-[80px] object-contain opacity-60 hover:opacity-100 transition-opacity duration-300">
                <img src="{{ asset('ui/brand/logo_5.svg') }}" alt="Brand 5" class="h-12 md:h-16 w-16 md:w-20 max-w-[80px] object-contain opacity-60 hover:opacity-100 transition-opacity duration-300">
                <img src="{{ asset('ui/brand/logo_6.svg') }}" alt="Brand 6" class="h-12 md:h-16 w-16 md:w-20 max-w-[80px] object-contain opacity-60 hover:opacity-100 transition-opacity duration-300">
                <img src="{{ asset('ui/brand/logo_7.svg') }}" alt="Brand 7" class="h-12 md:h-16 w-16 md:w-20 max-w-[80px] object-contain opacity-60 hover:opacity-100 transition-opacity duration-300">
                <img src="{{ asset('ui/brand/logo_8.svg') }}" alt="Brand 8" class="h-12 md:h-16 w-16 md:w-20 max-w-[80px] object-contain opacity-60 hover:opacity-100 transition-opacity duration-300">
                <img src="{{ asset('ui/brand/logo_9.svg') }}" alt="Brand 9" class="h-12 md:h-16 w-16 md:w-20 max-w-[80px] object-contain opacity-60 hover:opacity-100 transition-opacity duration-300">
                <img src="{{ asset('ui/brand/logo_10.svg') }}" alt="Brand 10" class="h-12 md:h-16 w-16 md:w-20 max-w-[80px] object-contain opacity-60 hover:opacity-100 transition-opacity duration-300">
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
            @forelse($categories as $index => $category)
            @if($index < 2)
            <div class="relative rounded-lg shadow-md cursor-pointer overflow-hidden group w-full"
                style="height: 0; padding-bottom: 108.69%; max-width: 564px; aspect-ratio: 564/613;">
                <!-- Background Image -->
                <div class="absolute inset-0 bg-cover bg-center"
                     style="background-image: url('{{ asset('ui/main' . ($index + 1) . '.jpg') }}');">
                    <!-- Dark Overlay -->
                    <div class="absolute inset-0 bg-black/40 group-hover:bg-black/50 transition-colors duration-300"></div>
                </div>

                <!-- Content -->
                <div class="absolute inset-0 flex items-center justify-center p-8">
                    <div class="text-center">
                        @if($category->image)
                            <img src="{{ asset('storage/' . $category->image) }}"
                                 alt="{{ $category->name }}"
                                 class="w-24 h-24 md:w-32 md:h-32 object-contain mx-auto mb-4">
                        @else
                            <i class="fas fa-{{ $category->icon ?? 'box' }} text-6xl md:text-7xl text-white mb-4"></i>
                        @endif

                        <h3 class="font-bold text-white text-xl md:text-2xl mb-2 drop-shadow-lg">
                            {{ $category->name }}
                        </h3>
                    </div>
                </div>
            </div>
            @endif
            @empty
            <!-- Box 1 dengan main1.jpg -->
            <div class="relative rounded-3xl shadow-md cursor-pointer overflow-hidden group w-full"
                style="height: 0; padding-bottom: 108.69%; max-width: 564px; aspect-ratio: 564/600;">
                <div class="absolute inset-0 bg-cover bg-center"
                     style="background-image: url('{{ asset('ui/main1.jpg') }}');">
                    <div class="absolute inset-0 bg-black/40 group-hover:bg-black/50 transition-colors duration-300"></div>
                </div>
                <div class="absolute inset-0 flex items-center justify-center p-8">
                    <div class="text-center">
                        <h3 class="font-bold text-white text-xl md:text-2xl mb-4 drop-shadow-lg">SIMPLE MADE BE...</h3>
                        <button class="bg-white font-bold text-gray-900 px-6 py-2 rounded-full text-sm hover:bg-gray-100 transition-colors duration-200">
                            View More
                        </button>
                    </div>
                </div>
            </div>

            <!-- Box 2 dengan main2.jpg -->
            <div class="relative rounded-3xl shadow-md cursor-pointer overflow-hidden group w-full"
                style="height: 0; padding-bottom: 108.69%; max-width: 564px; aspect-ratio: 564/600;">
                <div class="absolute inset-0 bg-cover bg-center"
                     style="background-image: url('{{ asset('ui/main2.jpg') }}');">
                    <div class="absolute inset-0 bg-black/40 group-hover:bg-black/50 transition-colors duration-300"></div>
                </div>
                <div class="absolute inset-0 flex items-center justify-center p-8">
                    <div class="text-center">
                        <h3 class="font-bold text-white text-xl md:text-2xl mb-4 drop-shadow-lg">NEW ARRIVALS</h3>
                        <button class="bg-white font-bold text-gray-900 px-6 py-2 rounded-full text-sm hover:bg-gray-100 transition-colors duration-200">
                            View More
                        </button>
                    </div>
                </div>
            </div>
            @endforelse
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
            <button class="bg-black text-[#FAD471] px-8 py-4 rounded-[2vw] font-semibold hover:bg-gray-100 transition-colors duration-200">
                Get Started
            </button>
        </div>
    </div>
</section>

{{-- Browse Product Category --}}
<section id="products" class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header and Button Container -->
        <div class="flex justify-between items-center mb-12">
            <!-- Header Text - Left -->
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900">
                Browse Product <br> By Category
            </h2>

            <!-- View More Button - Right -->
            <a href="#categories"
               class="inline-block bg-gradient-to-r from-yellow-400 to-yellow-500 text-black px-8 py-3 rounded-full font-bold hover:from-yellow-500 hover:to-yellow-600 transition duration-300 transform hover:scale-105 shadow-lg">
                View More
            </a>
        </div>

        <!-- Category Grid - Compact aligned cards -->
        <div class="flex flex-wrap justify-center gap-2 px-4">
            <!-- Category 1: Clothes -->
            <a href="#"
               class="group border-2 border-solid rounded-3xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden flex items-center"
               style="width: 280px; height: 80px;">
                <!-- Icon Container -->
                <div class="w-12 h-12 bg-[#FAD572] ml-4 rounded-full flex items-center justify-center group-hover:bg-blue-600 transition-colors flex-shrink-0">
                    <i class="fas fa-tshirt text-white text-2xl"></i>
                </div>

                <!-- Category Name -->
                <div class="flex-1 px-6 text-center">
                    <h3 class="text-sm font-semibold text-gray-900">Clothes</h3>
                </div>
            </a>

            <!-- Category 2: Jacket -->
            <a href="#"
               class="group border-2 border-solid rounded-3xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden flex items-center"
               style="width: 280px; height: 80px;">
                <!-- Icon Container -->
                <div class="w-12 h-12 bg-[#FAD572] ml-4 rounded-full flex items-center justify-center group-hover:bg-gray-700 transition-colors flex-shrink-0">
                    <i class="fas fa-vest text-white text-2xl"></i>
                </div>

                <!-- Category Name -->
                <div class="flex-1 px-6 text-center">
                    <h3 class="text-sm font-semibold text-gray-900">Jacket</h3>
                </div>
            </a>

            <!-- Category 3: Pants -->
            <a href="#"
               class="group border-2 border-solid rounded-3xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden flex items-center"
               style="width: 280px; height: 80px;">
                <!-- Icon Container -->
                <div class="w-12 h-12 bg-[#FAD572] ml-4 rounded-full flex items-center justify-center group-hover:bg-orange-600 transition-colors flex-shrink-0">
                    <i class="fas fa-socks text-white text-2xl"></i>
                </div>

                <!-- Category Name -->
                <div class="flex-1 px-6 text-center">
                    <h3 class="text-sm font-semibold text-gray-900">Pants</h3>
                </div>
            </a>

            <!-- Category 4: Hats -->
            <a href="#"
               class="group border-2 border-solid rounded-3xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden flex items-center"
               style="width: 280px; height: 80px;">
                <!-- Icon Container -->
                <div class="w-12 h-12 bg-[#FAD572] ml-4 rounded-full flex items-center justify-center group-hover:bg-red-600 transition-colors flex-shrink-0">
                    <i class="fas fa-hat-cowboy text-white text-2xl"></i>
                </div>

                <!-- Category Name -->
                <div class="flex-1 px-6 text-center">
                    <h3 class="text-sm font-semibold text-gray-900">Hats</h3>
                </div>
            </a>
        </div>
    </div>
</section>


{{-- Category Navigation Bar --}}
<section class="pt-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Product Header -->
        <div class="mb-8 text-left">
            <h1 class="text-5xl md:text-6xl font-black text-gray-900 mb-4">PRODUCT</h1>
            <p class="text-lg md:text-xl text-gray-600 max-w-4xl whitespace-nowrap">
                Redefine your wardrobe with fashion that's chic, versatile, and uniquely you
            </p>
        </div>
        <!-- Category Navigation -->
        <div class="flex flex-wrap justify-center gap-40 rounded-3xl bg-[#FAD470] p-2 p-2">
            <!-- All Tab (Active) -->
            <a href="#all"
               class="group px-8 py-3 text-black rounded-full font-semibold hover:text-black hover:bg-white transition-all duration-300 transform">
                All
            </a>

            <!-- T-shirt Tab -->
            <a href="#tshirt"
               class="group px-8 py-3 text-black rounded-full font-semibold hover:text-black hover:bg-white transition-all duration-300 transform">
                T-shirt
            </a>

            <!-- Jacket Tab -->
            <a href="#jacket"
               class="group px-8 py-3 text-black rounded-full font-semibold hover:text-black hover:bg-white transition-all duration-300 transform">
                Jacket
            </a>

            <!-- Bag Tab -->
            <a href="#bag"
                class="group px-8 py-3 text-black rounded-full font-semibold hover:text-black hover:bg-white transition-all duration-300 transform">
                    Bag
                </a>

            <!-- Pants Tab -->
            <a href="#pants"
               class="group px-8 py-3 text-black rounded-full font-semibold hover:text-black hover:bg-white transition-all duration-300 transform">
                Pants
            </a>
        </div>
    </div>
</section>

{{-- Catalog Section --}}
<section class="pb-8 pt-5 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <?php
        // Array data produk
        $catalogProducts = [
            [
                'id' => 1,
                'name' => 'Classic White T-Shirt',
                'price' => 29.99,
                'image' => 'ui/catalog/product1.jpg',
                'category' => 'tshirt',
                'slug' => 'classic-white-t-shirt'
            ],
            [
                'id' => 2,
                'name' => 'Denim Jacket Classic',
                'price' => 89.99,
                'image' => 'ui/catalog/product2.jpg',
                'category' => 'jacket',
                'slug' => 'denim-jacket-classic'
            ],
            [
                'id' => 3,
                'name' => 'Leather Crossbody Bag',
                'price' => 129.99,
                'image' => 'ui/catalog/product3.jpg',
                'category' => 'bag',
                'slug' => 'leather-crossbody-bag'
            ],
            [
                'id' => 4,
                'name' => 'Slim Fit Chino Pants',
                'price' => 59.99,
                'image' => 'ui/catalog/product4.jpg',
                'category' => 'pants',
                'slug' => 'slim-fit-chino-pants'
            ],
            [
                'id' => 5,
                'name' => 'Graphic Print Hoodie',
                'price' => 45.99,
                'image' => 'ui/catalog/product5.jpg',
                'category' => 'tshirt',
                'slug' => 'graphic-print-hoodie'
            ]
        ];
        ?>

        <!-- Product Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 md:gap-6">
            @foreach($catalogProducts as $product)
            <a href="{{ route('product.detail', $product['slug']) }}" class="group rounded-lg hover:shadow-lg transition-all duration-300 overflow-hidden">
                <!-- Product Image -->
                <div class="relative aspect-square bg-gray-100 overflow-hidden">
                    <img src="{{ asset($product['image']) }}"
                         alt="{{ $product['name'] }}"
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">

                    <!-- Quick Actions Overlay -->
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300 flex items-center justify-center">
                        <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex gap-2">
                            <button class="bg-white p-2 rounded-lg shadow-md hover:bg-gray-100 transition-colors" onclick="event.stopPropagation(); addToWishlist({{ $product['id'] }})">
                                <i class="fas fa-heart text-gray-700 text-sm"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Product Info -->
                <div class="p-4">
                    <h3 class="font-semibold text-gray-900 text-sm mb-2 line-clamp-2">
                        {{ $product['name'] }}
                    </h3>

                    <!-- Price -->
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-lg font-bold text-gray-900">
                            ${{ number_format($product['price'], 2) }}
                        </span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>

<script>
// JavaScript functions untuk product actions
function viewProduct(productSlug) {
    console.log('View product:', productSlug);
    // Redirect ke halaman detail product
    window.location.href = `/product/${productSlug}`;
}

function addToWishlist(productId) {
    console.log('Add to wishlist:', productId);
    // Implementasi wishlist akan ditambahkan di sini
    alert('Added to wishlist! Product ID: ' + productId);
}
</script>

{{-- About Section --}}
<section id="about" class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="flex justify-between items-start mb-16">
            <div>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-2">
                    ABOUT OUR BRAND
                </h2>
                <p class="text-lg text-gray-600 max-w-3xl">
                   About Us: Our Brand Story
                </p>
            </div>
            <button class="bg-gradient-to-r from-yellow-400 to-yellow-500 text-black px-6 py-3 rounded-full font-semibold hover:from-yellow-500 hover:to-yellow-600 transition duration-300 transform hover:scale-105 shadow-lg">
                View More ->
            </button>
        </div>

        <!-- Main Content -->
        <div class="relative mb-16 -mx-4 md:-mx-8 lg:-mx-16">
            <!-- Background Image Full Width -->
            <img src="{{ asset('about-us.jpg') }}"
                 alt="About Us"
                 class="w-full max-h-[500px] object-cover">

            <!-- Component Overlay on Right -->
            <div class="absolute top-8 right-8 md:right-8 lg:right-16 bg-white p-6 rounded-lg shadow-lg max-w-md">
                <h3 class="text-2xl font-bold text-gray-900 mb-4">
                    Our Story
                </h3>
                <p class="text-gray-600 mb-4 leading-relaxed">
                    Founded with a vision to revolutionize the fashion industry, we started as a small boutique with big dreams. Today, we're proud to be one of the most trusted names in online fashion retail.
                </p>
                <p class="text-gray-600 leading-relaxed">
                    Our commitment to quality, style, and customer satisfaction has helped us build a community of fashion lovers who trust us for their wardrobe needs.
                </p>
            </div>
        </div>

    </div>
</section>

{{-- Rewards Section --}}
<section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="flex justify-between items-start mb-16">
                <div>
                    <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-2">
                        REWARD
                    </h2>
                    <p class="text-lg text-gray-600 max-w-3xl">
                       Get Rewards
                    </p>
                </div>
                <button class="bg-gradient-to-r from-yellow-400 to-yellow-500 text-black px-6 py-3 rounded-full font-semibold hover:from-yellow-500 hover:to-yellow-600 transition duration-300 transform hover:scale-105 shadow-lg">
                    View More ->
                </button>
            </div>

            <!-- Promo Cards Grid -->
            <div class="grid md:grid-cols-3 gap-8 mb-12">
                <!-- Promo Card 1 - 20% OFF -->
                <div class="bg-gradient-to-br from-blue-500 to-cyan-500 rounded-2xl shadow-xl overflow-hidden transform hover:scale-105 transition-transform duration-300">
                    <div class="p-8 text-white">
                        <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mb-6">
                            <i class="fas fa-tag text-white text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-4">20% OFF</h3>
                        <p class="text-white/90 mb-6">
                            Get 20% discount on all products with promo code. Limited time offer for our loyal customers.
                        </p>
                        <div class="text-3xl font-bold mb-2">
                            PROMO20
                        </div>
                        <div class="text-sm text-white/80">
                            Use this code at checkout
                        </div>
                    </div>
                </div>

                <!-- Promo Card 2 - Free Shipping -->
                <div class="bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl shadow-xl overflow-hidden transform hover:scale-105 transition-transform duration-300">
                    <div class="p-8 text-white">
                        <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mb-6">
                            <i class="fas fa-shipping-fast text-white text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-4">FREE SHIPPING</h3>
                        <p class="text-white/90 mb-6">
                            Enjoy free shipping on all orders above $50. No hidden charges, fast delivery guaranteed.
                        </p>
                        <div class="text-3xl font-bold mb-2">
                            $50+
                        </div>
                        <div class="text-sm text-white/80">
                            Minimum order value
                        </div>
                    </div>
                </div>

                <!-- Promo Card 3 - Double Points -->
                <div class="bg-gradient-to-br from-orange-400 to-yellow-500 rounded-2xl shadow-xl overflow-hidden transform hover:scale-105 transition-transform duration-300">
                    <div class="p-8 text-white">
                        <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mb-6">
                            <i class="fas fa-star text-white text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-4">DOUBLE POINTS</h3>
                        <p class="text-white/90 mb-6">
                            Earn double reward points on every purchase this month. Redeem points for exclusive gifts.
                        </p>
                        <div class="text-3xl font-bold mb-2">
                            2X
                        </div>
                        <div class="text-sm text-white/80">
                            Points multiplier
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rewards Cards Grid (Original) -->
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                <!-- Points Card -->
                <div class="bg-gradient-to-br from-orange-400 to-yellow-500 rounded-2xl shadow-xl overflow-hidden transform hover:scale-105 transition-transform duration-300">
                    <div class="p-8 text-white">
                        <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mb-6">
                            <i class="fas fa-gift text-white text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-4">Points</h3>
                        <p class="text-white/90 mb-6">
                            Earn points with every purchase and redeem them for exclusive discounts and rewards
                        </p>
                        <div class="text-3xl font-bold">
                            100+
                        </div>
                        <div class="text-sm text-white/80">
                            Points per purchase
                        </div>
                    </div>
                </div>

                <!-- Exclusive Offers Card -->
                <div class="bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl shadow-xl overflow-hidden transform hover:scale-105 transition-transform duration-300">
                    <div class="p-8 text-white">
                        <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mb-6">
                            <i class="fas fa-star text-white text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-4">Exclusive Offers</h3>
                        <p class="text-white/90 mb-6">
                            Get access to member-only deals, special promotions, and early access to sales
                        </p>
                        <div class="text-3xl font-bold">
                            50+
                        </div>
                        <div class="text-sm text-white/80">
                            Exclusive deals monthly
                        </div>
                    </div>
                </div>

                <!-- Birthday Treat Card -->
                <div class="bg-gradient-to-br from-blue-500 to-cyan-500 rounded-2xl shadow-xl overflow-hidden transform hover:scale-105 transition-transform duration-300">
                    <div class="p-8 text-white">
                        <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mb-6">
                            <i class="fas fa-birthday-cake text-white text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-4">Birthday Treat</h3>
                        <p class="text-white/90 mb-6">
                            Receive special birthday gifts and surprises from us on your special day
                        </p>
                        <div class="text-3xl font-bold">
                            25%
                        </div>
                        <div class="text-sm text-white/80">
                            Birthday discount
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>  

{{-- Collection Section --}}
<section id="our-collections" class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" >
        <!-- Section Header -->
        <div class="flex justify-between items-start mb-16">
            <div>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-2">
                    OUR COLLECTIONS
                </h2>
            </div>
            <button class="bg-gradient-to-r from-yellow-400 to-yellow-500 text-black px-6 py-3 rounded-full font-semibold hover:from-yellow-500 hover:to-yellow-600 transition duration-300 transform hover:scale-105 shadow-lg">
                View More ->
            </button>
        </div>
        <div class="">
            <div class="relative overflow-hidden rounded-3xl shadow-lg">
                <img src="{{ asset('ui/collections/collection-banner.jpg') }}"
                     alt="Our Collections"
                     class="w-full h-auto object-cover">

                <!-- Overlay Text -->
                <div class="absolute inset-0 bg-black bg-opacity-30 flex flex-col items-center justify-center text-center px-4">
                    <h3 class="text-4xl md:text-5xl font-bold text-white mb-4">
                        Summer 2024 Collection
                    </h3>
                    <p class="text-lg md:text-xl text-white max-w-2xl mb-6">
                        Embrace the season with our vibrant and breezy summer styles
                    </p>
                    <button class="bg-yellow-500 text-black px-6 py-3 rounded-full font-semibold hover:bg-yellow-600 transition duration-300 transform hover:scale-105 shadow-lg">
                        Shop Now
                    </button>
                </div>
            </div>
        </div>
    </div>
    
</section>
@endsection
