@extends('customer.layouts.app')

@section('title', $product->name)

@push('styles')
<style>
    [x-cloak] { display: none !important; }

    /* Image Gallery Styles */
    .thumbnail-image {
        transition: all 0.3s ease;
        cursor: pointer;
        border: 2px solid transparent;
        background: #F3F5F9; /* Abu muda agar kontras dengan putih */
    }

    .thumbnail-image:hover,
    .thumbnail-image.active {
        border-color: #1A1A1D; /* Border HITAM saat aktif */
        opacity: 1;
        transform: scale(1.05);
    }

    .thumbnail-image:not(.active) {
        opacity: 0.6;
    }

    /* Variation Button Styles (Black & White) */
    .variation-btn {
        background: #fff;
        border: 1px solid #e5e7eb;
        color: #1A1A1D;
        transition: all 0.2s ease;
    }

    .variation-btn:hover:not(.disabled) {
        border-color: #1A1A1D;
        background: #f4f4f5;
    }

    .variation-btn.selected {
        background: #1A1A1D; /* Hitam Pekat */
        border-color: #1A1A1D;
        color: #fff; /* Teks Putih */
        font-weight: 700;
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    }

    .variation-btn.disabled {
        opacity: 0.3;
        cursor: not-allowed;
        background: #f3f4f6;
        color: #9ca3af;
        text-decoration: line-through;
    }

    /* Quantity Controls */
    .qty-btn {
        background: #fff;
        border: 1px solid #e5e7eb;
        color: #1A1A1D;
        transition: all 0.2s ease;
    }

    .qty-btn:hover:not(:disabled) {
        background: #1A1A1D;
        color: #fff;
        border-color: #1A1A1D;
    }

    .qty-btn:disabled {
        opacity: 0.3;
        cursor: not-allowed;
    }

    .qty-input {
        background: #fff;
        border-top: 1px solid #e5e7eb;
        border-bottom: 1px solid #e5e7eb;
        border-left: none;
        border-right: none;
        color: #1A1A1D;
    }

    .qty-input:focus { outline: none; }

    /* Info & Spec Cards */
    .info-card, .spec-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 20px;
    }

    /* Feature Card */
    .feature-card {
        background: #F3F5F9; /* Abu Sangat Muda */
        border: 1px solid #e5e7eb;
        color: #1A1A1D;
    }

    /* Main Image Container */
    .main-image-container {
        cursor: zoom-in;
        position: relative;
        background: #F3F5F9;
        border: 1px solid #f3f4f6;
    }

    .main-image-container img {
        mix-blend-mode: multiply;
    }

    .main-image-container:hover .zoom-hint { opacity: 1; }

    .zoom-hint {
        position: absolute;
        bottom: 16px; right: 16px;
        background: #1A1A1D; color: white;
        padding: 8px 12px; border-radius: 8px;
        font-size: 12px; opacity: 0;
        transition: opacity 0.3s ease;
        display: flex; align-items: center; gap: 6px;
    }

    /* Related Product Card */
    .product-card {
        background: white;
        border: 1px solid #f3f4f6;
        border-radius: 20px;
        transition: all 0.3s ease;
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        border-color: #e5e7eb;
    }

    /* Lightbox Styles (Sama seperti sebelumnya) */
    .lightbox {
        position: fixed; inset: 0; background: rgba(255, 255, 255, 0.98); z-index: 9999;
        display: flex; align-items: center; justify-content: center;
        opacity: 0; visibility: hidden; transition: all 0.3s ease;
    }
    .lightbox.active { opacity: 1; visibility: visible; }
    .lightbox-content {
        position: relative; max-width: 90vw; max-height: 90vh;
        transform: scale(0.9); transition: transform 0.3s ease;
    }
    .lightbox.active .lightbox-content { transform: scale(1); }
    .lightbox-image {
        max-width: 90vw; max-height: 85vh; object-fit: contain;
        border-radius: 8px;
    }
    .lightbox-close, .lightbox-nav {
        background: #1A1A1D; color: white; border-radius: 50%;
        cursor: pointer; display: flex; align-items: center; justify-content: center;
        transition: all 0.2s ease;
    }
    .lightbox-close { position: absolute; top: -40px; right: 0; width: 36px; height: 36px; }
    .lightbox-nav { position: absolute; top: 50%; transform: translateY(-50%); width: 48px; height: 48px; }
    .lightbox-prev { left: -70px; } .lightbox-next { right: -70px; }
    .lightbox-counter {
        position: absolute; bottom: -40px; left: 50%; transform: translateX(-50%);
        color: #1A1A1D; font-size: 14px; font-weight: bold;
    }
    .lightbox-thumbnails {
        position: absolute; bottom: -80px; left: 50%; transform: translateX(-50%);
        display: flex; gap: 8px;
    }
    .lightbox-thumb {
        width: 50px; height: 50px; border-radius: 6px; overflow: hidden;
        cursor: pointer; opacity: 0.5; transition: all 0.2s ease; border: 2px solid transparent;
    }
    .lightbox-thumb:hover, .lightbox-thumb.active { opacity: 1; border-color: #1A1A1D; }
    .lightbox-thumb img { width: 100%; height: 100%; object-fit: cover; }

    @media (max-width: 768px) {
        .lightbox-nav { width: 40px; height: 40px; }
        .lightbox-prev { left: 10px; } .lightbox-next { right: 10px; }
        .lightbox-thumbnails { display: none; }
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-white text-[#1A1A1D]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <nav class="flex items-center mb-8 text-sm">
            <a href="{{ route('home') }}" class="text-gray-400 hover:text-black transition-colors">
                <i class="fas fa-home"></i>
            </a>
            <i class="fas fa-chevron-right text-gray-300 mx-3 text-xs"></i>
            <a href="{{ route('home') }}#products" class="text-gray-400 hover:text-black transition-colors">Products</a>
            <i class="fas fa-chevron-right text-gray-300 mx-3 text-xs"></i>
            <span class="text-black font-bold">{{ Str::limit($product->name, 30) }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 mb-16">
            <div class="space-y-4">
                <div class="rounded-[30px] overflow-hidden aspect-square shadow-sm border border-gray-100 main-image-container" onclick="openLightbox(0)">
                    @if($product->images->count() > 0)
                        <img id="mainImage"
                             src="{{ asset('storage/products/' . $product->images->first()->image) }}"
                             alt="{{ $product->name }}"
                             class="w-full h-full object-contain p-8 transition-transform duration-500 hover:scale-105">
                        <div class="zoom-hint">
                            <i class="fas fa-search-plus"></i>
                            <span>Click to enlarge</span>
                        </div>
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-[#F3F5F9]">
                            <div class="text-center">
                                <i class="fas fa-image text-6xl text-gray-300 mb-4"></i>
                                <p class="text-gray-400 text-sm">No images available</p>
                            </div>
                        </div>
                    @endif
                </div>

                @if($product->images->count() > 1)
                <div class="grid grid-cols-4 sm:grid-cols-5 gap-3">
                    @foreach($product->images as $index => $image)
                    <div class="aspect-square rounded-2xl overflow-hidden {{ $index == 0 ? 'active' : '' }} thumbnail-image cursor-pointer"
                         onclick="changeMainImage('{{ asset('storage/products/' . $image->image) }}', this)">
                        <img src="{{ asset('storage/products/' . $image->image) }}"
                             alt="{{ $product->name }} - {{ $index + 1 }}"
                             class="w-full h-full object-contain p-1 mix-blend-multiply">
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            @php
                $colors = $product->variations->pluck('color')->filter()->unique()->values();
                $sizes = $product->variations->pluck('size')->filter()->unique()->values();
                $variationsData = $product->variations->map(function($v) {
                    return [
                        'id' => $v->id,
                        'color' => $v->color,
                        'size' => $v->size,
                        'stock' => $v->stock,
                    ];
                });
            @endphp

            <div x-data="{
                selectedColor: '{{ request('color') ?? '' }}',
                selectedSize: '{{ request('size') ?? '' }}',
                selectedVariation: null,
                quantity: 1,
                errorMessage: '',
                variations: {{ $variationsData->toJson() }},
                get currentStock() { return this.selectedVariation ? this.selectedVariation.stock : {{ $totalStock }}; },
                get hasSelection() { return this.selectedColor || this.selectedSize; },
                get hasBothSelections() { return this.selectedColor && this.selectedSize; },
                selectColor(color) { this.selectedColor = color; this.updateVariation(); },
                selectSize(size) { this.selectedSize = size; this.updateVariation(); },
                updateVariation() {
                    const match = this.variations.find(v => {
                        const colorMatch = this.selectedColor ? v.color === this.selectedColor : true;
                        const sizeMatch = this.selectedSize ? v.size === this.selectedSize : true;
                        return colorMatch && sizeMatch;
                    });
                    if (match) {
                        this.selectedVariation = match;
                        this.errorMessage = '';
                        document.getElementById('variation_id').value = match.id;
                        if (this.quantity > match.stock) { this.quantity = Math.max(1, match.stock); }
                    } else {
                        this.selectedVariation = null;
                        document.getElementById('variation_id').value = '';
                        if (this.selectedColor && this.selectedSize) { this.errorMessage = 'Kombinasi tidak tersedia'; }
                    }
                },
                incrementQuantity() { if (this.currentStock > 0 && this.quantity < this.currentStock) { this.quantity++; } },
                decrementQuantity() { if (this.quantity > 1) { this.quantity--; } },
                validateForm() {
                    this.errorMessage = '';
                    const hasVariations = {{ $product->variations->count() }} > 0;
                    if (hasVariations) {
                        if (!this.selectedColor) { this.errorMessage = 'Silakan pilih warna'; return false; }
                        if (!this.selectedSize) { this.errorMessage = 'Silakan pilih ukuran'; return false; }
                        if (!this.selectedVariation) { this.errorMessage = 'Kombinasi tidak tersedia'; return false; }
                    }
                    if (this.currentStock <= 0) { this.errorMessage = 'Stok habis'; return false; }
                    if (this.quantity < 1 || this.quantity > this.currentStock) { this.errorMessage = 'Jumlah tidak valid'; return false; }
                    return true;
                },
                init() { if (this.selectedColor || this.selectedSize) { this.updateVariation(); } }
            }" 
            x-init="init()"
            class="space-y-6">
                
                <div>
                    <span class="inline-flex items-center px-3 py-1 rounded-md text-xs font-bold bg-[#1A1A1D] text-white tracking-wide uppercase">
                        {{ $product->category->name }}
                    </span>
                </div>

                <h1 class="text-3xl lg:text-5xl font-black text-[#1A1A1D] leading-tight tracking-tight">{{ $product->name }}</h1>

                <div class="flex items-baseline gap-3">
                    @if($product->point_price > 0)
                        <span class="text-3xl lg:text-4xl font-black text-[#1A1A1D] flex items-center gap-2">
                             <i class="fas fa-coins text-yellow-500"></i> {{ number_format($product->point_price) }} Point
                        </span>
                    @else
                        <span class="text-3xl lg:text-4xl font-black text-[#1A1A1D]">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </span>
                    @endif
                </div>

                <div class="flex items-center gap-2 text-sm font-medium">
                    <template x-if="currentStock > 0">
                        <div class="flex items-center gap-2 text-green-600">
                            <div class="w-2 h-2 bg-green-600 rounded-full animate-pulse"></div>
                            Ready Stock <span class="text-gray-400" x-text="'(' + currentStock + ' items)'"></span>
                        </div>
                    </template>
                    <template x-if="currentStock <= 0">
                        <div class="flex items-center gap-2 text-red-600">
                            <div class="w-2 h-2 bg-red-600 rounded-full"></div>
                            Out of Stock
                        </div>
                    </template>
                </div>

                <div class="p-5 border-l-4 border-[#1A1A1D] bg-gray-50">
                    <p class="text-gray-600 leading-relaxed text-sm">
                        {{ $product->description ?? 'No description available for this product.' }}
                    </p>
                </div>

                <div x-show="errorMessage" x-cloak
                     class="p-3 bg-red-50 border border-red-200 text-red-600 rounded-lg text-sm transition-all">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-circle mt-0.5 mr-2"></i>
                        <span x-text="errorMessage"></span>
                    </div>
                </div>

                <form action="{{ route('cart.store') }}" method="POST" id="addToCartForm" @submit.prevent="if(validateForm()) $el.submit()">
                    @csrf
                    <input type="hidden" name="variation_id" id="variation_id" value="">
                    
                    @if($product->variations->count() > 0)
                    <div class="space-y-6">
                        @if($colors->count() > 0)
                        <div>
                            <label class="block text-xs font-bold text-gray-400 mb-2 uppercase tracking-wide">Select Color</label>
                            <div class="flex flex-wrap gap-2">
                                @foreach($colors as $color)
                                <button type="button"
                                        @click.prevent="selectColor('{{ $color }}')"
                                        :class="selectedColor === '{{ $color }}' ? 'selected' : ''"
                                        class="variation-btn px-6 py-3 rounded-xl text-sm font-bold capitalize">
                                    {{ $color }}
                                </button>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        @if($sizes->count() > 0)
                        <div>
                            <label class="block text-xs font-bold text-gray-400 mb-2 uppercase tracking-wide">Select Size</label>
                            <div class="flex flex-wrap gap-2">
                                @foreach($sizes as $size)
                                <button type="button"
                                        @click.prevent="selectSize('{{ $size }}')"
                                        :class="selectedSize === '{{ $size }}' ? 'selected' : ''"
                                        class="variation-btn px-6 py-3 rounded-xl text-sm font-bold uppercase min-w-[60px]">
                                    {{ $size }}
                                </button>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif

                    <div class="space-y-5 pt-6 mt-6">
                        <div class="flex flex-col sm:flex-row gap-4">
                            <div class="flex items-center">
                                <button type="button" @click.prevent="decrementQuantity()" :disabled="quantity <= 1"
                                        class="qty-btn w-12 h-14 rounded-l-xl flex items-center justify-center">
                                    <i class="fas fa-minus text-xs"></i>
                                </button>
                                <input type="number" name="quantity" id="quantity" x-model="quantity" :max="currentStock" min="1"
                                       class="qty-input w-16 h-14 text-center text-base font-bold" readonly>
                                <button type="button" @click.prevent="incrementQuantity()" :disabled="quantity >= currentStock || currentStock <= 0"
                                        class="qty-btn w-12 h-14 rounded-r-xl flex items-center justify-center">
                                    <i class="fas fa-plus text-xs"></i>
                                </button>
                            </div>

                            <button type="submit" id="addToCartBtn"
                                    :disabled="currentStock <= 0 || ({{ $product->variations->count() }} > 0 && (!selectedColor || !selectedSize))"
                                    :class="currentStock <= 0 || ({{ $product->variations->count() }} > 0 && (!selectedColor || !selectedSize)) ? 'opacity-50 cursor-not-allowed bg-gray-200 text-gray-400' : 'bg-[#1A1A1D] text-white hover:bg-gray-800 hover:scale-[1.02] shadow-lg'"
                                    class="flex-1 px-6 py-4 rounded-xl font-bold text-base transition-all duration-300 transform flex items-center justify-center gap-3">
                                <i class="fas fa-shopping-cart"></i>
                                <span x-text="currentStock > 0 ? 'ADD TO CART' : 'OUT OF STOCK'"></span>
                            </button>
                        </div>
                    </div>
                </form>

                <div class="grid grid-cols-3 gap-3 pt-5">
                    <div class="feature-card rounded-xl p-4 text-center">
                        <i class="fas fa-truck text-[#1A1A1D] text-xl mb-2"></i>
                        <p class="text-xs font-bold text-gray-600">Fast Delivery</p>
                    </div>
                    <div class="feature-card rounded-xl p-4 text-center">
                        <i class="fas fa-shield-alt text-[#1A1A1D] text-xl mb-2"></i>
                        <p class="text-xs font-bold text-gray-600">Original</p>
                    </div>
                    <div class="feature-card rounded-xl p-4 text-center">
                        <i class="fas fa-headset text-[#1A1A1D] text-xl mb-2"></i>
                        <p class="text-xs font-bold text-gray-600">24/7 Support</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="spec-card p-6 lg:p-8 mb-16 shadow-sm border border-gray-100">
            <h2 class="text-xl font-bold text-[#1A1A1D] mb-6 flex items-center gap-3">
                <i class="fas fa-list-alt text-gray-400"></i> Specifications
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-100">
                    <span class="text-gray-500 text-sm">Category</span>
                    <span class="text-[#1A1A1D] font-bold">{{ $product->category->name }}</span>
                </div>
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-100">
                    <span class="text-gray-500 text-sm">Weight</span>
                    <span class="text-[#1A1A1D] font-bold">{{ $product->weight }}g</span>
                </div>
                @if($product->variations->count() > 0)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-100">
                    <span class="text-gray-500 text-sm">Variations</span>
                    <span class="text-[#1A1A1D] font-bold">{{ $product->variations->count() }} options</span>
                </div>
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-100">
                    <span class="text-gray-500 text-sm">Total Stock</span>
                    <span class="text-[#1A1A1D] font-bold">{{ $totalStock }} items</span>
                </div>
                @endif
            </div>
        </div>

        @if($relatedProducts->count() > 0)
        <div class="mb-8">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-2xl font-bold text-[#1A1A1D] flex items-center gap-3">
                    Related Products
                </h2>
                <a href="{{ route('products') }}" class="text-gray-500 hover:text-black text-sm font-semibold flex items-center gap-2 transition-colors">
                    View All <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                @foreach($relatedProducts as $relatedProduct)
                <a href="{{ route('product.detail', $relatedProduct->slug) }}" class="product-card overflow-hidden group block border-gray-200 shadow-sm">
                    <div class="relative bg-[#F3F5F9] overflow-hidden aspect-[4/3] p-0">
                        @if($relatedProduct->images->count() > 0)
                            <img src="{{ asset('storage/products/' . $relatedProduct->images->first()->image) }}"
                                 alt="{{ $relatedProduct->name }}"
                                 class="w-full h-full object-cover p-0 mix-blend-multiply group-hover:scale-110 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <i class="fas fa-image text-4xl text-gray-300"></i>
                            </div>
                        @endif
                    </div>
                    <div class="p-4">
                        <h3 class="font-bold text-[#1A1A1D] text-sm mb-1 line-clamp-2 group-hover:text-gray-600 transition-colors">
                            {{ $relatedProduct->name }}
                        </h3>
                        <div class="text-lg font-black text-[#1A1A1D]">
                            <i class="fas fa-coins mr-1 text-yellow-500 text-xs"></i>
                            {{ number_format($relatedProduct->point_price, 0, ',', '.') }}
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

@if($product->images->count() > 0)
<div id="lightbox" class="lightbox" onclick="closeLightbox(event)">
    <div class="lightbox-content" onclick="event.stopPropagation()">
        <button class="lightbox-close" onclick="event.stopPropagation(); closeLightbox(event)">
            <i class="fas fa-times text-lg"></i>
        </button>
        @if($product->images->count() > 1)
        <button class="lightbox-nav lightbox-prev" onclick="navigateLightbox(-1)">
            <i class="fas fa-chevron-left text-lg"></i>
        </button>
        <button class="lightbox-nav lightbox-next" onclick="navigateLightbox(1)">
            <i class="fas fa-chevron-right text-lg"></i>
        </button>
        @endif
        <img id="lightboxImage" class="lightbox-image" src="" alt="{{ $product->name }}">
        @if($product->images->count() > 1)
        <div class="lightbox-counter">
            <span id="lightboxCounter">1</span> / {{ $product->images->count() }}
        </div>
        <div class="lightbox-thumbnails">
            @foreach($product->images as $index => $image)
            <div class="lightbox-thumb {{ $index == 0 ? 'active' : '' }}" onclick="goToImage({{ $index }})">
                <img src="{{ asset('storage/products/' . $image->image) }}" alt="Thumbnail">
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endif
@push('scripts')
<script>
    let currentImageIndex = 0;
    const images = @json($product->images->pluck('image')->toArray());

    function changeMainImage(url, element) {
        document.getElementById('mainImage').src = url;
        document.querySelectorAll('.thumbnail-image').forEach(thumb => thumb.classList.remove('active'));
        element.classList.add('active');
        currentImageIndex = Array.from(element.parentNode.children).indexOf(element);
    }

    function openLightbox(index) {
        currentImageIndex = index;
        const lightbox = document.getElementById('lightbox');
        const lightboxImage = document.getElementById('lightboxImage');
        const imageSrc = '{{ asset('storage/products') }}/' + images[currentImageIndex];
        lightboxImage.src = imageSrc;
        updateLightboxCounter();
        updateLightboxThumbnails();
        lightbox.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox(event) {
        if (event.target.id === 'lightbox' || event.target.classList.contains('lightbox-close')) {
            document.getElementById('lightbox').classList.remove('active');
            document.body.style.overflow = 'auto';
        }
    }

    function navigateLightbox(direction) {
        currentImageIndex = (currentImageIndex + direction + images.length) % images.length;
        document.getElementById('lightboxImage').src = '{{ asset('storage/products') }}/' + images[currentImageIndex];
        updateLightboxCounter();
        updateLightboxThumbnails();
    }

    function goToImage(index) {
        currentImageIndex = index;
        document.getElementById('lightboxImage').src = '{{ asset('storage/products') }}/' + images[currentImageIndex];
        updateLightboxCounter();
        updateLightboxThumbnails();
    }

    function updateLightboxCounter() {
        const counter = document.getElementById('lightboxCounter');
        if (counter) {
            counter.textContent = currentImageIndex + 1;
        }
    }

    function updateLightboxThumbnails() {
        document.querySelectorAll('.lightbox-thumb').forEach((thumb, index) => {
            thumb.classList.toggle('active', index === currentImageIndex);
        });
    }

    document.addEventListener('keydown', function(e) {
        const lightbox = document.getElementById('lightbox');
        if (lightbox.classList.contains('active')) {
            if (e.key === 'Escape') {
                closeLightbox({ target: { id: 'lightbox' } });
            } else if (e.key === 'ArrowLeft') {
                navigateLightbox(-1);
            } else if (e.key === 'ArrowRight') {
                navigateLightbox(1);
            }
        }
    });
</script>
@endpush
@endsection