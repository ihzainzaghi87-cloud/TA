@extends('customer.layouts.app')

@section('title', 'Home')

@push('styles')
<style>
    [x-cloak] {
        display: none !important;
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

{{-- Hero Banner Carousel Section --}}
<section class="relative bg-gradient-to-br from-purple-50 via-white to-pink-50">
    <div x-data="{
        currentSlide: 0,
        autoplay: true,
        interval: null,
        slides: {{ $banners->count() > 0 ? $banners->count() : 3 }},
        init() {
            this.startAutoplay();
        },
        startAutoplay() {
            if (this.autoplay) {
                this.interval = setInterval(() => {
                    this.nextSlide();
                }, 7000);
            }
        },
        stopAutoplay() {
            if (this.interval) {
                clearInterval(this.interval);
            }
        },
        nextSlide() {
            this.currentSlide = (this.currentSlide + 1) % this.slides;
        },
        prevSlide() {
            this.currentSlide = (this.currentSlide - 1 + this.slides) % this.slides;
        },
        goToSlide(index) {
            this.currentSlide = index;
        }
    }" 
    @mouseenter="stopAutoplay()" 
    @mouseleave="startAutoplay()"
    class="relative h-[500px] md:h-[600px] overflow-hidden">
        
        {{-- Carousel Slides --}}
        @if($banners->count() > 0)
            {{-- Database Banners --}}
            @foreach($banners as $index => $banner)
            <div x-show="currentSlide === {{ $index }}"
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0 transform translate-x-full"
                 x-transition:enter-end="opacity-100 transform translate-x-0"
                 x-transition:leave="transition ease-in duration-500"
                 x-transition:leave-start="opacity-100 transform translate-x-0"
                 x-transition:leave-end="opacity-0 transform -translate-x-full"
                 class="absolute inset-0"
                 style="display: none;">
                <img src="{{ $banner->image_url }}" 
                     alt="{{ $banner->title }}" 
                     class="w-full h-full object-cover"
                     loading="{{ $index === 0 ? 'eager' : 'lazy' }}">
                <div class="absolute inset-0 bg-gradient-to-r from-black/50 to-transparent flex items-center">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                        <div class="max-w-2xl">
                            <h2 class="text-4xl md:text-6xl font-bold text-white mb-4 fade-in">
                                {{ $banner->title }}
                            </h2>
                            <a href="#products" 
                               class="inline-block bg-gradient-to-r from-purple-600 to-pink-600 text-white px-8 py-3 rounded-full font-semibold hover:from-purple-700 hover:to-pink-700 transition duration-300 transform hover:scale-105 shadow-lg">
                                Shop Now
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @else
            {{-- Placeholder Banners --}}
            <div x-show="currentSlide === 0"
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 class="absolute inset-0 bg-gradient-to-br from-purple-600 via-purple-500 to-pink-600 flex items-center"
                 style="display: none;">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full text-center">
                    <h2 class="text-4xl md:text-6xl font-bold text-white mb-4 fade-in">
                        Welcome to The Paranoia
                    </h2>
                    <p class="text-xl md:text-2xl text-white/90 mb-8">
                        Discover Amazing Products at Great Prices
                    </p>
                    <a href="#products" 
                       class="inline-block bg-white text-purple-600 px-8 py-3 rounded-full font-semibold hover:bg-gray-100 transition duration-300 transform hover:scale-105 shadow-lg">
                        Explore Now
                    </a>
                </div>
            </div>
            
            <div x-show="currentSlide === 1"
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 class="absolute inset-0 bg-gradient-to-br from-pink-600 via-pink-500 to-purple-600 flex items-center"
                 style="display: none;">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full text-center">
                    <h2 class="text-4xl md:text-6xl font-bold text-white mb-4 fade-in">
                        New Arrivals
                    </h2>
                    <p class="text-xl md:text-2xl text-white/90 mb-8">
                        Fresh Styles Just For You
                    </p>
                    <a href="#products" 
                       class="inline-block bg-white text-pink-600 px-8 py-3 rounded-full font-semibold hover:bg-gray-100 transition duration-300 transform hover:scale-105 shadow-lg">
                        Shop Collection
                    </a>
                </div>
            </div>
            
            <div x-show="currentSlide === 2"
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 class="absolute inset-0 bg-gradient-to-br from-indigo-600 via-indigo-500 to-purple-600 flex items-center"
                 style="display: none;">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full text-center">
                    <h2 class="text-4xl md:text-6xl font-bold text-white mb-4 fade-in">
                        Special Offers
                    </h2>
                    <p class="text-xl md:text-2xl text-white/90 mb-8">
                        Up to 50% Off Selected Items
                    </p>
                    <a href="#products" 
                       class="inline-block bg-white text-indigo-600 px-8 py-3 rounded-full font-semibold hover:bg-gray-100 transition duration-300 transform hover:scale-105 shadow-lg">
                        Get Deals
                    </a>
                </div>
            </div>
        @endif
        
        {{-- Navigation Arrows --}}
        <button @click="prevSlide()" 
                class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/30 hover:bg-white/50 backdrop-blur-sm text-white p-3 rounded-full transition duration-300 transform hover:scale-110">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button @click="nextSlide()" 
                class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/30 hover:bg-white/50 backdrop-blur-sm text-white p-3 rounded-full transition duration-300 transform hover:scale-110">
            <i class="fas fa-chevron-right"></i>
        </button>
        
        {{-- Dot Navigation --}}
        <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex space-x-2">
            <template x-for="(slide, index) in slides" :key="index">
                <button @click="goToSlide(index)" 
                        :class="currentSlide === index ? 'bg-white w-8' : 'bg-white/50 w-3'"
                        class="h-3 rounded-full transition-all duration-300 hover:bg-white"></button>
            </template>
        </div>
    </div>
