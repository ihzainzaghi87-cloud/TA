@extends('customer.layouts.app')

@section('title', 'Checkout - The Paranoia')

@push('styles')
<style>
    [x-cloak] { display: none !important; }
    .checkout-hero-gradient {
        background: linear-gradient(90deg, #9333ea, #ec4899, #ef4444);
    }
    .checkout-primary-gradient {
        background: linear-gradient(90deg, #9333ea, #ec4899);
    }
    .checkout-card-shadow {
        box-shadow: 0 4px 20px rgba(147, 51, 234, 0.1);
    }
</style>
@endpush

@section('content')
{{-- Hero Section --}}
<section class="checkout-hero-gradient text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <p class="text-sm uppercase tracking-[0.3em] text-white/80 mb-4">Secure Checkout</p>
        <h1 class="text-3xl md:text-4xl font-bold">Finalize Your Order</h1>
        <p class="mt-4 text-white/80 max-w-2xl mx-auto">
            Review your items, fill in shipping details, and complete your purchase securely.
        </p>
    </div>
</section>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-12 pb-20 relative z-10">
    
    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="mb-6">
            <x-alert type="success" :message="session('success')" />
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6">
            <x-alert type="error" :message="session('error')" />
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6">
            <x-alert type="error" :message="$errors->first()" />
        </div>
    @endif

    <form action="{{ route('orders.store') }}" method="POST">
        @csrf
        <input type="hidden" name="selected_variations" value="{{ e(old('selected_variations', json_encode(session('selected_variations', [])))) }}">
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Left Column - Shipping Info & Items --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- Shipping Address Card --}}
                <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 rounded-full checkout-primary-gradient flex items-center justify-center text-white shadow-lg">
                            <i class="fas fa-map-marker-alt text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Alamat Pengiriman</h2>
                            <p class="text-sm text-gray-500">Informasi pengiriman pesanan Anda</p>
                        </div>
                    </div>
                    
                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Alamat Lengkap <span class="text-red-500">*</span>
                            </label>
                            <textarea name="shipping_address" 
                                      rows="3" 
                                      required
                                      class="w-full px-4 py-3 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200 resize-none"
                                      placeholder="Jl. Contoh No. 123, RT/RW, Kelurahan, Kecamatan, Kota, Provinsi, Kode Pos">{{ old('shipping_address') }}</textarea>
                            @error('shipping_address')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nomor Telepon <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fas fa-phone text-gray-400"></i>
                                </div>
                                <input type="tel" 
                                       name="phone" 
                                       required
                                       value="{{ old('phone') }}"
                                       class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200"
                                       placeholder="08123456789">
                            </div>
                            @error('phone')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Catatan Pesanan <span class="text-gray-400 font-normal">(Opsional)</span>
                            </label>
                            <textarea name="notes" 
                                      rows="2"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200 resize-none"
                                      placeholder="Catatan untuk penjual, misalnya: warna alternatif, dll">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Order Items Card --}}
                <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-full checkout-primary-gradient flex items-center justify-center text-white shadow-lg">
                                <i class="fas fa-shopping-bag text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900">Item Pesanan</h2>
                                <p class="text-sm text-gray-500">{{ $cartItems->count() }} produk</p>
                            </div>
                        </div>
                        <span class="bg-purple-100 text-purple-700 px-4 py-2 rounded-full text-sm font-semibold">
                            {{ $cartItems->sum('quantity') }} Item
                        </span>
                    </div>

                    <div class="space-y-4">
                        @foreach($cartItems as $item)
                            @php
                                $product = $item->variation->product;
                                $variation = $item->variation;
                            @endphp
                            
                            <article class="flex gap-4 pb-5 border-b border-gray-100 last:border-0 last:pb-0">
                                {{-- Product Image --}}
                                <div class="w-20 h-20 shrink-0">
                                    @if($product->images && $product->images->count() > 0)
                                        <img src="{{ asset('storage/products/' . $product->images->first()->image) }}" 
                                             alt="{{ $product->name }}"
                                             class="w-full h-full object-cover rounded-xl shadow-sm">
                                    @else
                                        <div class="w-full h-full bg-linear-to-br from-gray-200 to-gray-300 rounded-xl flex items-center justify-center">
                                            <i class="fas fa-image text-gray-400 text-2xl"></i>
                                        </div>
                                    @endif
                                </div>

                                {{-- Product Info --}}
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-bold text-gray-900 mb-1 truncate">{{ $product->name }}</h3>
                                    <p class="text-sm text-gray-500 flex flex-wrap gap-2 mb-2">
                                        @if($variation->color)
                                            <span class="inline-flex items-center gap-1">
                                                <i class="fas fa-palette text-xs text-purple-500"></i>
                                                {{ ucfirst($variation->color) }}
                                            </span>
                                        @endif
                                        @if($variation->size)
                                            <span class="inline-flex items-center gap-1">
                                                <i class="fas fa-ruler text-xs text-purple-500"></i>
                                                {{ strtoupper($variation->size) }}
                                            </span>
                                        @endif
                                        <span class="inline-flex items-center gap-1">
                                            <i class="fas fa-box text-xs text-purple-500"></i>
                                            Qty: {{ $item->quantity }}
                                        </span>
                                    </p>
                                </div>

                                {{-- Price --}}
                                <div class="text-right">
                                    <p class="font-bold text-purple-600 text-lg">
                                        Rp {{ number_format($product->price * $item->quantity, 0, ',', '.') }}
                                    </p>
                                    @if($product->point_price)
                                        <p class="text-sm text-amber-600 font-medium mt-1">
                                            <i class="fas fa-star text-xs"></i>
                                            {{ number_format($product->point_price * $item->quantity, 0, ',', '.') }} Poin
                                        </p>
                                    @endif
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Right Column - Order Summary --}}
            <aside class="lg:col-span-1">
                <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8 space-y-6 sticky top-24">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Ringkasan Pesanan</h2>
                        <p class="text-sm text-gray-500">{{ $cartItems->sum('quantity') }} barang</p>
                    </div>

                    <div class="space-y-4">
                        <div class="flex justify-between text-gray-700">
                            <span>Subtotal</span>
                            <span class="font-semibold">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        
                        <div class="flex justify-between text-gray-700">
                            <span class="flex items-center gap-2">
                                <i class="fas fa-truck text-purple-500"></i>
                                Ongkir
                            </span>
                            <span class="font-semibold">Rp {{ number_format($shippingCost, 0, ',', '.') }}</span>
                        </div>

                        {{-- PRODUCT POINTS REQUIRED --}}
                        @if($totalPointsNeeded > 0)
                            <div class="pt-4 border-t border-gray-200">
                                <div class="bg-linear-to-r from-red-50 to-pink-50 rounded-2xl p-4 border-2 border-red-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <div>
                                            <p class="text-sm font-bold text-red-700 flex items-center gap-2">
                                                <i class="fas fa-exclamation-circle"></i>
                                                Poin Dibutuhkan
                                            </p>
                                            <p class="text-xs text-gray-600 mt-1">
                                                Otomatis terpotong saat checkout
                                            </p>
                                        </div>
                                        <p class="text-2xl font-bold text-red-600">
                                            <i class="fas fa-star"></i>
                                            {{ number_format($totalPointsNeeded, 0, ',', '.') }}
                                        </p>
                                    </div>
                                    
                                    @if(!$hasEnoughPoints)
                                        <div class="mt-3 bg-red-100 border border-red-300 rounded-xl p-3">
                                            <p class="text-sm font-semibold text-red-700 flex items-center gap-2">
                                                <i class="fas fa-times-circle"></i>
                                                Poin Anda Tidak Mencukupi!
                                            </p>
                                            <p class="text-xs text-red-600 mt-1">
                                                Anda punya <strong>{{ number_format($availablePoints, 0, ',', '.') }}</strong> poin, 
                                                butuh <strong>{{ number_format($totalPointsNeeded, 0, ',', '.') }}</strong> poin
                                            </p>
                                        </div>
                                    @else
                                        <div class="mt-3 bg-green-100 border border-green-300 rounded-xl p-3">
                                            <p class="text-sm font-semibold text-green-700 flex items-center gap-2">
                                                <i class="fas fa-check-circle"></i>
                                                Poin Anda Cukup
                                            </p>
                                            <p class="text-xs text-green-600 mt-1">
                                                Sisa poin: <strong>{{ number_format($availablePoints - $totalPointsNeeded, 0, ',', '.') }}</strong> poin
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <div class="pt-4 border-t-2 border-gray-200">
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-lg font-bold text-gray-900">Total Bayar</span>
                                <span class="text-3xl font-bold text-transparent bg-clip-text checkout-primary-gradient">
                                    Rp {{ number_format($total, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        {{-- Points Reward Info --}}
                        <div class="bg-linear-to-r from-purple-50 to-pink-50 rounded-2xl p-4 border border-purple-200">
                            <p class="flex items-center text-sm font-semibold text-purple-700 gap-2">
                                <i class="fas fa-gift text-pink-500"></i>
                                Reward Poin dari Pesanan Ini:
                            </p>
                            <p class="text-2xl font-bold text-purple-600 mt-1">
                                <i class="fas fa-star text-amber-400"></i>
                                +{{ number_format($pointsWillEarn, 0, ',', '.') }} Poin
                            </p>
                            <p class="text-xs text-gray-600 mt-2 flex items-center gap-1">
                                <i class="fas fa-info-circle"></i>
                                Rp 10.000 = 1 Poin
                            </p>
                        </div>
                    </div>

                    @php
                        $disableCheckout = !$hasEnoughPoints && $totalPointsNeeded > 0;
                    @endphp
                    {{-- Submit Button --}}
                        <button type="submit"
                            @if($disableCheckout) disabled @endif
                            @class([
                                'w-full py-4 rounded-full font-bold text-lg shadow-xl transform transition duration-300',
                                'bg-gray-400 text-gray-700 cursor-not-allowed' => $disableCheckout,
                                'checkout-primary-gradient text-white hover:shadow-2xl hover:-translate-y-0.5' => ! $disableCheckout,
                            ])>
                        <i class="fas fa-lock mr-2"></i>
                        @if($disableCheckout)
                            Poin Tidak Mencukupi
                        @else
                            Buat Pesanan Sekarang
                        @endif
                    </button>

                    {{-- Back to Cart --}}
                    <a href="{{ route('cart.index') }}" 
                       class="w-full inline-flex items-center justify-center gap-2 border border-purple-100 text-purple-600 font-medium py-3 rounded-full hover:bg-purple-50 transition">
                        <i class="fas fa-arrow-left"></i>
                        Kembali ke Keranjang
                    </a>

                    {{-- Security Info --}}
                    <div class="space-y-3 text-xs text-gray-600">
                        <div class="flex items-start gap-3 p-3 bg-green-50 rounded-xl">
                            <i class="fas fa-shield-alt text-green-500 text-lg mt-0.5"></i>
                            <div>
                                <p class="font-semibold text-green-700">Transaksi Aman</p>
                                <p>Data Anda dilindungi dengan enkripsi SSL</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 p-3 bg-blue-50 rounded-xl">
                            <i class="fas fa-truck text-blue-500 text-lg mt-0.5"></i>
                            <div>
                                <p class="font-semibold text-blue-700">Pengiriman Cepat</p>
                                <p>Estimasi 2-3 hari kerja</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 p-3 bg-purple-50 rounded-xl">
                            <i class="fas fa-headset text-purple-500 text-lg mt-0.5"></i>
                            <div>
                                <p class="font-semibold text-purple-700">Bantuan 24/7</p>
                                <p>Customer service siap membantu</p>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </form>
</div>
@endsection
