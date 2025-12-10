@extends('customer.layouts.app')

@section('title', $product->name)

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

    /* Image Gallery Styles */
    .thumbnail-image {
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .thumbnail-image:hover,
    .thumbnail-image.active {
        border-color: #FAD470;
        opacity: 1;
    }

    .thumbnail-image:not(.active) {
        opacity: 0.6;
    }

    /* Quantity Selector Styles */
    .quantity-btn {
        @apply w-10 h-10 rounded-lg border border-gray-300 flex items-center justify-center text-gray-600 hover:bg-gray-100 transition-colors;
    }

    /* Variation Selector Styles */
    .variation-option {
        @apply px-4 py-2 border rounded-lg cursor-pointer transition-all duration-200;
    }

    .variation-option:not(.selected):not(.disabled) {
        @apply border-gray-300 hover:border-yellow-400;
    }

    .variation-option.selected {
        @apply border-yellow-400 bg-yellow-50;
    }

    .variation-option.disabled {
        @apply border-gray-200 bg-gray-100 text-gray-400 cursor-not-allowed;
    }

    /* Product Card Styles */
    .product-card {
        @apply bg-white rounded-lg shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden;
    }

    /* Breadcrumb Styles */
    .breadcrumb-link {
        @apply text-gray-500 hover:text-gray-900 transition-colors;
    }

    /* Price Display */
    .price-display {
        @apply text-2xl font-bold text-gray-900;
    }

    /* Stock Status */
    .in-stock {
        @apply text-green-600 font-medium;
    }

    .out-of-stock {
        @apply text-red-600 font-medium;
    }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-8 text-sm">
        <a href="{{ route('home') }}" class="breadcrumb-link">Home</a>
        <span class="mx-2 text-gray-400">/</span>
        <a href="#products" class="breadcrumb-link">Products</a>
        <span class="mx-2 text-gray-400">/</span>
        <span class="text-gray-900 font-medium">{{ $product->name }}</span>
    </nav>

    <!-- Product Detail Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-16">
        <!-- Product Images -->
        <div class="space-y-4">
            <!-- Main Image -->
            <div class="aspect-square rounded-lg overflow-hidden bg-gray-100">
                <img id="mainImage"
                     src="{{ $product->images->first() ? asset('storage/products/' . $product->images->first()->image) : asset('ui/placeholder.jpg') }}"
                     alt="{{ $product->name }}"
                     class="w-full h-full object-cover">
            </div>

            <!-- Thumbnail Gallery -->
            @if($product->images->count() > 1)
            <div class="grid grid-cols-4 gap-2">
                @foreach($product->images as $index => $image)
                <div class="aspect-square rounded-lg overflow-hidden border-2 {{ $index == 0 ? 'border-yellow-400 active' : 'border-gray-200' }} thumbnail-image"
                     onclick="changeMainImage('{{ asset('storage/products/' . $image->image) }}', this)">
                    <img src="{{ asset('storage/products/' . $image->image) }}"
                         alt="{{ $product->name }} - {{ $index + 1 }}"
                         class="w-full h-full object-cover">
                </div>
                @endforeach
            </div>
            @endif
        </div>

        <!-- Product Information -->
        <div class="space-y-6">
            <!-- Product Title and Category -->
            <div>
                <div class="text-sm text-gray-500 mb-2">{{ $product->category->name }}</div>
                <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $product->name }}</h1>
            </div>

            <!-- Price -->
            <div class="price-display">
                ${{ number_format($product->price, 2) }}
            </div>

            <!-- Stock Status -->
            <div class="{{ $totalStock > 0 ? 'in-stock' : 'out-of-stock' }}">
                {{ $totalStock > 0 ? 'In Stock' : 'Out of Stock' }}
                @if($totalStock > 0)
                    <span class="text-sm text-gray-500">({{ $totalStock }} available)</span>
                @endif
            </div>

            <!-- Product Description -->
            <div class="text-gray-600 leading-relaxed">
                <p>{{ $product->description ?? 'No description available for this product.' }}</p>
            </div>

            <!-- Variations Selection -->
            @if($product->variations->count() > 0)
                <!-- Color Selection -->
                @if($colors->count() > 0)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">Color</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach($colors as $color)
                        <button type="button"
                                class="variation-option {{ request('color') == $color ? 'selected' : '' }}"
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
                    <label class="block text-sm font-medium text-gray-700 mb-3">Size</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach($sizes as $size)
                        <button type="button"
                                class="variation-option {{ request('size') == $size ? 'selected' : '' }}"
                                onclick="selectVariation('size', '{{ $size }}', this)">
                            {{ $size }}
                        </button>
                        @endforeach
                    </div>
                </div>
                @endif
            @endif

            <!-- Quantity and Add to Cart -->
            <div class="space-y-4">
                <div class="flex items-center space-x-4">
                    <label class="text-sm font-medium text-gray-700">Quantity</label>
                    <div class="flex items-center">
                        <button type="button" class="quantity-btn" onclick="updateQuantity(-1)">
                            <i class="fas fa-minus"></i>
                        </button>
                        <input type="number" id="quantity" value="1" min="1" max="{{ $totalStock }}"
                               class="w-16 h-10 text-center border border-gray-300 rounded-lg mx-2"
                               onchange="validateQuantity(this)">
                        <button type="button" class="quantity-btn" onclick="updateQuantity(1)">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <button type="button"
                            id="addToCartBtn"
                            class="flex-1 bg-gradient-to-r from-yellow-400 to-yellow-500 text-black px-6 py-3 rounded-lg font-semibold hover:from-yellow-500 hover:to-yellow-600 transition duration-300 disabled:opacity-50 disabled:cursor-not-allowed"
                            onclick="addToCart()"
                            {{ $totalStock == 0 ? 'disabled' : '' }}>
                        <i class="fas fa-shopping-cart mr-2"></i>
                        {{ $totalStock > 0 ? 'Add to Cart' : 'Out of Stock' }}
                    </button>

                    <button type="button"
                            class="px-6 py-3 border-2 border-gray-300 rounded-lg font-semibold hover:border-yellow-400 transition-colors"
                            onclick="addToWishlist()">
                        <i class="far fa-heart mr-2"></i>
                        Wishlist
                    </button>
                </div>
            </div>

            <!-- Product Features -->
            <div class="border-t pt-6">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-truck text-gray-400"></i>
                        <span>Free Shipping</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-undo text-gray-400"></i>
                        <span>Easy Returns</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-shield-alt text-gray-400"></i>
                        <span>Secure Payment</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Details Tabs -->
    <div class="mb-16">
        <div class="border-b border-gray-200">
            <nav class="flex space-x-8">
                <button type="button"
                        class="py-4 px-1 border-b-2 font-medium text-sm border-yellow-400 text-gray-900"
                        onclick="showTab('details')">
                    Product Details
                </button>
                <button type="button"
                        class="py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300"
                        onclick="showTab('shipping')">
                    Shipping & Returns
                </button>
                <button type="button"
                        class="py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300"
                        onclick="showTab('reviews')">
                    Reviews
                </button>
            </nav>
        </div>

        <!-- Tab Content -->
        <div class="mt-8">
            <div id="details-tab" class="tab-content">
                <div class="prose max-w-none">
                    <h3>Description</h3>
                    <p>{{ $product->description ?? 'No detailed description available for this product.' }}</p>

                    <h3>Specifications</h3>
                    <ul>
                        <li><strong>Category:</strong> {{ $product->category->name }}</li>
                        <li><strong>Weight:</strong> {{ $product->weight }}g</li>
                        @if($product->variations->count() > 0)
                        <li><strong>Available Variations:</strong> {{ $product->variations->count() }}</li>
                        <li><strong>Total Stock:</strong> {{ $totalStock }} items</li>
                        @endif
                    </ul>
                </div>
            </div>

            <div id="shipping-tab" class="tab-content hidden">
                <div class="prose max-w-none">
                    <h3>Shipping Information</h3>
                    <ul>
                        <li>Standard shipping: 5-7 business days</li>
                        <li>Express shipping: 2-3 business days</li>
                        <li>Free shipping on orders over $50</li>
                        <li>International shipping available</li>
                    </ul>

                    <h3>Return Policy</h3>
                    <ul>
                        <li>30-day return policy</li>
                        <li>Items must be unused and in original packaging</li>
                        <li>Free return shipping on defective items</li>
                        <li>Refund processed within 5-7 business days</li>
                    </ul>
                </div>
            </div>

            <div id="reviews-tab" class="tab-content hidden">
                <div class="text-center py-12 text-gray-500">
                    <i class="fas fa-star text-4xl mb-4"></i>
                    <p>No reviews yet. Be the first to review this product!</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <div>
        <h2 class="text-2xl font-bold text-gray-900 mb-8">Related Products</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 md:gap-6">
            @foreach($relatedProducts as $relatedProduct)
            <a href="{{ route('product.detail', $relatedProduct->slug) }}"
               class="product-card group">
                <div class="aspect-square bg-gray-100 overflow-hidden">
                    <img src="{{ $relatedProduct->images->first() ? asset('storage/products/' . $relatedProduct->images->first()->image) : asset('ui/placeholder.jpg') }}"
                         alt="{{ $relatedProduct->name }}"
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-gray-900 text-sm mb-2 line-clamp-2">
                        {{ $relatedProduct->name }}
                    </h3>
                    <div class="text-lg font-bold text-gray-900">
                        ${{ number_format($relatedProduct->price, 2) }}
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif
</div>