</section>

{{-- Trust Badges Section --}}
<section class="bg-white py-8 shadow-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <div class="flex flex-col items-center text-center space-y-2">
                <div class="bg-gradient-to-br from-purple-100 to-pink-100 p-4 rounded-full">
                    <i class="fas fa-shipping-fast text-3xl text-purple-600"></i>
                </div>
                <h3 class="font-semibold text-gray-900">Free Shipping</h3>
                <p class="text-sm text-gray-600">On orders over $50</p>
            </div>
            <div class="flex flex-col items-center text-center space-y-2">
                <div class="bg-gradient-to-br from-purple-100 to-pink-100 p-4 rounded-full">
                    <i class="fas fa-lock text-3xl text-purple-600"></i>
                </div>
                <h3 class="font-semibold text-gray-900">Secure Payment</h3>
                <p class="text-sm text-gray-600">100% secure transactions</p>
            </div>
            <div class="flex flex-col items-center text-center space-y-2">
                <div class="bg-gradient-to-br from-purple-100 to-pink-100 p-4 rounded-full">
                    <i class="fas fa-undo text-3xl text-purple-600"></i>
                </div>
                <h3 class="font-semibold text-gray-900">Easy Returns</h3>
                <p class="text-sm text-gray-600">30-day return policy</p>
            </div>
            <div class="flex flex-col items-center text-center space-y-2">
                <div class="bg-gradient-to-br from-purple-100 to-pink-100 p-4 rounded-full">
                    <i class="fas fa-headset text-3xl text-purple-600"></i>
                </div>
                <h3 class="font-semibold text-gray-900">24/7 Support</h3>
                <p class="text-sm text-gray-600">Dedicated customer service</p>
            </div>
        </div>
    </div>
</section>

