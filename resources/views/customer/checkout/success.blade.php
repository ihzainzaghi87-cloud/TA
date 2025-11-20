@extends('customer.layouts.app')

@section('title', 'Pesanan Berhasil')

@push('styles')
<style>
    .success-gradient {
        background: linear-gradient(90deg, #10b981, #059669);
    }
    .order-primary-gradient {
        background: linear-gradient(90deg, #9333ea, #ec4899);
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 via-pink-50 to-white py-12">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Success Animation --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-24 h-24 success-gradient rounded-full mb-6 shadow-2xl animate-bounce">
                <i class="fas fa-check text-white text-5xl"></i>
            </div>
            <h1 class="text-4xl font-bold text-transparent bg-clip-text order-primary-gradient mb-3">
                Pesanan Berhasil Dibuat!
            </h1>
            <p class="text-gray-600 text-lg">Terima kasih atas pesanan Anda</p>
        </div>

        {{-- Order Summary Card --}}
        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8 mb-6">
            <div class="border-b border-gray-200 pb-6 mb-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Nomor Pesanan</p>
                        <p class="text-3xl font-bold text-purple-600">{{ $order->order_number }}</p>
                    </div>
                    <div class="text-left md:text-right">
                        <span class="inline-flex items-center px-4 py-2 bg-yellow-100 text-yellow-800 rounded-full text-sm font-semibold">
                            <i class="fas fa-clock mr-2"></i>
                            {{ $order->status }}
                        </span>
                    </div>
                </div>
                <p class="text-sm text-gray-500 flex items-center gap-2">
                    <i class="fas fa-calendar"></i>
                    {{ $order->created_at->format('d F Y, H:i') }} WIB
                </p>
            </div>

            {{-- Order Details --}}
            <div class="space-y-4 mb-6">
                <h3 class="font-bold text-gray-900 flex items-center gap-2 text-lg">
                    <i class="fas fa-box text-purple-600"></i>
                    Detail Pesanan
                </h3>
                
                @foreach($order->orderItems as $item)
                    <div class="flex gap-4 pb-4 border-b border-gray-100 last:border-0">
                        <div class="w-16 h-16 flex-shrink-0 bg-gray-100 rounded-lg overflow-hidden">
                            @if($item->variation->product->images->count() > 0)
                                <img src="{{ asset('storage/products/' . $item->variation->product->images->first()->image) }}" 
                                     alt="{{ $item->product_name }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gray-200">
                                    <i class="fas fa-image text-gray-400"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900">{{ $item->product_name }}</p>
                            <p class="text-sm text-gray-600">{{ $item->variant_details }}</p>
                            <p class="text-sm text-gray-600">Qty: {{ $item->quantity }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-purple-600">
                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                            </p>
                            @if($item->point_subtotal > 0)
                                <p class="text-sm text-amber-600">
                                    <i class="fas fa-star text-xs"></i>
                                    {{ number_format($item->point_subtotal, 0, ',', '.') }} poin
                                </p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Price Summary --}}
            <div class="bg-gray-50 rounded-2xl p-6 space-y-3">
                <div class="flex justify-between text-gray-700">
                    <span>Subtotal</span>
                    <span class="font-semibold">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-gray-700">
                    <span>Ongkir</span>
                    <span class="font-semibold">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                </div>
                @if($order->total_points_used > 0)
                    <div class="flex justify-between text-red-600">
                        <span><i class="fas fa-star mr-1"></i>Poin Digunakan</span>
                        <span class="font-semibold">{{ number_format($order->total_points_used, 0, ',', '.') }} poin</span>
                    </div>
                @endif
                <div class="border-t-2 border-gray-300 pt-3">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-bold text-gray-900">Total Bayar</span>
                        <span class="text-2xl font-bold text-transparent bg-clip-text order-primary-gradient">
                            Rp {{ number_format($order->total, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Points Earned --}}
            @if($order->points_earned > 0)
                <div class="mt-6 bg-gradient-to-r from-amber-50 to-yellow-50 rounded-2xl p-6 border-2 border-amber-200">
                    <p class="flex items-center text-amber-800 font-semibold mb-2 gap-2">
                        <i class="fas fa-gift text-amber-500 text-xl"></i>
                        Selamat! Anda Mendapat Reward
                    </p>
                    <p class="text-3xl font-bold text-amber-600">
                        <i class="fas fa-star"></i>
                        +{{ number_format($order->points_earned, 0, ',', '.') }} Poin
                    </p>
                    <p class="text-sm text-gray-600 mt-2">
                        Poin dapat digunakan untuk pembelian berikutnya
                    </p>
                </div>
            @endif

            {{-- Shipping Address --}}
            <div class="mt-6 p-4 bg-blue-50 rounded-2xl">
                <p class="font-semibold text-gray-900 mb-2 flex items-center gap-2">
                    <i class="fas fa-map-marker-alt text-blue-600"></i>
                    Alamat Pengiriman
                </p>
                <p class="text-gray-700">{{ $order->shipping_address }}</p>
                <p class="text-gray-700 mt-1 flex items-center gap-2">
                    <i class="fas fa-phone"></i>{{ $order->phone }}
                </p>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('orders.show', $order->id) }}" 
               class="inline-flex items-center justify-center px-8 py-3 order-primary-gradient text-white rounded-full hover:shadow-2xl transition duration-300 font-semibold">
                <i class="fas fa-file-invoice mr-2"></i>
                Lihat Detail Pesanan
            </a>
            <a href="{{ route('home') }}" 
               class="inline-flex items-center justify-center px-8 py-3 bg-white text-purple-600 border-2 border-purple-600 rounded-full hover:bg-purple-50 transition duration-300 font-semibold">
                <i class="fas fa-home mr-2"></i>
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
@endsection
