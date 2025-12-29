@extends('customer.layouts.app')

@section('title', 'All Products')

@section('content')
<!-- Hero Section -->
<section class="bg-[#E5DECC]">
    <!-- Text Content -->
    <div class="py-20 px-6 md:px-12">
        <p class="text-sm md:text-base text-gray-700 mb-2">Home / Products</p>
        <h1 class="text-4xl md:text-5xl font-bold mb-4 text-left">Explore Our Products</h1>
        <p class="text-lg md:text-xl mb-8">Discover a wide range of products tailored to your needs.</p>
</section>
<!-- Products Grid -->
<section class="bg-white py-12 px-6 md:px-12">
    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-8">Sedang Popular</h2>
    <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 max-w-sm overflow-hidden">
    <!-- Image Container -->
    <div class="relative overflow-hidden">
        <img 
            src="https://images.unsplash.com/photo-1576871337632-b9aef4c17ab9?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" 
            alt="Hot 120" 
            class="w-full h-56 object-cover group-hover:scale-105 transition-transform duration-500"
        >
        
        <!-- Sold Badge with subtle design -->
        <div class="absolute top-4 left-4 bg-white/95 text-gray-900 font-bold text-sm px-3 py-1.5 rounded-full">
            🔥 120 sold
        </div>
    </div>
    
    <!-- Product Content -->
    <div class="p-6">
        <div class="mb-4">
            <span class="text-sm font-medium text-red-600 bg-red-50 px-2 py-1 rounded">
                Hot Puma
            </span>
        </div>
        
        <h3 class="text-2xl font-bold text-gray-900 mb-2">
            Hot 120
        </h3>
        
        <p class="text-3xl font-black text-gray-900 mb-6">
            Rp 18.500.000
        </p>
        
        <button class="w-full bg-gradient-to-r from-red-600 to-orange-500 text-white font-bold py-4 rounded-xl hover:from-red-700 hover:to-orange-600 active:scale-95 transition-all duration-200 shadow-lg hover:shadow-xl">
            🛒 Add to Cart
        </button>
    </div>
</div>
</section>
@endsection