<!-- Hidden form for CSRF token -->
<form id="csrfForm">
    @csrf
</form>
@endsection

@push('scripts')
<script>
let selectedColor = '{{ request('color') ?? '' }}';
let selectedSize = '{{ request('size') ?? '' }}';
let maxQuantity = {{ $totalStock }};

// Change main image in gallery
function changeMainImage(imageSrc, thumbnailElement) {
    document.getElementById('mainImage').src = imageSrc;

    // Update active state of thumbnails
    document.querySelectorAll('.thumbnail-image').forEach(thumb => {
        thumb.classList.remove('active', 'border-yellow-400');
        thumb.classList.add('border-gray-200');
    });

    thumbnailElement.classList.remove('border-gray-200');
    thumbnailElement.classList.add('active', 'border-yellow-400');
}

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

    // Update UI
    document.querySelectorAll(`.variation-option`).forEach(btn => {
        btn.classList.remove('selected');
    });
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
    if (selectedVariation && selectedVariation.stock > 0) {
        addToCartBtn.disabled = false;
        addToCartBtn.innerHTML = '<i class="fas fa-shopping-cart mr-2"></i>Add to Cart';
        maxQuantity = selectedVariation.stock;
        document.getElementById('quantity').max = maxQuantity;
    } else {
        addToCartBtn.disabled = true;
        addToCartBtn.innerHTML = '<i class="fas fa-times mr-2"></i>Not Available';
    }
}

