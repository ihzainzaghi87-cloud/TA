@extends('customer.layouts.app')

@section('title', 'Detail Pesanan #' . $order->order_number)

@push('styles')
<style>
    .profile-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        transition: all 0.3s ease;
    }

    .status-badge {
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
    }

    .status-pending {
        background: #fef3c7;
        color: #92400e;
    }

    .status-processing {
        background: #dbeafe;
        color: #1e40af;
    }

    .status-shipped {
        background: #e0e7ff;
        color: #3730a3;
    }

    .status-delivered {
        background: #dcfce7;
        color: #166534;
    }

    .status-cancelled {
        background: #fee2e2;
        color: #991b1b;
    }

    .timeline-item {
        position: relative;
        padding-left: 40px;
        padding-bottom: 24px;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: 11px;
        top: 28px;
        bottom: 0;
        width: 2px;
        background: #e5e7eb;
    }

    .timeline-item:last-child::before {
        display: none;
    }

    .timeline-dot {
        position: absolute;
        left: 0;
        top: 4px;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: #e5e7eb;
        border: 3px solid #fff;
        box-shadow: 0 0 0 2px #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .timeline-dot.active {
        background: #FAD470;
        box-shadow: 0 0 0 2px #FAD470;
        animation: pulse-ring 2s infinite;
    }

    .timeline-dot.completed {
        background: #10b981;
        box-shadow: 0 0 0 2px #10b981;
    }

    .timeline-dot.cancelled {
        background: #ef4444;
        box-shadow: 0 0 0 2px #ef4444;
    }

    @keyframes pulse-ring {
        0%, 100% {
            box-shadow: 0 0 0 2px #FAD470, 0 0 0 4px rgba(250, 212, 112, 0.3);
        }
        50% {
            box-shadow: 0 0 0 2px #FAD470, 0 0 0 8px rgba(250, 212, 112, 0.1);
        }
    }

    .product-thumb {
        width: 80px;
        height: 80px;
        border-radius: 12px;
        object-fit: cover;
        border: 1px solid #e5e7eb;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid #f3f4f6;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: .7;
        }
    }

    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="flex items-center mb-8 text-sm">
            <a href="{{ route('home') }}" class="text-gray-500 hover:text-amber-600 transition-colors">
                <i class="fas fa-home"></i>
            </a>
            <i class="fas fa-chevron-right text-gray-300 mx-3 text-xs"></i>
            <a href="{{ route('customer.index') }}" class="text-gray-500 hover:text-amber-600 transition-colors">Profil</a>
            <i class="fas fa-chevron-right text-gray-300 mx-3 text-xs"></i>
            <a href="{{ route('customer.orders') }}" class="text-gray-500 hover:text-amber-600 transition-colors">Pesanan</a>
            <i class="fas fa-chevron-right text-gray-300 mx-3 text-xs"></i>
            <span class="text-gray-900 font-medium">#{{ $order->order_number }}</span>
        </nav>

        <!-- Flash Messages -->
        @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg flex items-center">
            <i class="fas fa-exclamation-circle mr-2"></i>
            {{ session('error') }}
        </div>
        @endif

        <!-- Order Header -->
        <div class="profile-card p-6 mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">Pesanan #{{ $order->order_number }}</h1>
                    <p class="text-gray-500">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        {{ $order->created_at->format('d F Y, H:i') }}
                    </p>
                </div>
                <div class="flex flex-col sm:items-end gap-2">
                    <span class="status-badge status-{{ strtolower($order->status) }} inline-block">
                        @switch($order->status)
                            @case('Pending')
                                <i class="fas fa-clock mr-1"></i> Menunggu Pembayaran
                                @break
                            @case('Processing')
                                <i class="fas fa-cog mr-1"></i> Sedang Diproses
                                @break
                            @case('Shipped')
                                <i class="fas fa-truck mr-1"></i> Dalam Pengiriman
                                @break
                            @case('Delivered')
                                <i class="fas fa-check-circle mr-1"></i> Selesai
                                @break
                            @case('Cancelled')
                                <i class="fas fa-times-circle mr-1"></i> Dibatalkan
                                @break
                        @endswitch
                    </span>
                    
                    @if($order->status == 'Shipped')
                    <div class="flex gap-2">
                        <a href="{{ route('customer.track-order', $order->id) }}" 
                           class="px-4 py-2 bg-gradient-to-r from-amber-500 to-yellow-500 text-white rounded-lg text-sm font-semibold hover:from-amber-600 hover:to-yellow-600 transition-all">
                            <i class="fas fa-truck mr-1"></i> Lacak Pengiriman
                        </a>
                        
                        @php
                            // Check if delivered based on tracking data
                            $isDelivered = false;
                            if (isset($trackingData)) {
                                $isDelivered = ($trackingData['delivered'] ?? false) === true
                                            || (isset($trackingData['delivery_status']['status']) && $trackingData['delivery_status']['status'] === 'DELIVERED')
                                            || (isset($trackingData['detail']['status']) && $trackingData['detail']['status'] === 'DELIVERED');
                            }
                        @endphp
                        
                        <form action="{{ route('customer.confirm-received', $order->id) }}" method="POST" 
                              onsubmit="return confirm('Konfirmasi bahwa pesanan sudah diterima?')">
                            @csrf
                            <button type="submit" 
                                    class="px-4 py-2 {{ $isDelivered ? 'bg-green-600 animate-pulse' : 'bg-green-500' }} text-white rounded-lg text-sm font-semibold hover:bg-green-600 transition-all">
                                <i class="fas fa-check mr-1"></i> 
                                {{ $isDelivered ? 'Konfirmasi Diterima' : 'Pesanan Diterima' }}
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column - Order Items & Timeline -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Order Items -->
                <div class="profile-card p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-box text-amber-500"></i>
                        Produk Pesanan
                    </h3>
                    
                    <div class="space-y-4">
                        @foreach($order->orderItems as $item)
                        <div class="flex gap-4 p-4 bg-gray-50 rounded-xl">
                            @if($item->variation && $item->variation->product && $item->variation->product->images->first())
                            <img src="{{ asset('storage/products/' . $item->variation->product->images->first()->image) }}" 
                                 alt="{{ $item->variation->product->name }}"
                                 class="product-thumb">
                            @else
                            <div class="product-thumb bg-gray-200 flex items-center justify-center">
                                <i class="fas fa-image text-gray-400 text-2xl"></i>
                            </div>
                            @endif
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900">
                                    {{ $item->variation->product->name ?? 'Produk tidak tersedia' }}
                                </h4>
                                <p class="text-sm text-gray-500 mt-1">
                                    Variasi: {{ $item->variation->color ?? '-' }} / {{ $item->variation->size ?? '-' }}
                                </p>
                                <div class="flex items-center justify-between mt-2">
                                    <p class="text-sm text-gray-500">
                                        {{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}
                                    </p>
                                    <p class="font-bold text-gray-900">
                                        Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Order Timeline -->
                <div class="profile-card p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-history text-amber-500"></i>
                        Status Pesanan
                    </h3>
                    
                    <div class="space-y-0">
                        @php
                            $statusOrder = ['Pending', 'Processing', 'Shipped', 'Delivered'];
                            $currentStatusIndex = array_search($order->status, $statusOrder);
                            $isCancelled = $order->status === 'Cancelled';
                            
                            // Check tracking data for delivered status
                            $trackingDelivered = false;
                            if (isset($trackingData)) {
                                $trackingDelivered = ($trackingData['delivered'] ?? false) === true
                                                  || (isset($trackingData['delivery_status']['status']) && $trackingData['delivery_status']['status'] === 'DELIVERED');
                            }
                        @endphp

                        {{-- Pesanan Dibuat --}}
                        <div class="timeline-item">
                            <div class="timeline-dot completed">
                                <i class="fas fa-check text-white text-xs"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">Pesanan Dibuat</p>
                                <p class="text-sm text-gray-500">{{ $order->created_at->format('d M Y, H:i') }}</p>
                                @if($order->status === 'Pending')
                                <p class="text-xs text-amber-600 mt-1">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Menunggu konfirmasi pembayaran
                                </p>
                                @endif
                            </div>
                        </div>

                        {{-- Pembayaran Dikonfirmasi --}}
                        <div class="timeline-item">
                            <div class="timeline-dot {{ $currentStatusIndex >= 1 ? 'completed' : ($order->status === 'Pending' ? 'active' : '') }}">
                                @if($currentStatusIndex >= 1)
                                <i class="fas fa-check text-white text-xs"></i>
                                @elseif($order->status === 'Pending')
                                <i class="fas fa-circle text-white text-xs" style="font-size: 8px;"></i>
                                @endif
                            </div>
                            <div>
                                <p class="font-semibold {{ $currentStatusIndex >= 1 ? 'text-gray-900' : 'text-gray-400' }}">
                                    Pembayaran Dikonfirmasi
                                </p>
                                @if($currentStatusIndex >= 1)
                                <p class="text-sm text-gray-500">Pesanan sedang dikemas</p>
                                @elseif($order->status === 'Pending')
                                <p class="text-sm text-gray-400">Menunggu pembayaran</p>
                                @else
                                <p class="text-sm text-gray-400">Belum diproses</p>
                                @endif
                                
                                @if($order->status === 'Processing')
                                <p class="text-xs text-blue-600 mt-1">
                                    <i class="fas fa-cog fa-spin mr-1"></i>
                                    Sedang dalam proses pengemasan
                                </p>
                                @endif
                            </div>
                        </div>

                        {{-- Pesanan Dikirim --}}
                        <div class="timeline-item">
                            <div class="timeline-dot {{ $currentStatusIndex >= 2 ? 'completed' : ($order->status === 'Processing' ? 'active' : '') }}">
                                @if($currentStatusIndex >= 2)
                                <i class="fas fa-check text-white text-xs"></i>
                                @elseif($order->status === 'Processing')
                                <i class="fas fa-circle text-white text-xs" style="font-size: 8px;"></i>
                                @endif
                            </div>
                            <div>
                                <p class="font-semibold {{ $currentStatusIndex >= 2 ? 'text-gray-900' : 'text-gray-400' }}">
                                    Pesanan Dikirim
                                </p>
                                @if($order->tracking_number && $currentStatusIndex >= 2)
                                <p class="text-sm text-gray-500">
                                    No. Resi: <span class="font-mono font-semibold">{{ $order->tracking_number }}</span>
                                </p>
                                <p class="text-xs text-gray-500">
                                    Kurir: {{ strtoupper($order->courier ?? '-') }} - {{ $order->service ?? '-' }}
                                </p>
                                @else
                                <p class="text-sm text-gray-400">Menunggu pengiriman</p>
                                @endif
                                
                                @if($order->status === 'Shipped')
                                <div class="mt-2 flex items-center gap-2">
                                    <span class="text-xs px-2 py-1 bg-purple-100 text-purple-700 rounded-full">
                                        <i class="fas fa-shipping-fast mr-1"></i>
                                        Dalam pengiriman
                                    </span>
                                    @if($trackingDelivered)
                                    <span class="text-xs px-2 py-1 bg-green-100 text-green-700 rounded-full animate-pulse">
                                        <i class="fas fa-box-check mr-1"></i>
                                        Sudah diterima kurir
                                    </span>
                                    @endif
                                </div>
                                @endif
                            </div>
                        </div>

                        {{-- Pesanan Selesai --}}
                        <div class="timeline-item">
                            <div class="timeline-dot {{ $order->status === 'Delivered' ? 'completed' : ($order->status === 'Shipped' ? 'active' : '') }}">
                                @if($order->status === 'Delivered')
                                <i class="fas fa-check text-white text-xs"></i>
                                @elseif($order->status === 'Shipped')
                                <i class="fas fa-circle text-white text-xs" style="font-size: 8px;"></i>
                                @endif
                            </div>
                            <div>
                                <p class="font-semibold {{ $order->status === 'Delivered' ? 'text-gray-900' : 'text-gray-400' }}">
                                    Pesanan Selesai
                                </p>
                                @if($order->delivered_at)
                                <p class="text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($order->delivered_at)->format('d M Y, H:i') }}
                                </p>
                                <p class="text-xs text-green-600 mt-1">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Pesanan telah diterima
                                </p>
                                @else
                                <p class="text-sm text-gray-400">Menunggu konfirmasi penerimaan</p>
                                @endif
                            </div>
                        </div>

                        {{-- Pesanan Dibatalkan --}}
                        @if($isCancelled)
                        <div class="timeline-item">
                            <div class="timeline-dot cancelled">
                                <i class="fas fa-times text-white text-xs"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-red-600">Pesanan Dibatalkan</p>
                                <p class="text-sm text-gray-500">{{ $order->updated_at->format('d M Y, H:i') }}</p>
                                @if($order->cancellation_reason)
                                <p class="text-xs text-red-600 mt-1">
                                    Alasan: {{ $order->cancellation_reason }}
                                </p>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column - Order Summary -->
            <div class="space-y-6">
                <!-- Shipping Address -->
                <div class="profile-card p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-map-marker-alt text-amber-500"></i>
                        Alamat Pengiriman
                    </h3>
                    
                    @if($order->shippingAddress)
                    <div class="space-y-2">
                        <p class="font-semibold text-gray-900">{{ $order->shippingAddress->recipient_name }}</p>
                        <p class="text-sm text-gray-600">
                            <i class="fas fa-phone mr-1"></i>
                            {{ $order->shippingAddress->phone }}
                        </p>
                        <p class="text-sm text-gray-600">
                            {{ $order->shippingAddress->full_address }},
                            {{ $order->shippingAddress->city->name ?? '' }},
                            {{ $order->shippingAddress->province->name ?? '' }}
                            {{ $order->shippingAddress->postal_code }}
                        </p>
                    </div>
                    @else
                    <p class="text-gray-500 text-sm">Alamat tidak tersedia</p>
                    @endif
                </div>

                <!-- Shipping Info -->
                @if($order->courier)
                <div class="profile-card p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-truck text-amber-500"></i>
                        Informasi Pengiriman
                    </h3>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Kurir</span>
                            <span class="font-semibold text-gray-900">{{ strtoupper($order->courier) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Layanan</span>
                            <span class="font-semibold text-gray-900">{{ $order->service ?? '-' }}</span>
                        </div>
                        @if($order->tracking_number)
                        <div class="pt-2 border-t border-gray-100">
                            <p class="text-xs text-gray-500 mb-1">Nomor Resi:</p>
                            <p class="font-mono font-bold text-gray-900 text-sm">{{ $order->tracking_number }}</p>
                            <a href="{{ route('customer.track-order', $order->id) }}" 
                               class="mt-2 inline-block text-xs text-amber-600 hover:text-amber-700">
                                Lacak pengiriman →
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Order Summary -->
                <div class="profile-card p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-receipt text-amber-500"></i>
                        Ringkasan Pembayaran
                    </h3>
                    
                    <div class="space-y-3">
                        <div class="info-row">
                            <span class="text-gray-500">Subtotal ({{ $order->orderItems->sum('quantity') }} produk)</span>
                            <span class="text-gray-900">Rp {{ number_format($order->subtotal ?? $order->orderItems->sum(fn($i) => $i->price * $i->quantity), 0, ',', '.') }}</span>
                        </div>
                        <div class="info-row">
                            <span class="text-gray-500">Ongkos Kirim</span>
                            <span class="text-gray-900">Rp {{ number_format($order->shipping_cost ?? 0, 0, ',', '.') }}</span>
                        </div>
                        @if($order->points_used > 0)
                        <div class="info-row">
                            <span class="text-gray-500">Poin Digunakan</span>
                            <span class="text-green-600">- Rp {{ number_format($order->points_used, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        <div class="pt-3 border-t-2 border-gray-200">
                            <div class="flex justify-between">
                                <span class="font-bold text-gray-900">Total</span>
                                <span class="font-bold text-xl text-amber-600">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    @if($order->pointTransactions && $order->pointTransactions->where('type', 'earned')->first())
                    <div class="mt-4 p-3 bg-green-50 rounded-xl border border-green-200">
                        <p class="text-sm text-green-700">
                            <i class="fas fa-coins mr-1"></i>
                            Anda mendapat <strong>{{ $order->pointTransactions->where('type', 'earned')->first()->points }}</strong> poin dari pesanan ini!
                        </p>
                    </div>
                    @endif
                </div>

                <!-- Back Button -->
                <a href="{{ route('customer.orders') }}" 
                   class="block w-full text-center py-3 bg-white border-2 border-gray-300 rounded-xl font-semibold text-gray-700 hover:border-amber-400 hover:text-amber-600 hover:bg-amber-50 transition-all">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali ke Daftar Pesanan
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
