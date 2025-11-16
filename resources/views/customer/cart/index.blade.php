@extends('customer.layouts.app')

@section('title', 'Shopping Cart')

@push('styles')
<style>
    [x-cloak] {
        display: none !important;
    }
</style>
@endpush

@section('content')
{{-- Header Section --}}
<section class="bg-gradient-to-r from-purple-600 to-pink-600 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">Shopping Cart</h1>
        <p class="text-white/90">Review your items and proceed to checkout</p>
    </div>
</section>

{{-- Flash Messages --}}
@if(session('success'))
<div x-data="{ show: true }" 
     x-show="show" 
     x-init="setTimeout(() => show = false, 5000)"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 transform translate-y-2"
     x-transition:enter-end="opacity-100 transform translate-y-0"
     x-transition:leave="transition ease-in duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
    <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-md flex items-center justify-between">
        <div class="flex items-center">
            <i class="fas fa-check-circle text-green-500 text-xl mr-3"></i>
            <p class="text-green-800 font-medium">{{ session('success') }}</p>
        </div>
        <button @click="show = false" class="text-green-500 hover:text-green-700">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>
@endif

@if(session('error'))
<div x-data="{ show: true }" 
     x-show="show" 
     x-init="setTimeout(() => show = false, 5000)"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 transform translate-y-2"
     x-transition:enter-end="opacity-100 transform translate-y-0"
     x-transition:leave="transition ease-in duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-md flex items-center justify-between">
        <div class="flex items-center">
            <i class="fas fa-exclamation-circle text-red-500 text-xl mr-3"></i>
            <p class="text-red-800 font-medium">{{ session('error') }}</p>
        </div>
        <button @click="show = false" class="text-red-500 hover:text-red-700">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>
@endif

