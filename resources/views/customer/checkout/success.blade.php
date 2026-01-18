@extends('customer.layouts.app')

@section('title', 'Order Success - The Paranoia')

@push('styles')
<style>
    /* Hero Section - Dark Mode */
    .success-hero-bg { 
        background-color: #0c0c0c; 
    }

    /* Primary Button - Solid Black */
    .success-primary-btn { 
        background-color: #1A1A1D; 
        color: #ffffff; 
        border: 1px solid #1A1A1D;
        transition: all 0.3s ease;
    }
    .success-primary-btn:hover { 
        background-color: #333333; 
        border-color: #333333;
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.15);
    }

    /* Secondary Button - Outline */
    .success-secondary-btn { 
        background-color: #ffffff; 
        color: #1A1A1D; 
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
    }
    .success-secondary-btn:hover { 
        background-color: #f3f4f6; 
        border-color: #1A1A1D;
        color: #000;
    }

    /* Card Styling */
    .success-card { 
        background: #fff; 
        border: 1px solid #e5e7eb;
        border-radius: 1.5rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    }

    /* Animation */
    @keyframes checkmark {
        0% { transform: scale(0); opacity: 0; }
        50% { transform: scale(1.2); }
        100% { transform: scale(1); opacity: 1; }
    }
    .animate-checkmark {
        animation: checkmark 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55) forwards;
    }
</style>
@endpush

@section('content')
{{-- Hero Section --}}
<section class="success-hero-bg py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="inline-flex items-center justify-center w-24 h-24 bg-white rounded-full mb-6 shadow-2xl animate-checkmark">
            <i class="fas fa-check text-green-600 text-5xl"></i>
        </div>
        <h1 class="font-bebas text-5xl md:text-7xl text-white mb-3 tracking-wide">ORDER CONFIRMED</h1>
        <p class="text-gray-400 text-lg tracking-wide">Thank you for your purchase.</p>
    </div>