{{-- Categories Section --}}
<section id="categories" class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Shop by <span class="bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">Category</span>
            </h2>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Discover our wide range of product categories, carefully curated for your needs
            </p>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse($categories as $index => $category)
            {{-- Dynamic Category Card --}}
            <div class="group relative bg-white rounded-xl shadow-md hover:shadow-xl transition duration-300 overflow-hidden cursor-pointer transform hover:-translate-y-2">
                <div class="aspect-square bg-gradient-to-br 
                    @if($index % 4 == 0) from-purple-100 to-pink-100
                    @elseif($index % 4 == 1) from-pink-100 to-purple-100
                    @elseif($index % 4 == 2) from-blue-100 to-indigo-100
                    @else from-green-100 to-teal-100
                    @endif
                    flex items-center justify-center">
                    @if($category->image)
                        <img src="{{ asset('storage/' . $category->image) }}" 
                             alt="{{ $category->name }}" 
                             class="w-20 h-20 object-cover group-hover:scale-110 transition duration-300">
                    @else
                        <i class="fas fa-{{ $category->icon ?? 'box' }} text-6xl 
                            @if($index % 4 == 0) text-purple-600
                            @elseif($index % 4 == 1) text-pink-600
                            @elseif($index % 4 == 2) text-blue-600
                            @else text-green-600
                            @endif
                            group-hover:scale-110 transition duration-300"></i>
                    @endif
                </div>
                <div class="p-4 text-center">
                    <h3 class="font-semibold text-gray-900 
                        @if($index % 4 == 0) group-hover:text-purple-600
                        @elseif($index % 4 == 1) group-hover:text-pink-600
                        @elseif($index % 4 == 2) group-hover:text-blue-600
                        @else group-hover:text-green-600
                        @endif
                        transition duration-300">
                        {{ $category->name }}
                    </h3>
                    <p class="text-sm text-gray-600">{{ $category->products_count }}+ Products</p>
                </div>
            </div>
            @empty
            {{-- Empty State --}}
            <div class="col-span-2 md:col-span-3 lg:col-span-4 text-center py-12">
                <i class="fas fa-box-open text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg">No categories available at the moment</p>
            </div>
            @endforelse
        </div>
    </div>
</section>