{{-- Main Cart Content --}}
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($cartItems->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Cart Items Section --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-bold text-gray-900">
                            Cart Items ({{ $cartItems->count() }})
                        </h2>
                        <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Are you sure you want to clear all items from cart?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-700 font-medium flex items-center">
                                <i class="fas fa-trash-alt mr-2"></i>
                                Clear All
                            </button>
                        </form>
                    </div>

                    <div class="space-y-4">
                        @foreach($cartItems as $item)
                        @if($item->variation && $item->variation->product)
                        <div x-data="{ quantity: {{ $item->quantity }} }" class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition duration-300">
                            <div class="flex flex-col sm:flex-row gap-4">
                                {{-- Product Image --}}
                                <div class="w-full sm:w-24 h-24 flex-shrink-0">
                                    @if($item->variation->product->images && $item->variation->product->images->count() > 0)
                                        <img src="{{ asset('storage/products/' . $item->variation->product->images->first()->image) }}" 
                                             alt="{{ $item->variation->product->name }}"
                                             class="w-full h-full object-cover rounded-lg">
                                    @else
                                        <div class="w-full h-full bg-gray-200 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-image text-gray-400 text-2xl"></i>
                                        </div>
                                    @endif
                                </div>

                                {{-- Product Details --}}
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900 mb-1">{{ $item->variation->product->name }}</h3>
                                    <p class="text-sm text-gray-600 mb-2">
                                        <i class="fas fa-tag mr-1"></i>
                                        {{ $item->variation->product->category->name ?? 'Uncategorized' }}
                                    </p>
                                    
                                    {{-- Variations --}}
                                    <div class="flex flex-wrap gap-2 mb-3">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            <i class="fas fa-palette mr-1"></i>
                                            {{ $item->variation->color }}
                                        </span>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-pink-100 text-pink-800">
                                            <i class="fas fa-ruler mr-1"></i>
                                            {{ $item->variation->size }}
                                        </span>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $item->variation->stock > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            <i class="fas fa-box mr-1"></i>
                                            Stock: {{ $item->variation->stock }}
                                        </span>
                                    </div>

                                    {{-- Price --}}
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                        <div>
                                            <p class="text-sm text-gray-600">Price per item:</p>
                                            <p class="font-bold text-purple-600">
                                                Rp {{ number_format($item->variation->product->price, 0, ',', '.') }}
                                            </p>
                                            @if($item->variation->product->point_price)
                                            <p class="text-sm text-amber-600">
                                                <i class="fas fa-star mr-1"></i>
                                                {{ number_format($item->variation->product->point_price, 0, ',', '.') }} Points
                                            </p>
                                            @endif
                                        </div>

                                        <div>
                                            <p class="text-sm text-gray-600">Subtotal:</p>
                                            <p class="font-bold text-gray-900" x-text="'Rp ' + ({{ $item->variation->product->price }} * quantity).toLocaleString('id-ID')"></p>
                                            @if($item->variation->product->point_price)
                                            <p class="text-sm text-amber-600" x-text="({{ $item->variation->product->point_price }} * quantity).toLocaleString('id-ID') + ' Points'"></p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- Quantity Controls --}}
                                <div class="flex sm:flex-col items-center justify-between sm:justify-center gap-2">
                                    <div class="flex items-center border border-gray-300 rounded-lg">
                                        <button @click="if(quantity > 1) { quantity--; updateCart({{ $item->id }}, quantity); }" 
                                                class="px-3 py-2 text-gray-600 hover:bg-gray-100 transition duration-200">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <input type="number" 
                                               x-model="quantity"
                                               min="1"
                                               max="{{ $item->variation->stock }}"
                                               @change="updateCart({{ $item->id }}, quantity)"
                                               class="w-16 text-center border-0 focus:ring-0 py-2">
                                        <button @click="if(quantity < {{ $item->variation->stock }}) { quantity++; updateCart({{ $item->id }}, quantity); }" 
                                                class="px-3 py-2 text-gray-600 hover:bg-gray-100 transition duration-200">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>

                                    {{-- Remove Button --}}
                                    <form action="{{ route('cart.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Remove this item from cart?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-700 p-2">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Order Summary Sidebar --}}
            <div class="lg:col-span-1">
                <div x-data="{ paymentType: 'cash' }" class="bg-white rounded-xl shadow-md p-6 sticky top-20">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Order Summary</h2>

                    {{-- Payment Method Toggle --}}
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">Payment Method</label>
                        <div class="grid grid-cols-2 gap-3">
                            <button @click="paymentType = 'cash'" 
                                    :class="paymentType === 'cash' ? 'bg-gradient-to-r from-purple-600 to-pink-600 text-white' : 'bg-gray-100 text-gray-700'"
                                    class="py-3 px-4 rounded-lg font-medium transition duration-300">
                                <i class="fas fa-money-bill-wave mr-2"></i>
                                Cash
                            </button>
                            <button @click="paymentType = 'points'" 
                                    :class="paymentType === 'points' ? 'bg-gradient-to-r from-purple-600 to-pink-600 text-white' : 'bg-gray-100 text-gray-700'"
                                    class="py-3 px-4 rounded-lg font-medium transition duration-300">
                                <i class="fas fa-star mr-2"></i>
                                Points
                            </button>
                        </div>
                    </div>

                    {{-- Summary Details --}}
                    <div class="space-y-3 border-t border-b border-gray-200 py-4 mb-4">
                        <div class="flex justify-between text-gray-600">
                            <span>Items ({{ $cartItems->count() }})</span>
                            <span class="font-medium text-gray-900">{{ $cartItems->count() }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Subtotal</span>
                            <span class="font-medium text-gray-900" x-show="paymentType === 'cash'">
                                Rp {{ number_format($totalPrice, 0, ',', '.') }}
                            </span>
                            <span class="font-medium text-amber-600" x-show="paymentType === 'points'" x-cloak>
                                {{ number_format($totalPointPrice, 0, ',', '.') }} Points
                            </span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Shipping</span>
                            <span class="font-medium text-green-600">FREE</span>
                        </div>
                    </div>

                    {{-- Total --}}
                    <div class="flex justify-between items-center mb-6">
                        <span class="text-lg font-bold text-gray-900">Total</span>
                        <div class="text-right">
                            <p class="text-2xl font-bold text-purple-600" x-show="paymentType === 'cash'">
                                Rp {{ number_format($totalPrice, 0, ',', '.') }}
                            </p>
                            <p class="text-2xl font-bold text-amber-600" x-show="paymentType === 'points'" x-cloak>
                                {{ number_format($totalPointPrice, 0, ',', '.') }} Points
                            </p>
                        </div>
                    </div>

                    {{-- Checkout Button --}}
                    <button class="w-full bg-gradient-to-r from-purple-600 to-pink-600 text-white py-4 rounded-lg font-bold hover:from-purple-700 hover:to-pink-700 transition duration-300 shadow-lg mb-4">
                        <i class="fas fa-lock mr-2"></i>
                        Proceed to Checkout
                    </button>

                    {{-- Security Badge --}}
                    <div class="bg-gray-50 rounded-lg p-3 text-center mb-4">
                        <i class="fas fa-shield-alt text-green-600 mr-2"></i>
                        <span class="text-sm text-gray-600">Secure Checkout</span>
                    </div>

                    {{-- Continue Shopping --}}
                    <a href="{{ route('home') }}" class="block text-center text-purple-600 hover:text-purple-700 font-medium">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Continue Shopping
                    </a>
                </div>
            </div>
        </div>
        @else
        {{-- Empty Cart State --}}
        <div class="bg-white rounded-xl shadow-md p-12 text-center">
            <div class="max-w-md mx-auto">
                <i class="fas fa-shopping-cart text-6xl text-gray-300 mb-6"></i>
                <h2 class="text-2xl font-bold text-gray-900 mb-3">Your cart is empty</h2>
                <p class="text-gray-600 mb-8">Looks like you haven't added any items to your cart yet.</p>
                <a href="{{ route('home') }}" 
                   class="inline-block bg-gradient-to-r from-purple-600 to-pink-600 text-white px-8 py-3 rounded-full font-semibold hover:from-purple-700 hover:to-pink-700 transition duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-shopping-bag mr-2"></i>
                    Start Shopping
                </a>

                {{-- Quick Links --}}
                <div class="grid grid-cols-2 gap-4 mt-8">
                    <a href="#" class="p-4 border border-gray-200 rounded-lg hover:border-purple-600 hover:shadow-md transition duration-300">
                        <i class="fas fa-fire text-orange-500 text-2xl mb-2"></i>
                        <p class="text-sm font-medium text-gray-900">Hot Deals</p>
                    </a>
                    <a href="#" class="p-4 border border-gray-200 rounded-lg hover:border-purple-600 hover:shadow-md transition duration-300">
                        <i class="fas fa-star text-yellow-500 text-2xl mb-2"></i>
                        <p class="text-sm font-medium text-gray-900">Best Sellers</p>
                    </a>
                    <a href="#" class="p-4 border border-gray-200 rounded-lg hover:border-purple-600 hover:shadow-md transition duration-300">
                        <i class="fas fa-gift text-pink-500 text-2xl mb-2"></i>
                        <p class="text-sm font-medium text-gray-900">Rewards</p>
                    </a>
                    <a href="#" class="p-4 border border-gray-200 rounded-lg hover:border-purple-600 hover:shadow-md transition duration-300">
                        <i class="fas fa-sparkles text-purple-500 text-2xl mb-2"></i>
                        <p class="text-sm font-medium text-gray-900">New Arrivals</p>
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>
</section>

{{-- Trust Badges --}}
<section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <div class="flex flex-col items-center text-center">
                <div class="bg-gradient-to-br from-purple-100 to-pink-100 p-4 rounded-full mb-3">
                    <i class="fas fa-shipping-fast text-3xl text-purple-600"></i>
                </div>
                <h3 class="font-semibold text-gray-900">Free Delivery</h3>
                <p class="text-sm text-gray-600">On all orders</p>
            </div>
            <div class="flex flex-col items-center text-center">
                <div class="bg-gradient-to-br from-purple-100 to-pink-100 p-4 rounded-full mb-3">
                    <i class="fas fa-shield-alt text-3xl text-purple-600"></i>
                </div>
                <h3 class="font-semibold text-gray-900">Safe Payment</h3>
                <p class="text-sm text-gray-600">100% secure</p>
            </div>
            <div class="flex flex-col items-center text-center">
                <div class="bg-gradient-to-br from-purple-100 to-pink-100 p-4 rounded-full mb-3">
                    <i class="fas fa-headset text-3xl text-purple-600"></i>
                </div>
                <h3 class="font-semibold text-gray-900">24/7 Support</h3>
                <p class="text-sm text-gray-600">Dedicated support</p>
            </div>
            <div class="flex flex-col items-center text-center">
                <div class="bg-gradient-to-br from-purple-100 to-pink-100 p-4 rounded-full mb-3">
                    <i class="fas fa-undo text-3xl text-purple-600"></i>
                </div>
                <h3 class="font-semibold text-gray-900">Easy Returns</h3>
                <p class="text-sm text-gray-600">30-day policy</p>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
function updateCart(cartId, quantity) {
    fetch(`/cart/${cartId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ quantity: quantity })
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            alert(data.message);
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to update cart');
    });
}
</script>
@endpush