</section>

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 pb-20 relative z-10">
    {{-- Order Summary Card --}}
    <div class="success-card p-8 mb-8">
        <div class="border-b border-gray-100 pb-8 mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 mb-2">
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-widest mb-1">Order Number</p>
                    <p class="text-4xl font-bebas text-[#1A1A1D] tracking-wide">{{ $order->order_number }}</p>
                </div>
                <div class="text-left md:text-right">
                    <span class="inline-flex items-center px-4 py-2 bg-[#1A1A1D] text-white rounded-full text-xs font-bold uppercase tracking-wider">
                        <i class="fas fa-clock mr-2 text-gray-300"></i>
                        {{ $order->status }}
                    </span>
                </div>
            </div>
            <p class="text-sm text-gray-500 flex items-center gap-2">
                <i class="fas fa-calendar-alt text-[#1A1A1D]"></i>
                {{ $order->created_at->format('d F Y, H:i') }} WIB
            </p>
        </div>

        {{-- Order Details --}}
        <div class="space-y-6 mb-8">
            <h3 class="font-bebas text-2xl text-[#1A1A1D] flex items-center gap-2">
                <i class="fas fa-box-open text-gray-400 text-lg"></i>
                ORDER ITEMS
            </h3>
            
            @foreach($order->orderItems as $item)
                <div class="flex gap-5 pb-6 border-b border-gray-100 last:border-0 last:pb-0">
                    <div class="w-20 h-20 flex-shrink-0 bg-[#F3F5F9] rounded-xl overflow-hidden border border-gray-100 p-2">
                        @if($item->variation->product->images->count() > 0)
                            <img src="{{ asset('storage/products/' . $item->variation->product->images->first()->image) }}" 
                                 alt="{{ $item->product_name }}"
                                 class="w-full h-full object-contain mix-blend-multiply">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                <i class="fas fa-image text-2xl"></i>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1">
                        <p class="font-bold text-[#1A1A1D] text-lg leading-tight mb-1">{{ $item->product_name }}</p>
                        <div class="flex flex-wrap gap-2 mb-2">
                            <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded font-medium">
                                {{ $item->variant_details }}
                            </span>
                            <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded font-medium">
                                Qty: {{ $item->quantity }}
                            </span>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-[#1A1A1D] text-lg">
                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                        </p>
                        @if($item->point_subtotal > 0)
                            <p class="text-xs text-gray-500 font-medium mt-1">
                                <i class="fas fa-star text-[10px]"></i>
                                {{ number_format($item->point_subtotal, 0, ',', '.') }} pts
                            </p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Price Summary --}}
        <div class="bg-gray-50 rounded-2xl p-6 space-y-4 border border-gray-100">
            <div class="flex justify-between text-gray-600 text-sm">
                <span>Subtotal</span>
                <span class="font-bold text-[#1A1A1D]">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-gray-600 text-sm">
                <span class="flex items-center gap-2">
                    <i class="fas fa-truck text-gray-400"></i> Shipping
                </span>
                <span class="font-bold text-[#1A1A1D]">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
            </div>
            
            @if($order->total_points_used > 0)
                <div class="flex justify-between text-red-600 bg-red-50 p-3 rounded-lg border border-red-100">
                    <span class="flex items-center gap-2 text-sm font-medium">
                        <i class="fas fa-coins"></i> Points Used
                    </span>
                    <span class="font-bold">- {{ number_format($order->total_points_used, 0, ',', '.') }} pts</span>
                </div>
            @endif

            <div class="border-t border-gray-200 pt-4 mt-2">
                <div class="flex justify-between items-center">
                    <span class="text-lg font-bold text-[#1A1A1D] uppercase">Total Paid</span>
                    <span class="text-3xl font-bebas text-[#1A1A1D]">
                        Rp {{ number_format($order->total, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Points Earned --}}
        @if($order->points_earned > 0)
            <div class="mt-6 bg-[#1A1A1D] rounded-2xl p-6 text-white relative overflow-hidden">
                <div class="absolute right-0 top-0 opacity-10 transform translate-x-1/4 -translate-y-1/4">
                    <i class="fas fa-star text-9xl"></i>
                </div>
                <div class="relative z-10">
                    <p class="flex items-center text-gray-300 font-bold mb-2 gap-2 text-sm uppercase tracking-wide">
                        <i class="fas fa-gift"></i>
                        Points Reward Earned
                    </p>
                    <p class="text-4xl font-bebas text-white">
                        +{{ number_format($order->points_earned, 0, ',', '.') }} PTS
                    </p>
                    <p class="text-xs text-gray-400 mt-2">
                        Use these points for your next purchase.
                    </p>
                </div>
            </div>
        @endif

        {{-- Shipping Address --}}
        <div class="mt-6 p-6 bg-white rounded-2xl border border-gray-200">
            <p class="font-bold text-[#1A1A1D] mb-3 flex items-center gap-2 uppercase tracking-wide text-sm">
                <i class="fas fa-map-marker-alt text-gray-400"></i>
                Shipping Address
            </p>
            <p class="text-gray-800 font-medium">{{ $order->shippingAddress->full_address }}</p>
            <p class="text-gray-500 mt-2 flex items-center gap-2 text-sm">
                <i class="fas fa-phone"></i> {{ $order->shippingAddress->phone }}
            </p>
        </div>
    </div>

    {{-- Action Buttons --}}
    <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="{{ route('customer.order-detail', $order->id) }}" 
           class="inline-flex items-center justify-center px-8 py-4 success-primary-btn rounded-xl font-bold text-base shadow-lg hover:shadow-xl transition duration-300 gap-2">
            <i class="fas fa-file-invoice"></i>
            Order Details
        </a>
        <a href="{{ route('home') }}" 
           class="inline-flex items-center justify-center px-8 py-4 success-secondary-btn rounded-xl font-bold text-base transition duration-300 gap-2">
            <i class="fas fa-home"></i>
            Back to Home
        </a>
    </div>
</div>
@endsection