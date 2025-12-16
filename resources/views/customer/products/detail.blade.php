@extends('customer.layouts.app')

@section('title', $product->name)

@push('styles')
<style>
    [x-cloak] {
        display: none !important;
    }

    /* Image Gallery Styles */
    .thumbnail-image {
        transition: all 0.3s ease;
        cursor: pointer;
        border: 2px solid #e5e7eb;
    }

    .thumbnail-image:hover,
    .thumbnail-image.active {
        border-color: #FAD470;
        opacity: 1;
    }

    .thumbnail-image:not(.active) {
        opacity: 0.7;
    }

    /* Product Card Styles */
    .product-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        transition: all 0.3s ease;
    }

    .product-card:hover {
        border-color: #FAD470;
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.1);
    }

    /* Variation Button Styles */
    .variation-btn {
        background: #f9fafb;
        border: 2px solid #e5e7eb;
        color: #374151;
        transition: all 0.2s ease;
    }

    .variation-btn:hover:not(.disabled) {
        border-color: #FAD470;
        background: #fffbeb;
    }

    .variation-btn.selected {
        background: #fffbeb;
        border-color: #FAD470;
        color: #92400e;
        font-weight: 600;
    }

    .variation-btn.disabled {
        opacity: 0.4;
        cursor: not-allowed;
    }

    /* Quantity Controls */
    .qty-btn {
        background: #f9fafb;
        border: 2px solid #e5e7eb;
        color: #374151;
        transition: all 0.2s ease;
    }

    .qty-btn:hover {
        border-color: #FAD470;
        background: #fffbeb;
    }

    .qty-input {
        background: #f9fafb;
        border-top: 2px solid #e5e7eb;
        border-bottom: 2px solid #e5e7eb;
        border-left: none;
        border-right: none;
        color: #111827;
    }

    .qty-input:focus {
        outline: none;
    }

    /* Info Card */
    .info-card {
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
    }

    /* Spec Card */
    .spec-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
    }

    /* Feature Card */
    .feature-card {
        background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
        border: 1px solid #fcd34d;
    }

    /* Main Image Placeholder */
    .image-placeholder {
        background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
    }

    /* Main Image Clickable */
    .main-image-container {
        cursor: zoom-in;
        position: relative;
    }

    .main-image-container:hover .zoom-hint {
        opacity: 1;
    }

    .zoom-hint {
        position: absolute;
        bottom: 16px;
        right: 16px;
        background: rgba(0, 0, 0, 0.6);
        color: white;
        padding: 8px 12px;
        border-radius: 8px;
        font-size: 12px;
        opacity: 0;
        transition: opacity 0.3s ease;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* Lightbox Styles */
    .lightbox {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.95);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }

    .lightbox.active {
        opacity: 1;
        visibility: visible;
    }

    .lightbox-content {
        position: relative;
        max-width: 90vw;
        max-height: 90vh;
        transform: scale(0.9);
        transition: transform 0.3s ease;
    }

    .lightbox.active .lightbox-content {
        transform: scale(1);
    }

    .lightbox-image {
        max-width: 90vw;
        max-height: 85vh;
        object-fit: contain;
        border-radius: 8px;
    }

    .lightbox-close {
        position: absolute;
        top: -40px;
        right: 0;
        background: rgba(255, 255, 255, 0.1);
        border: none;
        color: white;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }

    .lightbox-close:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: rotate(90deg);
    }

    .lightbox-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(255, 255, 255, 0.1);
        border: none;
        color: white;
        width: 48px;
        height: 48px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }

    .lightbox-nav:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    .lightbox-prev {
        left: -70px;
    }

    .lightbox-next {
        right: -70px;
    }

    .lightbox-counter {
        position: absolute;
        bottom: -40px;
        left: 50%;
        transform: translateX(-50%);
        color: rgba(255, 255, 255, 0.7);
        font-size: 14px;
    }

    .lightbox-thumbnails {
        position: absolute;
        bottom: -80px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 8px;
    }

    .lightbox-thumb {
        width: 50px;
        height: 50px;
        border-radius: 6px;
        overflow: hidden;
        cursor: pointer;
        opacity: 0.5;
        transition: all 0.2s ease;
        border: 2px solid transparent;
    }

    .lightbox-thumb:hover,
    .lightbox-thumb.active {
        opacity: 1;
        border-color: #FAD470;
    }

    .lightbox-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    @media (max-width: 768px) {
        .lightbox-nav {
            width: 40px;
            height: 40px;
        }
        .lightbox-prev {
            left: 10px;
        }
        .lightbox-next {
            right: 10px;
        }
        .lightbox-thumbnails {
            display: none;
        }
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Breadcrumb -->
        <nav class="flex items-center mb-8 text-sm">
            <a href="{{ route('home') }}" class="text-gray-500 hover:text-amber-600 transition-colors">
                <i class="fas fa-home"></i>
            </a>
            <i class="fas fa-chevron-right text-gray-300 mx-3 text-xs"></i>
            <a href="{{ route('home') }}#products" class="text-gray-500 hover:text-amber-600 transition-colors">Products</a>
            <i class="fas fa-chevron-right text-gray-300 mx-3 text-xs"></i>
            <span class="text-gray-900 font-medium">{{ Str::limit($product->name, 30) }}</span>
        </nav>

        <!-- Success/Error Messages -->
        @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
        @endif

        <!-- Product Detail Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 mb-16">
            <!-- Product Images -->
            <div class="space-y-4">
                <!-- Main Image -->
                <div class="bg-white rounded-2xl overflow-hidden aspect-square shadow-sm border border-gray-200 main-image-container" onclick="openLightbox(0)">
                    @if($product->images->count() > 0)
                        <img id="mainImage"
                             src="{{ asset('storage/products/' . $product->images->first()->image) }}"
                             alt="{{ $product->name }}"
                             class="w-full h-full object-cover">
                        <div class="zoom-hint">
                            <i class="fas fa-search-plus"></i>
                            <span>Klik untuk perbesar</span>
                        </div>
                    @else
                        <div class="w-full h-full image-placeholder flex items-center justify-center" onclick="event.stopPropagation()">
                            <div class="text-center">
                                <i class="fas fa-image text-6xl text-gray-300 mb-4"></i>
                                <p class="text-gray-400 text-sm">Tidak ada gambar</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Thumbnail Gallery -->
                @if($product->images->count() > 1)
                <div class="grid grid-cols-4 sm:grid-cols-5 gap-3">
                    @foreach($product->images as $index => $image)
                    <div class="aspect-square rounded-xl overflow-hidden {{ $index == 0 ? 'active' : '' }} thumbnail-image cursor-pointer shadow-sm"
                         onclick="changeMainImage('{{ asset('storage/products/' . $image->image) }}', this)">
                        <img src="{{ asset('storage/products/' . $image->image) }}"
                             alt="{{ $product->name }} - {{ $index + 1 }}"
                             class="w-full h-full object-cover">
                    </div>
                    @endforeach
                </div>
                @elseif($product->images->count() == 0)
                <div class="grid grid-cols-4 gap-3">
                    @for($i = 0; $i < 4; $i++)
                    <div class="aspect-square rounded-xl overflow-hidden bg-gray-100 border border-gray-200 flex items-center justify-center">
                        <i class="fas fa-image text-gray-300 text-xl"></i>
                    </div>
                    @endfor
                </div>
                @endif
            </div>

            <!-- Product Information -->
            <div class="space-y-6">
                <!-- Category Badge -->
                <div>
                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                        <i class="fas fa-tag mr-2"></i>
                        {{ $product->category->name }}
                    </span>
                </div>

                <!-- Product Title -->
                <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 leading-tight">{{ $product->name }}</h1>

                <!-- Price -->
                <div class="flex items-baseline gap-3">
                    <span class="text-3xl lg:text-4xl font-bold text-amber-600">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </span>
                </div>

                <!-- Stock Status -->
                <div class="flex items-center gap-3">
                    @if($totalStock > 0)
                        <div class="flex items-center gap-2 px-3 py-1.5 bg-green-100 rounded-full">
                            <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                            <span class="font-semibold text-green-700 text-sm">Stok Tersedia</span>
                        </div>
                        <span class="text-gray-500 text-sm">({{ $totalStock }} tersedia)</span>
                    @else
                        <div class="flex items-center gap-2 px-3 py-1.5 bg-red-100 rounded-full">
                            <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                            <span class="font-semibold text-red-700 text-sm">Stok Habis</span>
                        </div>
                    @endif
                </div>

                <!-- Product Description -->
                <div class="info-card p-5">
                    <h3 class="text-sm font-bold text-gray-900 mb-3 flex items-center gap-2">
                        <i class="fas fa-info-circle text-amber-500"></i>
                        Deskripsi Produk
                    </h3>
                    <p class="text-gray-600 leading-relaxed text-sm">
                        {{ $product->description ?? 'Tidak ada deskripsi untuk produk ini.' }}
                    </p>
                </div>

                <!-- Add to Cart Form -->
                <form action="{{ route('cart.store') }}" method="POST" id="addToCartForm">
                    @csrf
                    
                    <!-- Hidden input for variation_id -->
                    <input type="hidden" name="variation_id" id="variation_id" value="">
                    
                    <!-- Variations Selection -->
                    @if($product->variations->count() > 0)
                    <div class="space-y-5">
                        <!-- Color Selection -->
                        @if($colors->count() > 0)
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-3">
                                <i class="fas fa-palette mr-2 text-amber-500"></i>
                                Pilih Warna
                            </label>
                            <div class="flex flex-wrap gap-2">
                                @foreach($colors as $color)
                                <button type="button"
                                        class="variation-btn px-5 py-2.5 rounded-xl text-sm font-medium {{ request('color') == $color ? 'selected' : '' }}"
                                        onclick="selectVariation('color', '{{ $color }}', this)">
                                    {{ $color }}
                                </button>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Size Selection -->
                        @if($sizes->count() > 0)
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-3">
                                <i class="fas fa-ruler mr-2 text-amber-500"></i>
                                Pilih Ukuran
                            </label>
                            <div class="flex flex-wrap gap-2 mb-3">
                                @foreach($sizes as $size)
                                <button type="button"
                                        class="variation-btn px-5 py-2.5 rounded-xl text-sm font-medium {{ request('size') == $size ? 'selected' : '' }}"
                                        onclick="selectVariation('size', '{{ $size }}', this)">
                                    {{ $size }}
                                </button>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- Quantity and Add to Cart -->
                    <div class="space-y-5 pt-5 border-t border-gray-200">
                        <!-- Quantity -->
                        <div class="flex items-center gap-4">
                            <label class="text-sm font-bold text-gray-900">
                                <i class="fas fa-boxes mr-2 text-amber-500"></i>
                                Jumlah
                            </label>
                            <div class="flex items-center">
                                <button type="button" class="qty-btn w-11 h-11 rounded-l-xl flex items-center justify-center" onclick="updateQuantity(-1)">
                                    <i class="fas fa-minus text-sm"></i>
                                </button>
                                <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $totalStock }}"
                                       class="qty-input w-16 h-11 text-center text-sm font-bold"
                                       onchange="validateQuantity(this)">
                                <button type="button" class="qty-btn w-11 h-11 rounded-r-xl flex items-center justify-center" onclick="updateQuantity(1)">
                                    <i class="fas fa-plus text-sm"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row gap-3">
                            <button type="submit"
                                    id="addToCartBtn"
                                    class="flex-1 bg-gradient-to-r from-amber-500 to-yellow-500 hover:from-amber-600 hover:to-yellow-600 text-white px-6 py-4 rounded-xl font-bold text-sm transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed shadow-lg hover:shadow-xl transform hover:scale-[1.02] flex items-center justify-center gap-2"
                                    {{ $totalStock == 0 ? 'disabled' : '' }}>
                                <i class="fas fa-shopping-cart"></i>
                                {{ $totalStock > 0 ? 'Tambah ke Keranjang' : 'Stok Habis' }}
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Product Features -->
                <div class="grid grid-cols-3 gap-3 pt-5">
                    <div class="feature-card rounded-xl p-4 text-center">
                        <i class="fas fa-truck text-amber-600 text-xl mb-2"></i>
                        <p class="text-xs font-medium text-amber-800">Pengiriman Cepat</p>
                    </div>
                    <div class="feature-card rounded-xl p-4 text-center">
                        <i class="fas fa-shield-alt text-amber-600 text-xl mb-2"></i>
                        <p class="text-xs font-medium text-amber-800">Produk Original</p>
                    </div>
                    <div class="feature-card rounded-xl p-4 text-center">
                        <i class="fas fa-headset text-amber-600 text-xl mb-2"></i>
                        <p class="text-xs font-medium text-amber-800">Support 24/7</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Specifications -->
        <div class="spec-card p-6 lg:p-8 mb-16 shadow-sm">
            <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-list-alt text-amber-600"></i>
                </div>
                Spesifikasi Produk
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                    <span class="text-gray-500 text-sm">Kategori</span>
                    <span class="text-gray-900 font-semibold">{{ $product->category->name }}</span>
                </div>
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                    <span class="text-gray-500 text-sm">Berat</span>
                    <span class="text-gray-900 font-semibold">{{ $product->weight }}g</span>
                </div>
                @if($product->variations->count() > 0)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                    <span class="text-gray-500 text-sm">Variasi Tersedia</span>
                    <span class="text-gray-900 font-semibold">{{ $product->variations->count() }} pilihan</span>
                </div>
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                    <span class="text-gray-500 text-sm">Total Stok</span>
                    <span class="text-gray-900 font-semibold">{{ $totalStock }} item</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Related Products -->
        @if($relatedProducts->count() > 0)
        <div class="mb-8">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                    <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-th-large text-amber-600"></i>
                    </div>
                    Produk Terkait
                </h2>
                <a href="{{ route('home') }}#products" class="text-amber-600 hover:text-amber-700 text-sm font-semibold flex items-center gap-2 transition-colors">
                    Lihat Semua
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                @foreach($relatedProducts as $relatedProduct)
                <a href="{{ route('product.detail', $relatedProduct->slug) }}"
                   class="product-card overflow-hidden group">
                    <div class="aspect-square bg-gray-100 overflow-hidden">
                        @if($relatedProduct->images->count() > 0)
                            <img src="{{ asset('storage/products/' . $relatedProduct->images->first()->image) }}"
                                 alt="{{ $relatedProduct->name }}"
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gray-100">
                                <i class="fas fa-image text-4xl text-gray-300"></i>
                            </div>
                        @endif
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 text-sm mb-2 line-clamp-2 group-hover:text-amber-600 transition-colors">
                            {{ $relatedProduct->name }}
                        </h3>
                        <div class="text-lg font-bold text-amber-600">
                            Rp {{ number_format($relatedProduct->price, 0, ',', '.') }}
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Image Lightbox Modal -->
@if($product->images->count() > 0)
<div id="lightbox" class="lightbox" onclick="closeLightbox(event)">
    <div class="lightbox-content" onclick="event.stopPropagation()">
        <button class="lightbox-close" onclick="closeLightbox(event)">
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
                <img src="{{ asset('storage/products/' . $image->image) }}" alt="Thumbnail {{ $index + 1 }}">
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
let selectedColor = '{{ request('color') ?? '' }}';
let selectedSize = '{{ request('size') ?? '' }}';
let maxQuantity = {{ $totalStock }};

