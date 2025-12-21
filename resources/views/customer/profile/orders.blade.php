@extends('customer.layouts.app')

@section('title', 'Pesanan Saya')

@push('styles')
<style>
    .profile-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        transition: all 0.3s ease;
    }

    .profile-card:hover {
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.1);
    }

    .menu-item {
        display: flex;
        align-items: center;
        padding: 16px 20px;
        border-radius: 12px;
        transition: all 0.2s ease;
        color: #374151;
    }

    .menu-item:hover {
        background: #fffbeb;
        color: #92400e;
    }

    .menu-item.active {
        background: #FAD470;
        color: #92400e;
        font-weight: 600;
    }

    .avatar-ring {
        background: linear-gradient(135deg, #FAD470 0%, #F8B500 100%);
    }

    .stat-card {
        background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
        border: 1px solid #fcd34d;
        border-radius: 12px;
        padding: 16px;
        text-align: center;
    }

    .filter-btn {
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.2s ease;
        border: 2px solid #e5e7eb;
        background: #fff;
        color: #374151;
    }

    .filter-btn:hover {
        border-color: #FAD470;
        background: #fffbeb;
    }

    .filter-btn.active {
        background: #FAD470;
        border-color: #FAD470;
        color: #92400e;
    }

    .order-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .order-card:hover {
        border-color: #FAD470;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
    }

    .status-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
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

    .product-thumb {
        width: 60px;
        height: 60px;
        border-radius: 8px;
        object-fit: cover;
        border: 1px solid #e5e7eb;
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
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="flex items-center mb-8 text-sm">
            <a href="{{ route('home') }}" class="text-gray-500 hover:text-amber-600 transition-colors">
                <i class="fas fa-home"></i>
            </a>
            <i class="fas fa-chevron-right text-gray-300 mx-3 text-xs"></i>
            <a href="{{ route('customer.index') }}" class="text-gray-500 hover:text-amber-600 transition-colors">Profil</a>
            <i class="fas fa-chevron-right text-gray-300 mx-3 text-xs"></i>
            <span class="text-gray-900 font-medium">Pesanan Saya</span>
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

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Sidebar Menu -->
            <div class="lg:col-span-1">
                <div class="profile-card p-6">
                    <!-- User Avatar & Info -->
                    <div class="text-center mb-6">
                        <div class="w-24 h-24 mx-auto avatar-ring rounded-full flex items-center justify-center text-white text-3xl font-bold mb-4">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">{{ Auth::user()->name }}</h2>
                        <p class="text-gray-500 text-sm">{{ Auth::user()->email }}</p>
                    </div>

                    <!-- Menu Navigation -->
                    <nav class="space-y-2">
                        <a href="{{ route('customer.index') }}" class="menu-item">
                            <i class="fas fa-user w-5 mr-3"></i>
                            <span>Profil Saya</span>
                        </a>
                        <a href="{{ route('customer.points') }}" class="menu-item">
                            <i class="fas fa-coins w-5 mr-3"></i>
                            <span>Poin Saya</span>
                        </a>
                        <a href="{{ route('customer.orders') }}" class="menu-item active">
                            <i class="fas fa-box w-5 mr-3"></i>
                            <span>Pesanan Saya</span>
                        </a>
                        <a href="{{ route('addresses.index') }}" class="menu-item">
                            <i class="fas fa-map-marker-alt w-5 mr-3"></i>
                            <span>Alamat</span>
                        </a>
                        <a href="{{ route('customer.change-password') }}" class="menu-item">
                            <i class="fas fa-lock w-5 mr-3"></i>
                            <span>Ubah Password</span>
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-3 space-y-6">
                <!-- Page Title -->
                <div class="profile-card p-6">
                    <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                        <i class="fas fa-box text-amber-500"></i>
                        Pesanan Saya
                    </h1>
                    <p class="text-gray-500 mt-1">Kelola dan lacak pesanan Anda</p>
                </div>

                <!-- Order Stats -->
                <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
                    <div class="stat-card">
                        <p class="text-2xl font-bold text-amber-900">{{ $orderStats['total'] }}</p>
                        <p class="text-xs text-amber-700">Total</p>
                    </div>
                    <div class="stat-card">
                        <p class="text-2xl font-bold text-amber-900">{{ $orderStats['pending'] }}</p>
                        <p class="text-xs text-amber-700">Pending</p>
                    </div>
                    <div class="stat-card">
                        <p class="text-2xl font-bold text-amber-900">{{ $orderStats['processing'] }}</p>
                        <p class="text-xs text-amber-700">Diproses</p>
                    </div>
                    <div class="stat-card">
                        <p class="text-2xl font-bold text-amber-900">{{ $orderStats['shipped'] }}</p>
                        <p class="text-xs text-amber-700">Dikirim</p>
                    </div>
                    <div class="stat-card">
                        <p class="text-2xl font-bold text-amber-900">{{ $orderStats['delivered'] }}</p>
                        <p class="text-xs text-amber-700">Selesai</p>
                    </div>
                </div>

                <!-- Filter Buttons -->
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('customer.orders') }}" 
                       class="filter-btn {{ !$status ? 'active' : '' }}">
                        Semua
                    </a>
                    <a href="{{ route('customer.orders', ['status' => 'Pending']) }}" 
                       class="filter-btn {{ $status == 'Pending' ? 'active' : '' }}">
                        Pending
                    </a>
                    <a href="{{ route('customer.orders', ['status' => 'Processing']) }}" 
                       class="filter-btn {{ $status == 'Processing' ? 'active' : '' }}">
                        Diproses
                    </a>
                    <a href="{{ route('customer.orders', ['status' => 'Shipped']) }}" 
                       class="filter-btn {{ $status == 'Shipped' ? 'active' : '' }}">
                        Dikirim
                    </a>
                    <a href="{{ route('customer.orders', ['status' => 'Delivered']) }}" 
                       class="filter-btn {{ $status == 'Delivered' ? 'active' : '' }}">
                        Selesai
                    </a>
                    <a href="{{ route('customer.orders', ['status' => 'Cancelled']) }}" 
                       class="filter-btn {{ $status == 'Cancelled' ? 'active' : '' }}">
                        Dibatalkan
                    </a>
                </div>

                <!-- Orders List -->
                @if($orders->count() > 0)
                <div class="space-y-4">
                    @foreach($orders as $order)
                    @php
                        // Check delivery status from tracking data
                        $isDelivered = false;
                        if (isset($order->trackingData)) {
                            $isDelivered = ($order->trackingData['delivered'] ?? false) === true
                                        || (isset($order->trackingData['delivery_status']['status']) && $order->trackingData['delivery_status']['status'] === 'DELIVERED')
                                        || (isset($order->trackingData['detail']['status']) && $order->trackingData['detail']['status'] === 'DELIVERED');
                        }
                    @endphp

                    <div class="order-card">
                        <!-- Order Header -->
                        <div class="p-4 bg-gray-50 border-b border-gray-100">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                <div class="flex items-center gap-3">
                                    <span class="text-sm text-gray-500">{{ $order->created_at->format('d M Y, H:i') }}</span>
                                    <span class="text-gray-300">|</span>
                                    <span class="text-sm font-semibold text-gray-900">#{{ $order->order_number }}</span>
                                </div>
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="status-badge status-{{ strtolower($order->status) }} inline-block">
                                        @switch(strtolower($order->status))
                                            @case('pending')
                                                <i class="fas fa-clock mr-1"></i> Menunggu Pembayaran
                                                @break
                                            @case('processing')
                                                <i class="fas fa-cog mr-1"></i> Diproses
                                                @break
                                            @case('shipped')
                                                <i class="fas fa-shipping-fast mr-1"></i> Dikirim
                                                @break
                                            @case('delivered')
                                                <i class="fas fa-check-circle mr-1"></i> Selesai
                                                @break
                                            @case('cancelled')
                                                <i class="fas fa-times-circle mr-1"></i> Dibatalkan
                                                @break
                                            @default
                                                {{ $order->status }}
                                        @endswitch
                                    </span>

                                    {{-- Delivery Status Badge --}}
                                    @if($order->status == 'Shipped' && $isDelivered)
                                    <span class="status-badge bg-green-100 text-green-700 animate-pulse">
                                        <i class="fas fa-box-check mr-1"></i> Sudah Diterima
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Order Items -->
                        <div class="p-4">
                            @foreach($order->orderItems->take(2) as $item)
                            <div class="flex items-center gap-4 {{ !$loop->last ? 'mb-3 pb-3 border-b border-gray-100' : '' }}">
                                @if($item->variation && $item->variation->product && $item->variation->product->images->first())
                                <img src="{{ asset('storage/products/' . $item->variation->product->images->first()->image) }}" 
                                     alt="{{ $item->variation->product->name }}"
                                     class="product-thumb">
                                @else
                                <div class="product-thumb bg-gray-100 flex items-center justify-center">
                                    <i class="fas fa-image text-gray-400"></i>
                                </div>
                                @endif
                                
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-semibold text-gray-900 truncate">
                                        {{ $item->variation->product->name ?? 'Produk tidak tersedia' }}
                                    </h4>
                                    <p class="text-sm text-gray-500">
                                        {{ $item->variation->color ?? '' }} 
                                        {{ $item->variation->size ? '- ' . $item->variation->size : '' }}
                                    </p>
                                    <p class="text-sm text-gray-500">x{{ $item->quantity }}</p>
                                </div>
                                
                                <p class="font-semibold text-gray-900">
                                    Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                </p>
                            </div>
                            @endforeach

                            @if($order->orderItems->count() > 2)
                            <p class="text-sm text-gray-500 mt-3">
                                +{{ $order->orderItems->count() - 2 }} produk lainnya
                            </p>
                            @endif
                        </div>

                        <!-- Order Footer -->
                        <div class="p-4 bg-gray-50 border-t border-gray-100">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                <div>
                                    <p class="text-sm text-gray-500">Total Pesanan</p>
                                    <p class="text-lg font-bold text-amber-600">
                                        Rp {{ number_format($order->total, 0, ',', '.') }}
                                    </p>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ route('customer.order-detail', $order->id) }}" 
                                       class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-semibold text-gray-700 hover:border-amber-400 hover:text-amber-600 transition-all">
                                        <i class="fas fa-eye mr-1"></i> Detail
                                    </a>
                                    
                                    @if(in_array($order->status, ['Shipped', 'Delivered']))
                                    <a href="{{ route('customer.track-order', $order->id) }}" 
                                       class="px-4 py-2 bg-gradient-to-r from-amber-500 to-yellow-500 text-white rounded-lg text-sm font-semibold hover:from-amber-600 hover:to-yellow-600 transition-all">
                                        <i class="fas fa-truck mr-1"></i> Lacak
                                    </a>

                                    {{-- Confirm Receipt Button if Delivered --}}
                                    @if($isDelivered)
                                    <form action="{{ route('customer.confirm-received', $order->id) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Konfirmasi bahwa pesanan sudah diterima?')"
                                          class="inline-block">
                                        @csrf
                                        <button type="submit" 
                                                class="px-4 py-2 bg-green-500 text-white rounded-lg text-sm font-semibold hover:bg-green-600 transition-all animate-pulse">
                                            <i class="fas fa-check-circle mr-1"></i> Konfirmasi
                                        </button>
                                    </form>
                                    @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $orders->appends(['status' => $status])->links() }}
                </div>
                @else
                <!-- Empty State -->
                <div class="profile-card p-12 text-center">
                    <div class="w-24 h-24 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-box-open text-gray-400 text-4xl"></i>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-2">Belum Ada Pesanan</h4>
                    <p class="text-gray-500 mb-6">
                        @if($status)
                            Tidak ada pesanan dengan status "{{ ucfirst($status) }}"
                        @else
                            Anda belum memiliki pesanan. Mulai belanja sekarang!
                        @endif
                    </p>
                    @if($status)
                    <a href="{{ route('customer.orders') }}" 
                       class="inline-block text-amber-600 hover:text-amber-700 font-semibold">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Lihat Semua Pesanan
                    </a>
                    @else
                    <a href="{{ route('home') }}" 
                       class="inline-block bg-gradient-to-r from-amber-500 to-yellow-500 text-white px-8 py-3 rounded-xl font-bold hover:from-amber-600 hover:to-yellow-600 transition-all transform hover:scale-105">
                        <i class="fas fa-shopping-bag mr-2"></i>
                        Mulai Belanja
                    </a>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
