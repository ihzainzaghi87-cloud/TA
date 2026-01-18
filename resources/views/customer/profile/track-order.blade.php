@extends('customer.layouts.app')

@section('title', 'Lacak Pesanan #' . $order->order_number)

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

    .tracking-timeline {
        position: relative;
        padding-left: 30px;
    }

    .tracking-timeline::before {
        content: '';
        position: absolute;
        left: 10px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e5e7eb;
    }

    .tracking-item {
        position: relative;
        padding-bottom: 24px;
    }

    .tracking-item:last-child {
        padding-bottom: 0;
    }

    .tracking-dot {
        position: absolute;
        left: -26px;
        top: 4px;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background: #e5e7eb;
        border: 3px solid #fff;
        box-shadow: 0 0 0 2px #e5e7eb;
    }

    .tracking-item:first-child .tracking-dot {
        background: #1A1A1D;
        box-shadow: 0 0 0 2px #1A1A1D;
    }

    .tracking-item.latest .tracking-dot {
        background: #1A1A1D;
        box-shadow: 0 0 0 2px #1A1A1D;
        animation: pulse-ring 2s infinite;
    }

    @keyframes pulse-ring {
        0% { box-shadow: 0 0 0 0 rgba(26, 26, 29, 0.4); }
        70% { box-shadow: 0 0 0 10px rgba(26, 26, 29, 0); }
        100% { box-shadow: 0 0 0 0 rgba(26, 26, 29, 0); }
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

    .status-shipped {
        background: #f3e8ff;
        color: #6b21a8;
        border-color: #d8b4fe;
    }

    .status-delivered {
        background: #1A1A1D;
        color: #ffffff;
        border-color: #1A1A1D;
    }

    .info-card {
        background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
        border: 1px solid #e5e7eb;
        border-radius: 1.5rem;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50 py-10">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="flex items-center mb-8 text-sm">
            <a href="{{ route('home') }}" class="text-gray-400 hover:text-black transition-colors">
                <i class="fas fa-home"></i>
            </a>
            <i class="fas fa-chevron-right text-gray-300 mx-3 text-xs"></i>
            <a href="{{ route('customer.orders') }}" class="text-gray-400 hover:text-black transition-colors">Pesanan</a>
            <i class="fas fa-chevron-right text-gray-300 mx-3 text-xs"></i>
            <a href="{{ route('customer.order-detail', $order->id) }}" class="text-gray-400 hover:text-black transition-colors">#{{ $order->order_number }}</a>
            <i class="fas fa-chevron-right text-gray-300 mx-3 text-xs"></i>
            <span class="text-[#1A1A1D] font-bold">Lacak Pengiriman</span>
        </nav>

        <!-- Flash Messages -->
        @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-600 px-6 py-4 rounded-2xl flex items-center">
            <i class="fas fa-exclamation-circle mr-3"></i>
            {{ session('error') }}
        </div>
        @endif

        <!-- Order & Tracking Info Header -->
        <div class="profile-card p-8 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-black text-[#1A1A1D] tracking-tight mb-2 flex items-center gap-2">
                        <i class="fas fa-truck"></i>
                        Lacak Pesanan
                    </h1>
                    <p class="text-gray-500 text-sm">Pesanan #{{ $order->order_number }}</p>
                </div>
                <span class="status-badge status-{{ $order->status }} inline-block">
                    @if($order->status == 'shipped')
                        <i class="fas fa-shipping-fast mr-1"></i> Dalam Pengiriman
                    @elseif($order->status == 'delivered')
                        <i class="fas fa-check-circle mr-1"></i> Terkirim
                    @endif
                </span>
            </div>
        </div>

        <!-- Shipping Details -->
        <div class="info-card p-8 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="w-16 h-16 mx-auto bg-[#1A1A1D] rounded-full flex items-center justify-center mb-3">
                        <i class="fas fa-box text-white text-xl"></i>
                    </div>
                    <p class="text-sm text-gray-500 font-semibold uppercase tracking-wide mb-1">Kurir</p>
                    <p class="text-lg font-black text-[#1A1A1D]">{{ strtoupper($order->courier ?? '-') }}</p>
                    <p class="text-sm text-gray-600">{{ $order->courier_service ?? '' }}</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 mx-auto bg-[#1A1A1D] rounded-full flex items-center justify-center mb-3">
                        <i class="fas fa-barcode text-white text-xl"></i>
                    </div>
                    <p class="text-sm text-gray-500 font-semibold uppercase tracking-wide mb-1">Nomor Resi</p>
                    <p class="text-base font-black text-[#1A1A1D] break-all">{{ $order->tracking_number ?? '-' }}</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 mx-auto bg-[#1A1A1D] rounded-full flex items-center justify-center mb-3">
                        <i class="fas fa-map-marker-alt text-white text-xl"></i>
                    </div>
                    <p class="text-sm text-gray-500 font-semibold uppercase tracking-wide mb-1">Tujuan</p>
                    <p class="text-lg font-black text-[#1A1A1D]">
                        {{ $order->shippingAddress->city_name ?? 'Kota' }}
                    </p>
                    <p class="text-sm text-gray-600">{{ $order->shippingAddress->province_name ?? '' }}</p>
                </div>
            </div>
        </div>

        <!-- Tracking Timeline -->
        <div class="profile-card p-8 mb-6">
            <h3 class="text-xl font-bold text-[#1A1A1D] mb-8 flex items-center gap-2 uppercase tracking-wide">
                <i class="fas fa-route"></i>
                Riwayat Pengiriman
            </h3>

            @if($trackingData && isset($trackingData['manifest']) && count($trackingData['manifest']) > 0)
            <div class="tracking-timeline">
                @foreach($trackingData['manifest'] as $index => $manifest)
                <div class="tracking-item {{ $index == 0 ? 'latest' : '' }}">
                    <div class="tracking-dot"></div>
                    <div>
                        <p class="font-bold text-[#1A1A1D]">{{ $manifest['manifest_description'] ?? 'Update' }}</p>
                        <p class="text-sm text-gray-500 mt-1">
                            <i class="fas fa-clock mr-1"></i>
                            {{ $manifest['manifest_date'] ?? '' }} {{ $manifest['manifest_time'] ?? '' }}
                        </p>
                        @if(isset($manifest['city_name']))
                        <p class="text-sm text-gray-500">
                            <i class="fas fa-map-marker-alt mr-1"></i>
                            {{ $manifest['city_name'] }}
                        </p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-12">
                <div class="w-20 h-20 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-search text-gray-400 text-3xl"></i>
                </div>
                <h4 class="text-xl font-black text-[#1A1A1D] mb-3 tracking-tight">Informasi Tracking Tidak Tersedia</h4>
                <p class="text-gray-500 text-sm mb-2">
                    Data tracking belum tersedia atau tidak dapat diambil saat ini.
                </p>
                <p class="text-gray-500 text-sm mb-6">
                    Silakan coba beberapa saat lagi atau lacak langsung di website kurir.
                </p>
                
                @if($order->tracking_number)
                <div class="mt-6 flex flex-wrap justify-center gap-3">
                    @if(strtolower($order->courier) == 'jne')
                    <a href="https://www.jne.co.id/id/tracking/trace/{{ $order->tracking_number }}" 
                       target="_blank"
                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-500 text-white rounded-xl text-sm font-bold hover:bg-red-600 transition-all shadow-lg">
                        <i class="fas fa-external-link-alt"></i>
                        Lacak di JNE
                    </a>
                    @elseif(strtolower($order->courier) == 'jnt' || strtolower($order->courier) == 'j&t')
                    <a href="https://www.jet.co.id/track/{{ $order->tracking_number }}" 
                       target="_blank"
                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-600 text-white rounded-xl text-sm font-bold hover:bg-red-700 transition-all shadow-lg">
                        <i class="fas fa-external-link-alt"></i>
                        Lacak di J&T
                    </a>
                    @elseif(strtolower($order->courier) == 'sicepat')
                    <a href="https://www.sicepat.com/checkAwb/{{ $order->tracking_number }}" 
                       target="_blank"
                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-orange-500 text-white rounded-xl text-sm font-bold hover:bg-orange-600 transition-all shadow-lg">
                        <i class="fas fa-external-link-alt"></i>
                        Lacak di SiCepat
                    </a>
                    @elseif(strtolower($order->courier) == 'pos')
                    <a href="https://www.posindonesia.co.id/id/tracking/{{ $order->tracking_number }}" 
                       target="_blank"
                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-orange-600 text-white rounded-xl text-sm font-bold hover:bg-orange-700 transition-all shadow-lg">
                        <i class="fas fa-external-link-alt"></i>
                        Lacak di Pos Indonesia
                    </a>
                    @endif
                </div>
                @endif
            </div>
            @endif
        </div>

        <!-- Delivery Summary -->
        @if($trackingData && isset($trackingData['summary']))
        <div class="profile-card p-8 mb-6">
            <h3 class="text-xl font-bold text-[#1A1A1D] mb-6 flex items-center gap-2 uppercase tracking-wide">
                <i class="fas fa-info-circle"></i>
                Ringkasan Pengiriman
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @if(isset($trackingData['summary']['origin']))
                <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                    <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-1">Asal</p>
                    <p class="font-bold text-[#1A1A1D]">{{ $trackingData['summary']['origin'] }}</p>
                </div>
                @endif
                @if(isset($trackingData['summary']['destination']))
                <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                    <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-1">Tujuan</p>
                    <p class="font-bold text-[#1A1A1D]">{{ $trackingData['summary']['destination'] }}</p>
                </div>
                @endif
                @if(isset($trackingData['summary']['shipper']))
                <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                    <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-1">Pengirim</p>
                    <p class="font-bold text-[#1A1A1D]">{{ $trackingData['summary']['shipper'] }}</p>
                </div>
                @endif
                @if(isset($trackingData['summary']['receiver']))
                <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                    <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-1">Penerima</p>
                    <p class="font-bold text-[#1A1A1D]">{{ $trackingData['summary']['receiver'] }}</p>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Confirm Received Button -->
        @php
            // Check if delivered based on tracking data OR order status
            $isDelivered = false;
            
            if ($trackingData) {
                // Check multiple indicators of delivery
                $isDelivered = ($trackingData['delivered'] ?? false) === true
                            || (isset($trackingData['delivery_status']['status']) && $trackingData['delivery_status']['status'] === 'DELIVERED')
                            || (isset($trackingData['detail']['status']) && $trackingData['detail']['status'] === 'DELIVERED');
            }
            
            // Show button if order is shipped OR tracking shows delivered, but NOT if order status is already delivered
            $showConfirmButton = (($order->status == 'Shipped') || ($isDelivered && $order->status != 'Delivered')) && $order->status != 'Delivered';
        @endphp

        @if($showConfirmButton)
        <div class="profile-card p-8 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h4 class="font-black text-[#1A1A1D] text-lg">
                        @if($isDelivered)
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            Paket sudah diterima oleh {{ $trackingData['delivery_status']['pod_receiver'] ?? 'penerima' }}
                        @else
                            Sudah menerima pesanan?
                        @endif
                    </h4>
                    <p class="text-sm text-gray-500 mt-1">
                        @if($isDelivered && isset($trackingData['delivery_status']['pod_date']))
                            Diterima pada {{ $trackingData['delivery_status']['pod_date'] }} {{ $trackingData['delivery_status']['pod_time'] ?? '' }}
                            <br>Konfirmasi untuk menyelesaikan pesanan
                        @else
                            Konfirmasi penerimaan untuk menyelesaikan pesanan
                        @endif
                    </p>
                </div>
                <form action="{{ route('customer.confirm-received', $order->id) }}" method="POST" 
                    onsubmit="return confirm('Konfirmasi bahwa pesanan sudah diterima?')">
                    @csrf
                    <button type="submit" 
                            class="w-full md:w-auto px-6 py-3 bg-green-600 text-white rounded-xl font-bold hover:bg-green-700 transition-all shadow-lg flex items-center gap-2">
                        <i class="fas fa-check-double"></i>
                        Pesanan Diterima
                    </button>
                </form>
            </div>
        </div>
        @endif

        <!-- Back Button -->
        <div class="flex gap-4">
            <a href="{{ route('customer.order-detail', $order->id) }}" 
               class="flex-1 text-center py-4 bg-white border border-gray-300 rounded-2xl font-bold text-gray-700 hover:border-[#1A1A1D] hover:text-[#1A1A1D] transition-all shadow-sm">
                <i class="fas fa-arrow-left mr-2"></i>
                Detail Pesanan
            </a>
            <a href="{{ route('customer.orders') }}" 
               class="flex-1 text-center py-4 bg-[#1A1A1D] text-white rounded-2xl font-bold hover:bg-gray-800 transition-all shadow-lg">
                <i class="fas fa-list mr-2"></i>
                Semua Pesanan
            </a>
        </div>
    </div>
</div>
@endsection
