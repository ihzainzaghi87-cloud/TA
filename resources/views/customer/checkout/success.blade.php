@extends('customer.layouts.app')

@section('title', 'Pesanan Berhasil')

@push('styles')
<style>
    .success-hero-bg { background-color: #FAD470; }
    .success-primary-btn { background-color: #000; color: #FAD471; }
    .success-primary-btn:hover { background-color: #333; }
    .success-secondary-btn { background-color: #FAD470; color: #000; }
    .success-secondary-btn:hover { background-color: #F59E0B; }
    .success-card { 
        background: #fff; 
        border: 2px solid #FAD470;
        border-radius: 1.5rem;
    }
    @keyframes checkmark {
        0% { transform: scale(0); }
        50% { transform: scale(1.2); }
        100% { transform: scale(1); }
    }
    .animate-checkmark {
        animation: checkmark 0.5s ease-out forwards;
    }
</style>
@endpush

@section('content')
{{-- Hero Section --}}
<section class="success-hero-bg py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="inline-flex items-center justify-center w-24 h-24 bg-green-500 rounded-full mb-6 shadow-2xl animate-checkmark">
            <i class="fas fa-check text-white text-5xl"></i>
        </div>
        <h1 class="font-bebas text-5xl md:text-6xl text-black mb-3">PESANAN BERHASIL!</h1>
        <p class="text-black/70 text-lg">Terima kasih atas pesanan Anda</p>
    </div>
</section>

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 pb-20 relative z-10">
    {{-- Order Summary Card --}}
    <div class="success-card shadow-xl p-8 mb-6">
        <div class="border-b-2 border-[#FAD470] pb-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Nomor Pesanan</p>
                    <p class="text-3xl font-bebas text-black">{{ $order->order_number }}</p>
                </div>
                <div class="text-left md:text-right">
                    <span class="inline-flex items-center px-4 py-2 bg-[#FAD470] text-black rounded-full text-sm font-bold">
                        <i class="fas fa-clock mr-2"></i>
                        {{ $order->status }}
                    </span>
                </div>
            </div>
            <p class="text-sm text-gray-500 flex items-center gap-2">
                <i class="fas fa-calendar text-[#FAD470]"></i>
                {{ $order->created_at->format('d F Y, H:i') }} WIB
            </p>
        </div>

        {{-- Order Details --}}
        <div class="space-y-4 mb-6">
            <h3 class="font-bebas text-2xl text-black flex items-center gap-2">
                <i class="fas fa-box text-[#FAD470]"></i>
                DETAIL PESANAN
            </h3>
            
            @foreach($order->orderItems as $item)
                <div class="flex gap-4 pb-4 border-b-2 border-gray-100 last:border-0">
                    <div class="w-16 h-16 flex-shrink-0 bg-gray-100 rounded-2xl overflow-hidden border-2 border-gray-100">
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
                        <p class="font-bold text-gray-900">{{ $item->product_name }}</p>
                        <p class="text-sm text-gray-600">{{ $item->variant_details }}</p>
                        <p class="text-sm text-gray-600">Qty: {{ $item->quantity }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-black">
                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                        </p>
                        @if($item->point_subtotal > 0)
                            <p class="text-sm text-amber-600 font-medium">
                                <i class="fas fa-star text-xs"></i>
                                {{ number_format($item->point_subtotal, 0, ',', '.') }} poin
                            </p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Price Summary --}}
        <div class="bg-[#FAD470]/20 rounded-2xl p-6 space-y-3 border-2 border-[#FAD470]">
            <div class="flex justify-between text-gray-700">
                <span>Subtotal</span>
                <span class="font-bold text-black">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-gray-700">
                <span class="flex items-center gap-2">
                    <i class="fas fa-truck text-[#FAD470]"></i> Ongkir
                </span>
                <span class="font-bold text-black">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
            </div>
            @if($order->total_points_used > 0)
                <div class="flex justify-between text-red-600">
                    <span><i class="fas fa-star mr-1"></i>Poin Digunakan</span>
                    <span class="font-bold">{{ number_format($order->total_points_used, 0, ',', '.') }} poin</span>
                </div>
            @endif
            <div class="border-t-2 border-[#FAD470] pt-3">
                <div class="flex justify-between items-center">
                    <span class="text-lg font-bold text-gray-900">Total Bayar</span>
                    <span class="text-3xl font-bebas text-black">
                        Rp {{ number_format($order->total, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Points Earned --}}
        @if($order->points_earned > 0)
            <div class="mt-6 bg-gradient-to-r from-amber-50 to-yellow-50 rounded-2xl p-6 border-2 border-amber-300">
                <p class="flex items-center text-amber-800 font-bold mb-2 gap-2">
                    <i class="fas fa-gift text-amber-500 text-xl"></i>
                    Selamat! Anda Mendapat Reward
                </p>
                <p class="text-4xl font-bebas text-amber-600">
                    <i class="fas fa-star"></i>
                    +{{ number_format($order->points_earned, 0, ',', '.') }} Poin
                </p>
                <p class="text-sm text-gray-600 mt-2">
                    Poin dapat digunakan untuk pembelian berikutnya
                </p>
            </div>
        @endif

        {{-- Shipping Address --}}
        <div class="mt-6 p-4 bg-blue-50 rounded-2xl border-2 border-blue-200">
            <p class="font-bold text-gray-900 mb-2 flex items-center gap-2">
                <i class="fas fa-map-marker-alt text-blue-600"></i>
                Alamat Pengiriman
            </p>
            <p class="text-gray-700">{{ $order->shippingAddress->full_address }}</p>
            <p class="text-gray-700 mt-1 flex items-center gap-2">
                <i class="fas fa-phone text-blue-600"></i>{{ $order->shippingAddress->phone }}
            </p>
        </div>
    </div>

    {{-- Action Buttons --}}
    <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="{{ route('customer.order-detail', $order->id) }}" 
           class="inline-flex items-center justify-center px-8 py-4 success-primary-btn rounded-full font-bold text-lg shadow-xl hover:shadow-2xl transition duration-300">
            <i class="fas fa-file-invoice mr-2"></i>
            Lihat Detail Pesanan
        </a>
        <a href="{{ route('home') }}" 
           class="inline-flex items-center justify-center px-8 py-4 success-secondary-btn rounded-full font-bold text-lg hover:shadow-lg transition duration-300">
            <i class="fas fa-home mr-2"></i>
            Kembali ke Beranda
        </a>
    </div>
</div>
@endsection