{{-- Featured Products Section --}}
<section id="products" class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Featured <span class="bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">Products</span>
            </h2>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Check out our most popular products handpicked just for you
            </p>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($popularProducts as $product)
            {{-- Dynamic Product Card --}}
            <div x-data="{
                showModal: false,
                selectedVariation: null,
                quantity: 1,
                errorMessage: '',
                selectVariation(variation) {
                    this.selectedVariation = variation;
                    this.errorMessage = '';
                },
                addToCart() {
                    this.errorMessage = '';
                    
                    if (!this.selectedVariation) {
                        this.errorMessage = 'Please select a product variation (color and size) before adding to cart';
                        return;
                    }
                    
                    if (this.selectedVariation.stock <= 0) {
                        this.errorMessage = 'Selected variation is out of stock';
                        return;
                    }
                    
                    if (this.quantity < 1) {
                        this.errorMessage = 'Quantity must be at least 1';
                        return;
                    }
                    
                    if (this.quantity > this.selectedVariation.stock) {
                        this.errorMessage = 'Quantity cannot exceed available stock (' + this.selectedVariation.stock + ')';
                        return;
                    }
                    
                    // Submit the form
                    document.getElementById('addToCartForm_{{ $product->id }}').submit();
                }
            }" class="group bg-white rounded-xl shadow-md hover:shadow-xl transition duration-300 overflow-hidden">
                <div class="relative aspect-square bg-gray-200 overflow-hidden">
                    @if($product->images && $product->images->count() > 0)
                        <img src="{{ asset('storage/products/' . $product->images->first()->image) }}" 
                             alt="{{ $product->name }}" 
                             class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    @else
                        <div class="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
                            <i class="fas fa-image text-6xl text-gray-400"></i>
                        </div>
                    @endif
                    
                    {{-- Quick View Overlay --}}
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition duration-300 flex items-center justify-center opacity-0 group-hover:opacity-100">
                        <span class="text-white font-semibold">Quick View</span>
                    </div>

                    {{-- Stock Badge --}}
                    @php
                        $totalStock = $product->variations->sum('stock');
                    @endphp
                    <div class="absolute top-2 right-2">
                        @if($totalStock > 0)
                            <span class="bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-full">In Stock</span>
                        @else
                            <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">Out of Stock</span>
                        @endif
                    </div>
                </div>
                
                <div class="p-4">
                    <h3 class="font-semibold text-gray-900 line-clamp-2 min-h-[3rem] mb-2">
                        {{ $product->name }}
                    </h3>
                    
                    <p class="text-sm text-gray-600 mb-2">
                        <i class="fas fa-tag mr-1"></i>
                        {{ $product->category->name ?? 'Uncategorized' }}
                    </p>
                    
                    {{-- Price --}}
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <span class="text-lg font-bold text-purple-600">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </span>
                            @if($product->point_price)
                            <p class="text-xs text-amber-600">
                                <i class="fas fa-star"></i>
                                {{ number_format($product->point_price, 0, ',', '.') }} Points
                            </p>
                            @endif
                        </div>
                    </div>

                    {{-- Add to Cart Button --}}
                    @auth
                        @if($totalStock > 0)
                            <button @click="showModal = true" 
                                    class="w-full bg-gradient-to-r from-purple-600 to-pink-600 text-white py-2 rounded-lg hover:from-purple-700 hover:to-pink-700 transition duration-300 transform hover:scale-105 font-medium">
                                <i class="fas fa-shopping-cart mr-2"></i>
                                Add to Cart
                            </button>
                        @else
                            <button disabled 
                                    class="w-full bg-gray-400 text-white py-2 rounded-lg cursor-not-allowed opacity-50 font-medium">
                                <i class="fas fa-ban mr-2"></i>
                                Out of Stock
                            </button>
                        @endif
                    @else
                        <a href="{{ route('login') }}" 
                           class="block w-full bg-gradient-to-r from-purple-600 to-pink-600 text-white py-2 rounded-lg hover:from-purple-700 hover:to-pink-700 transition duration-300 text-center font-medium">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Login to Buy
                        </a>
                    @endauth
                </div>

                {{-- Add to Cart Modal --}}
                @auth
                <div x-show="showModal" 
                     x-cloak
                     @click.self="showModal = false"
                     class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center p-4"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0">
                    <div class="bg-white rounded-xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto"
                         @click.stop
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 transform scale-100"
                         x-transition:leave-end="opacity-0 transform scale-95">
                        
                        {{-- Modal Header --}}
                        <div class="flex items-center justify-between p-6 border-b border-gray-200">
                            <h3 class="text-2xl font-bold text-gray-900">Add to Cart</h3>
                            <button @click="showModal = false" class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-times text-2xl"></i>
                            </button>
                        </div>

                        {{-- Modal Content --}}
                        <div class="p-6">
                            <div class="grid md:grid-cols-2 gap-6">
                                {{-- Product Image --}}
                                <div class="aspect-square bg-gray-200 rounded-lg overflow-hidden">
                                    @if($product->images && $product->images->count() > 0)
                                        <img src="{{ asset('storage/products/' . $product->images->first()->image) }}" 
                                             alt="{{ $product->name }}"
                                             class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <i class="fas fa-image text-6xl text-gray-400"></i>
                                        </div>
                                    @endif
                                </div>

                                {{-- Product Details & Form --}}
                                <div>
                                    <h4 class="text-xl font-bold text-gray-900 mb-2">{{ $product->name }}</h4>
                                    <p class="text-sm text-gray-600 mb-4">
                                        <i class="fas fa-tag mr-1"></i>
                                        {{ $product->category->name ?? 'Uncategorized' }}
                                    </p>
                                    
                                    <div class="mb-4">
                                        <p class="text-2xl font-bold text-purple-600">
                                            Rp {{ number_format($product->price, 0, ',', '.') }}
                                        </p>
                                        @if($product->point_price)
                                        <p class="text-amber-600">
                                            <i class="fas fa-star mr-1"></i>
                                            {{ number_format($product->point_price, 0, ',', '.') }} Points
                                        </p>
                                        @endif
                                    </div>

                                    @if($product->description)
                                    <p class="text-gray-600 text-sm mb-6">{{ Str::limit($product->description, 150) }}</p>
                                    @endif

                                    {{-- Error Message Display --}}
                                    <div x-show="errorMessage" 
                                         x-cloak
                                         class="mb-4 p-3 bg-red-50 border border-red-200 text-red-800 rounded-lg text-sm"
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 transform scale-95"
                                         x-transition:enter-end="opacity-100 transform scale-100">
                                        <div class="flex items-start">
                                            <i class="fas fa-exclamation-circle text-red-500 mt-0.5 mr-2"></i>
                                            <span x-text="errorMessage"></span>
                                        </div>
                                    </div>

                                    <form id="addToCartForm_{{ $product->id }}" action="{{ route('cart.store') }}" method="POST">
                                        @csrf
                                        
                                        {{-- Variation Selector --}}
                                        <div class="mb-6">
                                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                                Select Variation <span class="text-red-500">*</span>
                                            </label>
                                            <div class="space-y-2 max-h-48 overflow-y-auto">
                                                @foreach($product->variations as $variation)
                                                <div @click="if({{ $variation->stock }} > 0) selectVariation({ id: {{ $variation->id }}, color: '{{ $variation->color }}', size: '{{ $variation->size }}', stock: {{ $variation->stock }} })"
                                                     :class="selectedVariation && selectedVariation.id === {{ $variation->id }} ? 'border-purple-600 bg-purple-50' : 'border-gray-300'"
                                                     class="border-2 rounded-lg p-3 {{ $variation->stock <= 0 ? 'opacity-50 cursor-not-allowed bg-gray-100' : 'cursor-pointer hover:border-purple-400' }} transition duration-200">
                                                    <div class="flex items-center justify-between">
                                                        <div class="flex items-center gap-2">
                                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                                {{ $variation->color }}
                                                            </span>
                                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-pink-100 text-pink-800">
                                                                {{ $variation->size }}
                                                            </span>
                                                        </div>
                                                        <span class="text-sm {{ $variation->stock > 0 ? 'text-green-600' : 'text-red-600' }} font-medium">
                                                            @if($variation->stock > 0)
                                                                Stock: {{ $variation->stock }}
                                                            @else
                                                                Out of Stock
                                                            @endif
                                                        </span>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                            <input type="hidden" name="variation_id" :value="selectedVariation ? selectedVariation.id : ''" required>
                                        </div>

                                        {{-- Quantity Selector --}}
                                        <div class="mb-6">
                                            <label class="block text-sm font-medium text-gray-700 mb-3">Quantity</label>
                                            <div class="flex items-center gap-4">
                                                <div class="flex items-center border border-gray-300 rounded-lg">
                                                    <button type="button" 
                                                            @click="if(quantity > 1) quantity--" 
                                                            class="px-4 py-2 text-gray-600 hover:bg-gray-100 transition duration-200">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                    <input type="number" 
                                                           name="quantity"
                                                           x-model="quantity"
                                                           min="1"
                                                           :max="selectedVariation ? selectedVariation.stock : 1"
                                                           class="w-20 text-center border-0 focus:ring-0 py-2">
                                                    <button type="button" 
                                                            @click="if(selectedVariation && quantity < selectedVariation.stock) quantity++" 
                                                            class="px-4 py-2 text-gray-600 hover:bg-gray-100 transition duration-200">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                                <span class="text-sm text-gray-600" x-show="selectedVariation">
                                                    Max: <span x-text="selectedVariation ? selectedVariation.stock : 0"></span>
                                                </span>
                                            </div>
                                        </div>

                                        {{-- Submit Button --}}
                                        <button type="button"
                                                @click="addToCart()"
                                                :disabled="!selectedVariation"
                                                :class="!selectedVariation ? 'opacity-50 cursor-not-allowed' : 'hover:from-purple-700 hover:to-pink-700'"
                                                class="w-full bg-gradient-to-r from-purple-600 to-pink-600 text-white py-3 rounded-lg transition duration-300 font-bold text-lg">
                                            <i class="fas fa-shopping-cart mr-2"></i>
                                            Add to Cart
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endauth
            </div>
            @empty
            {{-- Empty State --}}
            <div class="col-span-1 sm:col-span-2 lg:col-span-4 text-center py-12">
                <i class="fas fa-shopping-bag text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg">No products available at the moment</p>
                <p class="text-gray-400 text-sm mt-2">Check back soon for exciting new products!</p>
            </div>
            @endforelse
        </div>
        
        @if($popularProducts->count() > 0)
        <div class="text-center mt-12">
            <a href="#" 
               class="inline-block bg-gradient-to-r from-purple-600 to-pink-600 text-white px-8 py-3 rounded-full font-semibold hover:from-purple-700 hover:to-pink-700 transition duration-300 transform hover:scale-105 shadow-lg">
                View All Products
            </a>
        </div>
        @endif
    </div>
