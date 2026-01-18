@extends('customer.layouts.app')

@section('title', 'Detail Pesanan #' . $order->order_number)

@push('styles')
<style>
    /* Card Styling - Sharp & Clean */
    .profile-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 1.5rem; /* Rounded 24px */
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        transition: all 0.3s ease;
    }
    .profile-card:hover {
        border-color: #1A1A1D;
        transform: translateY(-2px);
    }

    /* Status Badges - Monochrome / Semantic High Contrast */
    .status-badge {
        padding: 6px 16px;
        border-radius: 9999px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border: 1px solid transparent;
    }

    .status-pending { background: #f3f4f6; color: #4b5563; border-color: #d1d5db; }
    .status-processing { background: #dbeafe; color: #1e40af; border-color: #93c5fd; }
    .status-shipped { background: #f3e8ff; color: #6b21a8; border-color: #d8b4fe; }
    .status-delivered { background: #1A1A1D; color: #ffffff; border-color: #1A1A1D; } /* Black for success/final */
    .status-cancelled { background: #fee2e2; color: #991b1b; border-color: #fca5a5; }

    /* Timeline Styles */
    .timeline-item {
        position: relative;
        padding-left: 48px;
        padding-bottom: 32px;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: 14px;
        top: 30px;
        bottom: 0;
        width: 2px;
        background: #f3f4f6;
    }

    .timeline-item:last-child::before { display: none; }

    .timeline-dot {
        position: absolute;
        left: 0;
        top: 0;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: #fff;
        border: 2px solid #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10;
        transition: all 0.3s ease;
    }

    /* Active State (Pulsing) */
    .timeline-dot.active {
        background: #fff;
        border-color: #1A1A1D;
        color: #1A1A1D;
        animation: pulse-ring 2s infinite;
    }

    /* Completed State (Solid Black) */
    .timeline-dot.completed {
        background: #1A1A1D;
        border-color: #1A1A1D;
        color: #fff;
    }

    .timeline-dot.cancelled {
        background: #ef4444;
        border-color: #ef4444;
        color: #fff;
    }

    @keyframes pulse-ring {
        0% { box-shadow: 0 0 0 0 rgba(26, 26, 29, 0.4); }
        70% { box-shadow: 0 0 0 10px rgba(26, 26, 29, 0); }
        100% { box-shadow: 0 0 0 0 rgba(26, 26, 29, 0); }
    }

    /* Product Thumbnail */
    .product-thumb {
        width: 70px;
        height: 70px;
        border-radius: 12px;
        object-fit: contain;
        background: #f9fafb;
        border: 1px solid #f3f4f6;
        padding: 4px;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 14px 0;
        border-bottom: 1px solid #f3f4f6;
        font-size: 14px;
    }

    .info-row:last-child { border-bottom: none; }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50 py-10">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <nav class="flex items-center mb-8 text-sm">
            <a href="{{ route('home') }}" class="text-gray-400 hover:text-black transition-colors">
                <i class="fas fa-home"></i>
            </a>
            <i class="fas fa-chevron-right text-gray-300 mx-3 text-xs"></i>
            <a href="{{ route('customer.index') }}" class="text-gray-400 hover:text-black transition-colors">Profile</a>
            <i class="fas fa-chevron-right text-gray-300 mx-3 text-xs"></i>
            <a href="{{ route('customer.orders') }}" class="text-gray-400 hover:text-black transition-colors">Pesanan Saya</a>
            <i class="fas fa-chevron-right text-gray-300 mx-3 text-xs"></i>
            <span class="text-[#1A1A1D] font-bold">#{{ $order->order_number }}</span>
        </nav>

        @if(session('success'))
        <div class="mb-6 bg-[#1A1A1D] text-white px-6 py-4 rounded-2xl flex items-center shadow-lg">
            <i class="fas fa-check-circle mr-3 text-green-400"></i>
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-600 px-6 py-4 rounded-2xl flex items-center">
            <i class="fas fa-exclamation-circle mr-3"></i>
            {{ session('error') }}
        </div>
        @endif

        <div class="profile-card p-8 mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <h1 class="text-3xl font-black text-[#1A1A1D] tracking-tight">Pesanan #{{ $order->order_number }}</h1>
                        <span class="status-badge status-{{ strtolower($order->status) }}">
                            {{ $order->status }}
                        </span>
                    </div>
                    <p class="text-gray-500 text-sm flex items-center gap-2">
                        <i class="fas fa-calendar text-[#1A1A1D]"></i>
                        Order dibuat pada {{ $order->created_at->format('d F Y, H:i') }}
                    </p>
                </div>

                <div class="flex flex-wrap gap-3">
                    @php
                        $isDelivered = false;
                        if (isset($trackingData)) {
                            $isDelivered = ($trackingData['delivered'] ?? false) === true
                                        || (isset($trackingData['delivery_status']['status']) && $trackingData['delivery_status']['status'] === 'DELIVERED')
                                        || (isset($trackingData['detail']['status']) && $trackingData['detail']['status'] === 'DELIVERED');
                        }
                    @endphp

                    @if($order->status == 'Delivered')
                    <a href="{{ route('customer.print-invoice', $order->id) }}" target="_blank"
                       class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-700 hover:border-[#1A1A1D] hover:text-[#1A1A1D] transition-all text-sm font-bold shadow-sm">
                        <i class="fas fa-print"></i>
                        <span>Invoice</span>
                    </a>
                    @endif
                    
                    @if($order->status == 'Shipped')
                        <a href="{{ route('customer.track-order', $order->id) }}" 
                           class="px-5 py-2.5 bg-[#1A1A1D] text-white rounded-xl text-sm font-bold hover:bg-gray-800 transition-all shadow-lg flex items-center gap-2">
                            <i class="fas fa-search-location"></i> Lacak Pesanan
                        </a>
                        
                        @if ($isDelivered)
                        <form action="{{ route('customer.confirm-received', $order->id) }}" method="POST"
                            onsubmit="return confirm('Konfirmasi pesanan diterima?')">
                            @csrf
                            <button type="submit"
                                class="px-5 py-2.5 bg-green-600 text-white rounded-xl text-sm font-bold hover:bg-green-700 transition-all shadow-md flex items-center gap-2">
                                <i class="fas fa-check-double"></i> Konfirmasi Diterima
                            </button>
                        </form>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-8">
                
                <div class="profile-card p-8">
                    <h3 class="text-xl font-bold text-[#1A1A1D] mb-6 flex items-center gap-2 uppercase tracking-wide">
                        <i class="fas fa-box-open"></i> Item Pesanan
                    </h3>
                    
                    <div class="space-y-6">
                        @foreach($order->orderItems as $item)
                        <div class="flex gap-5 items-start">
                            @if($item->variation && $item->variation->product && $item->variation->product->images->first())
                                <img src="{{ asset('storage/products/' . $item->variation->product->images->first()->image) }}" 
                                     alt="{{ $item->variation->product->name }}"
                                     class="product-thumb mix-blend-multiply">
                            @else
                                <div class="product-thumb flex items-center justify-center text-gray-300">
                                    <i class="fas fa-image text-xl"></i>
                                </div>
                            @endif

                            <div class="flex-1 min-w-0">
                                <h4 class="font-bold text-[#1A1A1D] text-base leading-tight">
                                    {{ $item->variation->product->name ?? 'Product Unavailable' }}
                                </h4>
                                <p class="text-xs text-gray-500 mt-1 uppercase tracking-wide font-semibold">
                                    {{ $item->variation->color ?? '-' }} / {{ $item->variation->size ?? '-' }}
                                </p>
                                <div class="flex items-center justify-between mt-3">
                                    <span class="px-2 py-1 bg-gray-100 rounded text-xs font-medium text-gray-600">
                                        Qty: {{ $item->quantity }}
                                    </span>
                                    <span class="font-bold text-[#1A1A1D]">
                                        Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        @if(!$loop->last) <hr class="border-gray-100"> @endif
                        @endforeach
                    </div>
                </div>

                <div class="profile-card p-8">
                    <h3 class="text-xl font-bold text-[#1A1A1D] mb-8 flex items-center gap-2 uppercase tracking-wide">
                        <i class="fas fa-stream"></i> Status Pesanan
                    </h3>
                    
                    <div>
                        @php
                            $statusOrder = ['Pending', 'Processing', 'Shipped', 'Delivered'];
                            $currentStatusIndex = array_search($order->status, $statusOrder);
                            $isCancelled = $order->status === 'Cancelled';
                        @endphp

                        {{-- Order Placed --}}
                        <div class="timeline-item">
                            <div class="timeline-dot completed">
                                <i class="fas fa-check text-xs"></i>
                            </div>
                            <div>
                                <p class="font-bold text-[#1A1A1D]">Pesanan Dibuat</p>
                                <p class="text-xs text-gray-500">{{ $order->created_at->format('d M Y, H:i') }}</p>
                                @if($order->status === 'Pending')
                                <p class="text-xs text-gray-600 mt-1 italic">Menunggu pembayaran...</p>
                                @endif
                            </div>
                        </div>

                        {{-- Payment Confirmed / Processing --}}
                        <div class="timeline-item">
                            <div class="timeline-dot {{ $currentStatusIndex >= 1 ? 'completed' : ($order->status === 'Pending' ? 'active' : '') }}">
                                @if($currentStatusIndex >= 1) <i class="fas fa-check text-xs"></i> @endif
                            </div>
                            <div>
                                <p class="font-bold {{ $currentStatusIndex >= 1 ? 'text-[#1A1A1D]' : 'text-gray-400' }}">Pembayaran Dikonfirmasi</p>
                                @if($order->status === 'Processing')
                                <p class="text-xs text-blue-600 font-medium mt-1">
                                    <i class="fas fa-circle-notch fa-spin mr-1"></i> Memproses pesanan Anda
                                </p>
                                @endif
                            </div>
                        </div>

                        {{-- Shipped --}}
                        <div class="timeline-item">
                            <div class="timeline-dot {{ $currentStatusIndex >= 2 ? 'completed' : ($order->status === 'Processing' ? 'active' : '') }}">
                                @if($currentStatusIndex >= 2) <i class="fas fa-truck text-xs"></i> @endif
                            </div>
                            <div>
                                <p class="font-bold {{ $currentStatusIndex >= 2 ? 'text-[#1A1A1D]' : 'text-gray-400' }}">Dikirim</p>
                                @if($order->tracking_number && $currentStatusIndex >= 2)
                                <div class="mt-1 p-2 bg-gray-50 rounded border border-gray-100 inline-block">
                                    <p class="text-xs text-gray-500">Nomor Pelacakan:</p>
                                    <p class="text-sm font-mono font-bold text-[#1A1A1D]">{{ $order->tracking_number }}</p>
                                    <p class="text-[10px] text-gray-400 mt-1 uppercase">{{ strtoupper($order->courier ?? '') }} - {{ $order->service }}</p>
                                </div>
                                @endif
                            </div>
                        </div>

                        {{-- Delivered --}}
                        <div class="timeline-item">
                            <div class="timeline-dot {{ $order->status === 'Delivered' ? 'completed' : ($order->status === 'Shipped' ? 'active' : '') }}">
                                @if($order->status === 'Delivered') <i class="fas fa-home text-xs"></i> @endif
                            </div>
                            <div>
                                <p class="font-bold {{ $order->status === 'Delivered' ? 'text-[#1A1A1D]' : 'text-gray-400' }}">Diterima</p>
                                @if($order->delivered_at)
                                <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($order->delivered_at)->format('d M Y, H:i') }}</p>
                                @endif
                            </div>
                        </div>

                        {{-- Cancelled --}}
                        @if($isCancelled)
                        <div class="timeline-item">
                            <div class="timeline-dot cancelled">
                                <i class="fas fa-times text-xs"></i>
                            </div>
                            <div>
                                <p class="font-bold text-red-600">Dibatalkan</p>
                                <p class="text-xs text-gray-500">{{ $order->updated_at->format('d M Y, H:i') }}</p>
                                @if($order->cancellation_reason)
                                <p class="text-xs text-red-500 mt-1 bg-red-50 px-2 py-1 rounded">
                                    Alasan: {{ $order->cancellation_reason }}
                                </p>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="space-y-8">
                
                <div class="profile-card p-8">
                    <h3 class="text-lg font-bold text-[#1A1A1D] mb-4 flex items-center gap-2 uppercase tracking-wide">
                        <i class="fas fa-map-marker-alt"></i> Alamat Pengiriman
                    </h3>
                    @if($order->shippingAddress)
                    <div class="text-sm">
                        <p class="font-bold text-gray-900 mb-1">{{ $order->shippingAddress->recipient_name }}</p>
                        <p class="text-gray-500 mb-3">{{ $order->shippingAddress->phone }}</p>
                        <p class="text-gray-600 leading-relaxed bg-gray-50 p-3 rounded-xl border border-gray-100">
                            {{ $order->shippingAddress->full_address }}<br>
                            {{ $order->shippingAddress->city_name }}, {{ $order->shippingAddress->province_name }} {{ $order->shippingAddress->postal_code }}
                        </p>
                    </div>
                    @else
                    <p class="text-gray-400 text-sm italic">Alamat tidak tersedia</p>
                    @endif
                </div>

                <div class="profile-card p-8">
                    <h3 class="text-lg font-bold text-[#1A1A1D] mb-6 flex items-center gap-2 uppercase tracking-wide">
                        <i class="fas fa-receipt"></i> Ringkasan Pembayaran
                    </h3>
                    
                    <div class="space-y-2">
                        <div class="info-row">
                            <span class="text-gray-500">Subtotal</span>
                            <span class="font-medium text-gray-900">Rp {{ number_format($order->subtotal ?? $order->orderItems->sum(fn($i) => $i->price * $i->quantity), 0, ',', '.') }}</span>
                        </div>
                        <div class="info-row">
                            <span class="text-gray-500">Biaya Pengiriman</span>
                            <span class="font-medium text-gray-900">Rp {{ number_format($order->shipping_cost ?? 0, 0, ',', '.') }}</span>
                        </div>
                        @if($order->points_used > 0)
                        <div class="info-row text-red-600 bg-red-50 px-2 rounded-lg -mx-2">
                            <span class="text-xs font-bold uppercase py-1">Poin Digunakan</span>
                            <span class="font-bold py-1">- Rp {{ number_format($order->points_used, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        
                        <div class="pt-4 mt-2 border-t border-dashed border-gray-200">
                            <div class="flex justify-between items-center">
                                <span class="font-bold text-[#1A1A1D] text-lg">TOTAL</span>
                                <span class="font-black text-2xl text-[#1A1A1D]">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    @if($order->pointTransactions && $order->pointTransactions->where('type', 'earned')->first())
                    <div class="mt-6 p-4 bg-[#1A1A1D] rounded-xl text-white text-center">
                        <p class="text-xs text-gray-400 uppercase tracking-widest mb-1">Poin Diperoleh</p>
                        <p class="text-xl font-bold flex items-center justify-center gap-2">
                            <i class="fas fa-star text-yellow-400"></i>
                            +{{ $order->pointTransactions->where('type', 'earned')->first()->points }} PTS
                        </p>
                    </div>
                    @endif
                </div>

                <a href="{{ route('customer.orders') }}" 
                   class="flex items-center justify-center w-full py-4 bg-white border border-gray-200 rounded-2xl font-bold text-gray-600 hover:bg-[#1A1A1D] hover:text-white hover:border-[#1A1A1D] transition-all shadow-sm">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Pesanan
                </a>
            </div>
        </div>
    </div>
</div>
@endsection