@extends('customer.layouts.app')

@section('title', 'Home')

@section('content')
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
            {{-- Category Card 1 --}}
            <div class="group relative bg-white rounded-xl shadow-md hover:shadow-xl transition duration-300 overflow-hidden cursor-pointer transform hover:-translate-y-2">
                <div class="aspect-square bg-gradient-to-br from-purple-100 to-pink-100 flex items-center justify-center">
                    <i class="fas fa-laptop text-6xl text-purple-600 group-hover:scale-110 transition duration-300"></i>
                </div>
                <div class="p-4 text-center">
                    <h3 class="font-semibold text-gray-900 group-hover:text-purple-600 transition duration-300">Electronics</h3>
                    <p class="text-sm text-gray-600">50+ Products</p>
                </div>
            </div>
            
            {{-- Category Card 2 --}}
            <div class="group relative bg-white rounded-xl shadow-md hover:shadow-xl transition duration-300 overflow-hidden cursor-pointer transform hover:-translate-y-2">
                <div class="aspect-square bg-gradient-to-br from-pink-100 to-purple-100 flex items-center justify-center">
                    <i class="fas fa-tshirt text-6xl text-pink-600 group-hover:scale-110 transition duration-300"></i>
                </div>
                <div class="p-4 text-center">
                    <h3 class="font-semibold text-gray-900 group-hover:text-pink-600 transition duration-300">Fashion</h3>
                    <p class="text-sm text-gray-600">100+ Products</p>
                </div>
            </div>
            
            {{-- Category Card 3 --}}
            <div class="group relative bg-white rounded-xl shadow-md hover:shadow-xl transition duration-300 overflow-hidden cursor-pointer transform hover:-translate-y-2">
                <div class="aspect-square bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center">
                    <i class="fas fa-home text-6xl text-blue-600 group-hover:scale-110 transition duration-300"></i>
                </div>
                <div class="p-4 text-center">
                    <h3 class="font-semibold text-gray-900 group-hover:text-blue-600 transition duration-300">Home & Living</h3>
                    <p class="text-sm text-gray-600">75+ Products</p>
                </div>
            </div>
            
            {{-- Category Card 4 --}}
            <div class="group relative bg-white rounded-xl shadow-md hover:shadow-xl transition duration-300 overflow-hidden cursor-pointer transform hover:-translate-y-2">
                <div class="aspect-square bg-gradient-to-br from-green-100 to-teal-100 flex items-center justify-center">
                    <i class="fas fa-dumbbell text-6xl text-green-600 group-hover:scale-110 transition duration-300"></i>
                </div>
                <div class="p-4 text-center">
                    <h3 class="font-semibold text-gray-900 group-hover:text-green-600 transition duration-300">Sports</h3>
                    <p class="text-sm text-gray-600">40+ Products</p>
                </div>
            </div>
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
            {{-- Product Card Placeholder 1 --}}
            <div class="group bg-white rounded-xl shadow-md hover:shadow-xl transition duration-300 overflow-hidden">
                <div class="relative aspect-square bg-gray-200 overflow-hidden">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <i class="fas fa-image text-6xl text-gray-400"></i>
                    </div>
                    <div class="absolute top-4 right-4">
                        <span class="bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full">-20%</span>
                    </div>
                    <button class="absolute top-4 left-4 bg-white p-2 rounded-full shadow-md opacity-0 group-hover:opacity-100 transition duration-300 hover:bg-purple-600 hover:text-white">
                        <i class="fas fa-heart"></i>
                    </button>
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">Premium Product Name</h3>
                    <div class="flex items-center mb-2">
                        <div class="flex text-yellow-400 text-sm">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <span class="text-sm text-gray-600 ml-2">(4.5)</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-lg font-bold text-purple-600">$80.00</span>
                            <span class="text-sm text-gray-500 line-through ml-2">$100.00</span>
                        </div>
                        <button class="bg-gradient-to-r from-purple-600 to-pink-600 text-white p-2 rounded-lg hover:from-purple-700 hover:to-pink-700 transition duration-300 transform hover:scale-110">
                            <i class="fas fa-shopping-cart"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            {{-- Product Card Placeholder 2 --}}
            <div class="group bg-white rounded-xl shadow-md hover:shadow-xl transition duration-300 overflow-hidden">
                <div class="relative aspect-square bg-gray-200 overflow-hidden">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <i class="fas fa-image text-6xl text-gray-400"></i>
                    </div>
                    <div class="absolute top-4 right-4">
                        <span class="bg-green-500 text-white text-xs font-bold px-3 py-1 rounded-full">New</span>
                    </div>
                    <button class="absolute top-4 left-4 bg-white p-2 rounded-full shadow-md opacity-0 group-hover:opacity-100 transition duration-300 hover:bg-purple-600 hover:text-white">
                        <i class="fas fa-heart"></i>
                    </button>
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">Amazing Product Title</h3>
                    <div class="flex items-center mb-2">
                        <div class="flex text-yellow-400 text-sm">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <span class="text-sm text-gray-600 ml-2">(5.0)</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-lg font-bold text-purple-600">$120.00</span>
                        </div>
                        <button class="bg-gradient-to-r from-purple-600 to-pink-600 text-white p-2 rounded-lg hover:from-purple-700 hover:to-pink-700 transition duration-300 transform hover:scale-110">
                            <i class="fas fa-shopping-cart"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            {{-- Product Card Placeholder 3 --}}
            <div class="group bg-white rounded-xl shadow-md hover:shadow-xl transition duration-300 overflow-hidden">
                <div class="relative aspect-square bg-gray-200 overflow-hidden">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <i class="fas fa-image text-6xl text-gray-400"></i>
                    </div>
                    <button class="absolute top-4 left-4 bg-white p-2 rounded-full shadow-md opacity-0 group-hover:opacity-100 transition duration-300 hover:bg-purple-600 hover:text-white">
                        <i class="fas fa-heart"></i>
                    </button>
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">Bestseller Product</h3>
                    <div class="flex items-center mb-2">
                        <div class="flex text-yellow-400 text-sm">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="far fa-star"></i>
                        </div>
                        <span class="text-sm text-gray-600 ml-2">(4.0)</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-lg font-bold text-purple-600">$65.00</span>
                        </div>
                        <button class="bg-gradient-to-r from-purple-600 to-pink-600 text-white p-2 rounded-lg hover:from-purple-700 hover:to-pink-700 transition duration-300 transform hover:scale-110">
                            <i class="fas fa-shopping-cart"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            {{-- Product Card Placeholder 4 --}}
            <div class="group bg-white rounded-xl shadow-md hover:shadow-xl transition duration-300 overflow-hidden">
                <div class="relative aspect-square bg-gray-200 overflow-hidden">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <i class="fas fa-image text-6xl text-gray-400"></i>
                    </div>
                    <div class="absolute top-4 right-4">
                        <span class="bg-purple-500 text-white text-xs font-bold px-3 py-1 rounded-full">Hot</span>
                    </div>
                    <button class="absolute top-4 left-4 bg-white p-2 rounded-full shadow-md opacity-0 group-hover:opacity-100 transition duration-300 hover:bg-purple-600 hover:text-white">
                        <i class="fas fa-heart"></i>
                    </button>
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">Trending Product</h3>
                    <div class="flex items-center mb-2">
                        <div class="flex text-yellow-400 text-sm">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <span class="text-sm text-gray-600 ml-2">(5.0)</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-lg font-bold text-purple-600">$95.00</span>
                        </div>
                        <button class="bg-gradient-to-r from-purple-600 to-pink-600 text-white p-2 rounded-lg hover:from-purple-700 hover:to-pink-700 transition duration-300 transform hover:scale-110">
                            <i class="fas fa-shopping-cart"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-12">
            <a href="#" class="inline-block bg-gradient-to-r from-purple-600 to-pink-600 text-white px-8 py-3 rounded-full font-semibold hover:from-purple-700 hover:to-pink-700 transition duration-300 transform hover:scale-105 shadow-lg">
                View All Products
            </a>
        </div>
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