</section>


{{-- Special Offers Section --}}
<section class="py-16 bg-gradient-to-br from-purple-600 via-purple-500 to-pink-600 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-2 gap-8 items-center">
            <div>
                <h2 class="text-3xl md:text-5xl font-bold mb-4">
                    Special Offer!
                </h2>
                <p class="text-xl mb-6 text-white/90">
                    Get up to 50% OFF on selected items this week only!
                </p>
                <div class="flex items-center space-x-4 mb-8">
                    <div class="bg-white/20 backdrop-blur-sm px-6 py-4 rounded-lg text-center">
                        <div class="text-3xl font-bold">23</div>
                        <div class="text-sm">Hours</div>
                    </div>
                    <div class="bg-white/20 backdrop-blur-sm px-6 py-4 rounded-lg text-center">
                        <div class="text-3xl font-bold">45</div>
                        <div class="text-sm">Minutes</div>
                    </div>
                    <div class="bg-white/20 backdrop-blur-sm px-6 py-4 rounded-lg text-center">
                        <div class="text-3xl font-bold">32</div>
                        <div class="text-sm">Seconds</div>
                    </div>
                </div>
                <a href="#products" 
                   class="inline-block bg-white text-purple-600 px-8 py-3 rounded-full font-semibold hover:bg-gray-100 transition duration-300 transform hover:scale-105 shadow-lg">
                    Shop Now
                </a>
            </div>
            <div class="hidden md:flex items-center justify-center">
                <div class="relative">
                    <div class="absolute inset-0 bg-white/20 backdrop-blur-sm rounded-full animate-ping"></div>
                    <div class="relative bg-white/10 backdrop-blur-sm p-12 rounded-full">
                        <i class="fas fa-gift text-9xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Newsletter Section --}}
<section id="newsletter" class="py-16 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="bg-white rounded-2xl shadow-xl p-8 md:p-12">
            <i class="fas fa-envelope text-5xl text-purple-600 mb-6"></i>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Subscribe to Our Newsletter
            </h2>
            <p class="text-gray-600 mb-8 max-w-2xl mx-auto">
                Stay updated with our latest products, exclusive offers, and special promotions. Join thousands of happy subscribers!
            </p>
            <form class="flex flex-col sm:flex-row gap-4 max-w-xl mx-auto">
                <input type="email" 
                       placeholder="Enter your email address" 
                       class="flex-1 px-6 py-3 rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent">
                <button type="submit" 
                        class="bg-gradient-to-r from-purple-600 to-pink-600 text-white px-8 py-3 rounded-full font-semibold hover:from-purple-700 hover:to-pink-700 transition duration-300 transform hover:scale-105 shadow-lg whitespace-nowrap">
                    Subscribe Now
                </button>
            </form>
            <p class="text-sm text-gray-500 mt-4">
                We respect your privacy. Unsubscribe at any time.
            </p>
        </div>
    </div>
</section>
@endsection
