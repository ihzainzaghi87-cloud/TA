@extends('customer.layouts.app')

@section('title', $product->name)

@push('styles')
<style>
    .image-thumbnail {
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .image-thumbnail:hover {
        transform: scale(1.05);
        border-color: #f97316;
    }
    .image-thumbnail.active {
        border-color: #f97316;
        border-width: 3px;
    }
</style>
@endpush

@section('content')
<!-- Breadcrumb -->
<section class="bg-gray-50 py-4 px-6 md:px-12">
    <nav class="text-sm text-gray-600">
        <a href="{{ route('home') }}" class="hover:text-orange-600">Home</a>
        <span class="mx-2">/</span>
        <a href="{{ route('rewards') }}" class="hover:text-orange-600">Rewards</a>
        <span class="mx-2">/</span>
        @if($product->category)
            <a href="{{ route('rewards', ['category' => $product->category_id]) }}" class="hover:text-orange-600">
                {{ $product->category->name }}
            </a>
            <span class="mx-2">/</span>
        @endif
        <span class="text-gray-900 font-medium">{{ $product->name }}</span>
    </nav>
</section>

<!-- Product Detail Section -->
<section class="bg-white py-12 px-6 md:px-12">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Product Images -->
            <div>
                <!-- Main Image -->
                <div class="mb-4 relative group">
                    @if($product->images->isNotEmpty())
                        <img 
                            id="mainImage" 
                            src="{{ asset('storage/' . $product->images->first()->image_path) }}" 
                            alt="{{ $product->name }}"
                            class="w-full h-[500px] object-cover rounded-2xl shadow-lg"
                        >
                    @else
                        <div class="w-full h-[500px] bg-gray-200 flex items-center justify-center rounded-2xl">
                            <p class="text-gray-400 text-lg">Tidak ada gambar</p>
                        </div>
                    @endif
                </div>

                <!-- Image Thumbnails -->
                @if($product->images->count() > 1)
                    <div class="grid grid-cols-4 gap-3">
                        @foreach($product->images as $index => $image)
                            <img 
                                src="{{ asset('storage/' . $image->image_path) }}" 
                                alt="{{ $product->name }}"
                                class="image-thumbnail w-full h-24 object-cover rounded-lg border-2 border-gray-200 {{ $index == 0 ? 'active' : '' }}"
                                onclick="changeMainImage('{{ asset('storage/' . $image->image_path) }}', this)"
                            >
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Product Info -->
            <div>
                <!-- Category Badge -->
                @if($product->category)
                    <span class="inline-block bg-red-100 text-red-700 text-sm font-semibold px-4 py-1 rounded-full mb-4">
                        {{ $product->category->name }}
                    </span>
                @endif

                <!-- Product Name -->
                <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $product->name }}</h1>

                <!-- Price -->
                <div class="mb-6">
                    <p class="text-5xl font-black text-gray-900">
                        {{ number_format($product->point_price, 0, ',', '.') }} Points
                    </p>
                </div>

                <!-- Stock Status -->
                <div class="mb-6 p-4 {{ $totalStock > 0 ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200' }} border-2 rounded-lg">
                    <p class="text-sm font-semibold {{ $totalStock > 0 ? 'text-green-700' : 'text-red-700' }}">
                        @if($totalStock > 0)
                            ✓ In Stock ({{ $totalStock }} items available)
                        @else
                            ✕ Out of Stock
                        @endif
                    </p>
                </div>

                <!-- Variations -->
                <form method="POST" action="{{ route('cart.store') }}" id="addToCartForm" class="mb-6">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="variation_id" id="selectedVariationId">
                    <input type="hidden" name="quantity" value="1">

                    <!-- Color Selection -->
                    @if($colors->count() > 0)
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Color:</label>
                            <div class="flex flex-wrap gap-3">
                                @foreach($colors as $color)
                                    <button 
                                        type="button"
                                        onclick="selectColor('{{ $color }}')"
                                        class="color-option px-6 py-3 border-2 border-gray-300 rounded-lg hover:border-orange-500 transition-all"
                                        data-color="{{ $color }}"
                                    >
                                        {{ $color }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Size Selection -->
                    @if($sizes->count() > 0)
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Size:</label>
                            <div class="flex flex-wrap gap-3">
                                @foreach($sizes as $size)
                                    <button 
                                        type="button"
                                        onclick="selectSize('{{ $size }}')"
                                        class="size-option px-6 py-3 border-2 border-gray-300 rounded-lg hover:border-orange-500 transition-all"
                                        data-size="{{ $size }}"
                                    >
                                        {{ $size }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Add to Cart Button -->
                    <button 
                        type="submit"
                        class="w-full bg-gradient-to-r from-red-600 to-orange-500 text-white font-bold py-4 rounded-xl hover:from-red-700 hover:to-orange-600 active:scale-95 transition-all duration-200 shadow-lg hover:shadow-xl mb-4"
                        @if($totalStock == 0) disabled @endif
                    >
                        @if($totalStock > 0)
                            🎁 Redeem with Points
                        @else
                            Out of Stock
                        @endif
                    </button>
                </form>

                <!-- Product Features -->
                <div class="grid grid-cols-3 gap-4 mb-8">
                    <div class="text-center p-4 bg-orange-50 rounded-lg">
                        <svg class="w-8 h-8 mx-auto mb-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        <p class="text-xs font-semibold text-gray-700">Pengiriman Cepat</p>
                    </div>
                    <div class="text-center p-4 bg-orange-50 rounded-lg">
                        <svg class="w-8 h-8 mx-auto mb-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-xs font-semibold text-gray-700">Produk Original</p>
                    </div>
                    <div class="text-center p-4 bg-orange-50 rounded-lg">
                        <svg class="w-8 h-8 mx-auto mb-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <p class="text-xs font-semibold text-gray-700">Support 24/7</p>
                    </div>
                </div>

                <!-- Product Description -->
                <div class="border-t pt-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Product Description</h3>
                    <div class="text-gray-700 leading-relaxed prose max-w-none">
                        {{ $product->description ?? 'Tidak ada deskripsi untuk produk ini.' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Products -->
@if($relatedProducts->count() > 0)
<section class="bg-gray-50 py-12 px-6 md:px-12">
    <div class="max-w-7xl mx-auto">
        <h2 class="text-3xl font-bold text-gray-900 mb-8">Related Reward Products</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($relatedProducts as $relatedProduct)
                <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden">
                    <div class="relative overflow-hidden">
                        @if($relatedProduct->images->isNotEmpty())
                            <img 
                                src="{{ asset('storage/' . $relatedProduct->images->first()->image_path) }}" 
                                alt="{{ $relatedProduct->name }}"
                                class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-500"
                            >
                        @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-400">No Image</span>
                            </div>
                        @endif
                    </div>
                    <div class="p-4">
                        <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2">{{ $relatedProduct->name }}</h3>
                        <p class="text-xl font-black text-gray-900 mb-3">
                            {{ number_format($relatedProduct->price, 0, ',', '.') }} Points
                        </p>
                        <a href="{{ route('reward.detail', $relatedProduct->slug) }}" 
                           class="block w-full bg-gradient-to-r from-red-600 to-orange-500 text-white font-semibold py-2 rounded-lg hover:from-red-700 hover:to-orange-600 transition-all text-center text-sm">
                            View Details
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@push('scripts')
<script>
    // Change main image on thumbnail click
    function changeMainImage(imageSrc, element) {
        document.getElementById('mainImage').src = imageSrc;
        document.querySelectorAll('.image-thumbnail').forEach(thumb => {
            thumb.classList.remove('active');
        });
        element.classList.add('active');
    }

    let selectedColor = null;
    let selectedSize = null;

    // Color selection
    function selectColor(color) {
        selectedColor = color;
        document.querySelectorAll('.color-option').forEach(btn => {
            btn.classList.remove('border-orange-600', 'bg-orange-50');
            btn.classList.add('border-gray-300');
        });
        event.target.classList.add('border-orange-600', 'bg-orange-50');
        event.target.classList.remove('border-gray-300');
        updateVariation();
    }

    // Size selection
    function selectSize(size) {
        selectedSize = size;
        document.querySelectorAll('.size-option').forEach(btn => {
            btn.classList.remove('border-orange-600', 'bg-orange-50');
            btn.classList.add('border-gray-300');
        });
        event.target.classList.add('border-orange-600', 'bg-orange-50');
        event.target.classList.remove('border-gray-300');
        updateVariation();
    }

    // Update variation based on selection
    function updateVariation() {
        @if($availableVariations->count() > 0)
            const variations = @json($availableVariations->values());
            const selected = variations.find(v => 
                (selectedColor === null || v.color === selectedColor) &&
                (selectedSize === null || v.size === selectedSize)
            );
            
            if (selected) {
                document.getElementById('selectedVariationId').value = selected.id;
            }
        @endif
    }

    // Form submission validation
    document.getElementById('addToCartForm')?.addEventListener('submit', function(e) {
        const variationId = document.getElementById('selectedVariationId').value;
        if (!variationId) {
            e.preventDefault();
            alert('Please select product variations (color/size)');
        }
    });
</script>
@endpush
@endsection