// Add to cart function
function addToCart() {
    const quantity = parseInt(document.getElementById('quantity').value);
    const productId = {{ $product->id }};

    // Get CSRF token
    const csrfToken = document.querySelector('#csrfForm input[name="_token"]').value;

    fetch('/cart', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: quantity,
            color: selectedColor || null,
            size: selectedSize || null
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Product added to cart successfully!');
            // You can update cart UI here
        } else {
            alert(data.message || 'Failed to add product to cart');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while adding to cart');
    });
}

// Add to wishlist function
function addToWishlist() {
    const productId = {{ $product->id }};
    alert(`Product ${productId} added to wishlist! (Implement actual wishlist functionality)`);
}

// Show tab content
function showTab(tabName) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.add('hidden');
    });

    // Reset all tab buttons
    document.querySelectorAll('nav button').forEach(btn => {
        btn.classList.remove('border-yellow-400', 'text-gray-900');
        btn.classList.add('border-transparent', 'text-gray-500');
    });

    // Show selected tab
    document.getElementById(tabName + '-tab').classList.remove('hidden');

    // Highlight selected tab button
    event.target.classList.remove('border-transparent', 'text-gray-500');
    event.target.classList.add('border-yellow-400', 'text-gray-900');
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    checkVariationAvailability();
});
</script>
@endpush