// Image gallery data
const productImages = @json($product->images->pluck('image')->map(fn($img) => asset('storage/products/' . $img)));
let currentImageIndex = 0;

// Change main image in gallery
function changeMainImage(imageSrc, thumbnailElement) {
    document.getElementById('mainImage').src = imageSrc;
    
    // Find the index of clicked image
    const index = productImages.indexOf(imageSrc);
    if (index !== -1) {
        currentImageIndex = index;
    }

    // Update active state of thumbnails
    document.querySelectorAll('.thumbnail-image').forEach(thumb => {
        thumb.classList.remove('active');
        thumb.style.borderColor = '#e5e7eb';
    });

    thumbnailElement.classList.add('active');
    thumbnailElement.style.borderColor = '#FAD470';
}

// Lightbox functions
function openLightbox(index) {
    if (productImages.length === 0) return;
    
    currentImageIndex = index;
    updateLightboxImage();
    document.getElementById('lightbox').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeLightbox(event) {
    if (event) event.stopPropagation();
    document.getElementById('lightbox').classList.remove('active');
    document.body.style.overflow = '';
}

function navigateLightbox(direction) {
    currentImageIndex += direction;
    
    // Loop around
    if (currentImageIndex >= productImages.length) {
        currentImageIndex = 0;
    } else if (currentImageIndex < 0) {
        currentImageIndex = productImages.length - 1;
    }
    
    updateLightboxImage();
}

function goToImage(index) {
    currentImageIndex = index;
    updateLightboxImage();
}

function updateLightboxImage() {
    const lightboxImage = document.getElementById('lightboxImage');
    const lightboxCounter = document.getElementById('lightboxCounter');
    
    if (lightboxImage && productImages[currentImageIndex]) {
        lightboxImage.src = productImages[currentImageIndex];
    }
    
    if (lightboxCounter) {
        lightboxCounter.textContent = currentImageIndex + 1;
    }
    
    // Update lightbox thumbnails active state
    document.querySelectorAll('.lightbox-thumb').forEach((thumb, index) => {
        if (index === currentImageIndex) {
            thumb.classList.add('active');
        } else {
            thumb.classList.remove('active');
        }
    });
}

// Keyboard navigation for lightbox
document.addEventListener('keydown', function(e) {
    const lightbox = document.getElementById('lightbox');
    if (!lightbox || !lightbox.classList.contains('active')) return;
    
    if (e.key === 'Escape') {
        closeLightbox();
    } else if (e.key === 'ArrowLeft') {
        navigateLightbox(-1);
    } else if (e.key === 'ArrowRight') {
        navigateLightbox(1);
    }
});

// Update quantity
function updateQuantity(change) {
    const quantityInput = document.getElementById('quantity');
    let newValue = parseInt(quantityInput.value) + change;

    if (newValue >= 1 && newValue <= maxQuantity) {
        quantityInput.value = newValue;
    }
}

// Validate quantity input
function validateQuantity(input) {
    let value = parseInt(input.value);

    if (isNaN(value) || value < 1) {
        input.value = 1;
    } else if (value > maxQuantity) {
        input.value = maxQuantity;
    }
}

// Select variation
function selectVariation(type, value, element) {
    if (type === 'color') {
        selectedColor = value;
    } else if (type === 'size') {
        selectedSize = value;
    }

    // Update UI - remove selected from siblings only
    const siblings = element.parentElement.querySelectorAll('.variation-btn');
    siblings.forEach(btn => btn.classList.remove('selected'));
    element.classList.add('selected');

    // Check if selected combination is available
    checkVariationAvailability();
}

// Check if selected variation is available
function checkVariationAvailability() {
    const variations = @json($availableVariations);
    const selectedVariation = variations.find(v =>
        v.color === selectedColor && v.size === selectedSize
    );

    const addToCartBtn = document.getElementById('addToCartBtn');
    const variationIdInput = document.getElementById('variation_id');
    
    if (selectedVariation && selectedVariation.stock > 0) {
        addToCartBtn.disabled = false;
        addToCartBtn.innerHTML = '<i class="fas fa-shopping-cart"></i> Tambah ke Keranjang';
        maxQuantity = selectedVariation.stock;
        document.getElementById('quantity').max = maxQuantity;
        
        // Set variation_id to hidden input
        variationIdInput.value = selectedVariation.id;
    } else if (selectedColor || selectedSize) {
        addToCartBtn.disabled = true;
        addToCartBtn.innerHTML = '<i class="fas fa-times"></i> Tidak Tersedia';
        variationIdInput.value = '';
    }
}

// Add to wishlist function
function addToWishlist() {
    alert('Fitur wishlist akan segera hadir!');
}

// Form validation before submit
document.getElementById('addToCartForm').addEventListener('submit', function(e) {
    const variationId = document.getElementById('variation_id').value;
    const hasVariations = {{ $product->variations->count() }} > 0;
    
    if (hasVariations && !variationId) {
        e.preventDefault();
        alert('Silakan pilih variasi produk (warna dan ukuran)');
        return false;
    }
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    checkVariationAvailability();
    
    // Auto-hide success/error messages after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.bg-green-100, .bg-red-100');
        alerts.forEach(alert => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000);
});
</script>
@endpush
