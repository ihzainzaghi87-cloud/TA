@extends('customer.layouts.app')

@section('title', 'Lacak Pesanan #' . $order->order_number)

@push('styles')
<style>
    .profile-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        transition: all 0.3s ease;
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
        background: #10b981;
        box-shadow: 0 0 0 2px #10b981;
    }

    .tracking-item.latest .tracking-dot {
        background: #FAD470;
        box-shadow: 0 0 0 2px #FAD470;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(250, 212, 112, 0.7);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(250, 212, 112, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(250, 212, 112, 0);
        }
    }

    .status-badge {
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
    }

    .status-shipped {
        background: #e0e7ff;
        color: #3730a3;
    }

    .status-delivered {
        background: #dcfce7;
        color: #166534;
    }

    .info-card {
        background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
        border: 1px solid #fcd34d;
        border-radius: 16px;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="flex items-center mb-8 text-sm">
            <a href="{{ route('home') }}" class="text-gray-500 hover:text-gray-600 transition-colors">
                <i class="fas fa-home"></i>
            </a>
            <i class="fas fa-chevron-right text-gray-300 mx-3 text-xs"></i>
            <a href="{{ route('customer.orders') }}" class="text-gray-500 hover:text-gray-600 transition-colors">Pesanan</a>
            <i class="fas fa-chevron-right text-gray-300 mx-3 text-xs"></i>
            <a href="{{ route('customer.order-detail', $order->id) }}" class="text-gray-500 hover:text-gray-600 transition-colors">#{{ $order->order_number }}</a>
            <i class="fas fa-chevron-right text-gray-300 mx-3 text-xs"></i>
            <span class="text-gray-900 font-medium">Lacak Pengiriman</span>
        </nav>

        <!-- Flash Messages -->
        @if(session('error'))
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg flex items-center">
            <i class="fas fa-exclamation-circle mr-2"></i>
            {{ session('error') }}
        </div>
        @endif

        <!-- Order & Tracking Info Header -->
        <div class="profile-card p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">
                        <i class="fas fa-truck text-gray-500 mr-2"></i>
                        Lacak Pesanan
                    </h1>
                    <p class="text-gray-500">Pesanan #{{ $order->order_number }}</p>
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
        <div class="info-card p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="w-14 h-14 mx-auto bg-gray-400 rounded-full flex items-center justify-center mb-3">
                        <i class="fas fa-box text-white text-xl"></i>
                    </div>
                    <p class="text-sm text-gray-800 font-medium">Kurir</p>
                    <p class="text-lg font-bold text-gray-900">{{ strtoupper($order->courier ?? '-') }}</p>
                    <p class="text-sm text-gray-700">{{ $order->courier_service ?? '' }}</p>
                </div>
                <div class="text-center">
                    <div class="w-14 h-14 mx-auto bg-gray-400 rounded-full flex items-center justify-center mb-3">
                        <i class="fas fa-barcode text-white text-xl"></i>
                    </div>
                    <p class="text-sm text-gray-800 font-medium">Nomor Resi</p>
                    <p class="text-lg font-bold text-gray-900 break-all">{{ $order->tracking_number ?? '-' }}</p>
                </div>
                <div class="text-center">
                    <div class="w-14 h-14 mx-auto bg-gray-400 rounded-full flex items-center justify-center mb-3">
                        <i class="fas fa-map-marker-alt text-white text-xl"></i>
                    </div>
                    <p class="text-sm text-gray-800 font-medium">Tujuan</p>
                    <p class="text-lg font-bold text-gray-900">
                        {{ $order->shippingAddress->city_name ?? 'Kota' }}
                    </p>
                    <p class="text-sm text-gray-700">{{ $order->shippingAddress->province_name ?? '' }}</p>
                </div>
            </div>
        </div>

        <!-- Tracking Timeline -->
        <div class="profile-card p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                <i class="fas fa-route text-gray-500"></i>
                Riwayat Pengiriman
            </h3>

            @if($trackingData && isset($trackingData['manifest']) && count($trackingData['manifest']) > 0)
            <div class="tracking-timeline">
                @foreach($trackingData['manifest'] as $index => $manifest)
                <div class="tracking-item {{ $index == 0 ? 'latest' : '' }}">
                    <div class="tracking-dot"></div>
                    <div>
                        <p class="font-semibold text-gray-900">{{ $manifest['manifest_description'] ?? 'Update' }}</p>
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
                <div class="w-20 h-20 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-search text-gray-400 text-3xl"></i>
                </div>
                <h4 class="text-gray-900 font-semibold mb-2">Informasi Tracking Tidak Tersedia</h4>
                <p class="text-gray-500 text-sm mb-4">
                    Data tracking belum tersedia atau tidak dapat diambil saat ini.
                </p>
                <p class="text-gray-500 text-sm">
                    Silakan coba beberapa saat lagi atau lacak langsung di website kurir.
                </p>
                
                @if($order->tracking_number)
                <div class="mt-6 flex flex-wrap justify-center gap-3">
                    @if(strtolower($order->courier) == 'jne')
                    <a href="https://www.jne.co.id/id/tracking/trace/{{ $order->tracking_number }}" 
                       target="_blank"
                       class="inline-flex items-center px-4 py-2 bg-red-500 text-white rounded-lg text-sm font-semibold hover:bg-red-600 transition-all">
                        <i class="fas fa-external-link-alt mr-2"></i>
                        Lacak di JNE
                    </a>
                    @elseif(strtolower($order->courier) == 'jnt' || strtolower($order->courier) == 'j&t')
                    <a href="https://www.jet.co.id/track/{{ $order->tracking_number }}" 
                       target="_blank"
                       class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-semibold hover:bg-red-700 transition-all">
                        <i class="fas fa-external-link-alt mr-2"></i>
                        Lacak di J&T
                    </a>
                    @elseif(strtolower($order->courier) == 'sicepat')
                    <a href="https://www.sicepat.com/checkAwb/{{ $order->tracking_number }}" 
                       target="_blank"
                       class="inline-flex items-center px-4 py-2 bg-orange-500 text-white rounded-lg text-sm font-semibold hover:bg-orange-600 transition-all">
                        <i class="fas fa-external-link-alt mr-2"></i>
                        Lacak di SiCepat
                    </a>
                    @elseif(strtolower($order->courier) == 'pos')
                    <a href="https://www.posindonesia.co.id/id/tracking/{{ $order->tracking_number }}" 
                       target="_blank"
                       class="inline-flex items-center px-4 py-2 bg-orange-600 text-white rounded-lg text-sm font-semibold hover:bg-orange-700 transition-all">
                        <i class="fas fa-external-link-alt mr-2"></i>
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
        <div class="profile-card p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fas fa-info-circle text-gray-500"></i>
                Ringkasan Pengiriman
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @if(isset($trackingData['summary']['origin']))
                <div class="p-4 bg-gray-50 rounded-xl">
                    <p class="text-sm text-gray-500">Asal</p>
                    <p class="font-semibold text-gray-900">{{ $trackingData['summary']['origin'] }}</p>
                </div>
                @endif
                @if(isset($trackingData['summary']['destination']))
                <div class="p-4 bg-gray-50 rounded-xl">
                    <p class="text-sm text-gray-500">Tujuan</p>
                    <p class="font-semibold text-gray-900">{{ $trackingData['summary']['destination'] }}</p>
                </div>
                @endif
                @if(isset($trackingData['summary']['shipper']))
                <div class="p-4 bg-gray-50 rounded-xl">
                    <p class="text-sm text-gray-500">Pengirim</p>
                    <p class="font-semibold text-gray-900">{{ $trackingData['summary']['shipper'] }}</p>
                </div>
                @endif
                @if(isset($trackingData['summary']['receiver']))
                <div class="p-4 bg-gray-50 rounded-xl">
                    <p class="text-sm text-gray-500">Penerima</p>
                    <p class="font-semibold text-gray-900">{{ $trackingData['summary']['receiver'] }}</p>
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
        <div class="profile-card p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h4 class="font-bold text-gray-900">
                        @if($isDelivered)
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            Paket sudah diterima oleh {{ $trackingData['delivery_status']['pod_receiver'] ?? 'penerima' }}
                        @else
                            Sudah menerima pesanan?
                        @endif
                    </h4>
                    <p class="text-sm text-gray-500">
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
                            class="w-full md:w-auto px-6 py-3 bg-green-500 text-white rounded-xl font-semibold hover:bg-green-600 transition-all">
                        <i class="fas fa-check-circle mr-2"></i>
                        Pesanan Diterima
                    </button>
                </form>
            </div>
        </div>
        @endif

        <!-- Back Button -->
        <div class="flex gap-4">
            <a href="{{ route('customer.order-detail', $order->id) }}" 
               class="flex-1 text-center py-3 bg-white border border-gray-300 rounded-xl font-semibold text-gray-700 hover:border-gray-400 hover:text-gray-600 transition-all">
                <i class="fas fa-arrow-left mr-2"></i>
                Detail Pesanan
            </a>
            <a href="{{ route('customer.orders') }}" 
               class="flex-1 text-center py-3 bg-gradient-to-r from-gray-500 to-gray-500 text-white rounded-xl font-semibold hover:from-gray-600 hover:to-gray-600 transition-all">
                <i class="fas fa-list mr-2"></i>
                Semua Pesanan
            </a>
        </div>
    </div>
</div>
@endsection
