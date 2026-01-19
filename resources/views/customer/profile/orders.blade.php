@extends('customer.layouts.app')

@section('title', 'Pesanan Saya')

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

    .menu-item {
        display: flex;
        align-items: center;
        padding: 16px 20px;
        border-radius: 12px;
        transition: all 0.2s ease;
        color: #6b7280;
    }

    .menu-item:hover {
        background: #f9fafb;
        color: #1A1A1D;
    }

    .menu-item.active {
        background: #1A1A1D;
        color: #ffffff;
        font-weight: 600;
    }

    .avatar-ring {
        background: linear-gradient(135deg, #1A1A1D 0%, #374151 100%);
    }

    .stat-card {
        background: linear-gradient(135deg, #F9FAFB 0%, #F3F4F6 100%);
        border: 1px solid #E5E7EB;
        border-radius: 1rem;
        padding: 16px;
        text-align: center;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        border-color: #1A1A1D;
        transform: translateY(-2px);
    }

    .filter-btn {
        padding: 8px 16px;
        border-radius: 9999px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        transition: all 0.2s ease;
        border: 1px solid #e5e7eb;
        background: #fff;
        color: #6b7280;
    }

    .filter-btn:hover {
        border-color: #1A1A1D;
        background: #F9FAFB;
        color: #1A1A1D;
    }

    .filter-btn.active {
        background: #1A1A1D;
        border-color: #1A1A1D;
        color: #ffffff;
    }

    .order-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 1.5rem;
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .order-card:hover {
        border-color: #1A1A1D;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
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

    .status-pending {
        background: #f3f4f6;
        color: #4b5563;
        border-color: #d1d5db;
    }

    .status-processing {
        background: #dbeafe;
        color: #1e40af;
        border-color: #93c5fd;
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

    .status-cancelled {
        background: #fee2e2;
        color: #991b1b;
        border-color: #fca5a5;
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
<div class="min-h-screen bg-gray-50 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center mb-8 text-sm">
            <a href="{{ route('home') }}" class="text-gray-400 hover:text-black transition-colors">
                <i class="fas fa-home"></i>
            </a>
            <i class="fas fa-chevron-right text-gray-300 mx-3 text-xs"></i>
            <a href="{{ route('customer.index') }}" class="text-gray-400 hover:text-black transition-colors">Profile</a>
            <i class="fas fa-chevron-right text-gray-300 mx-3 text-xs"></i>
            <span class="text-[#1A1A1D] font-bold">Pesanan Saya</span>
        </nav>

        <!-- Flash Messages -->
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
                <div class="profile-card p-8">
                    <h1 class="text-3xl font-black text-[#1A1A1D] tracking-tight flex items-center gap-3">
                        <i class="fas fa-box"></i>
                        Pesanan Saya
                    </h1>
                    <p class="text-gray-500 mt-2 text-sm">Kelola dan lacak pesanan Anda</p>
                </div>

                <!-- Order Stats -->
                <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
                    <div class="stat-card">
                        <p class="text-2xl font-bold text-gray-900">{{ $orderStats['total'] }}</p>
                        <p class="text-xs text-gray-700">Total</p>
                    </div>
                    <div class="stat-card">
                        <p class="text-2xl font-bold text-gray-900">{{ $orderStats['pending'] }}</p>
                        <p class="text-xs text-gray-700">Pending</p>
                    </div>
                    <div class="stat-card">
                        <p class="text-2xl font-bold text-gray-900">{{ $orderStats['processing'] }}</p>
                        <p class="text-xs text-gray-700">Diproses</p>
                    </div>
                    <div class="stat-card">
                        <p class="text-2xl font-bold text-gray-900">{{ $orderStats['shipped'] }}</p>
                        <p class="text-xs text-gray-700">Dikirim</p>
                    </div>
                    <div class="stat-card">
                        <p class="text-2xl font-bold text-gray-900">{{ $orderStats['delivered'] }}</p>
                        <p class="text-xs text-gray-700">Selesai</p>
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
                        <div class="p-5 bg-gray-50 border-b border-gray-100">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <span class="text-sm text-gray-500"><i class="fas fa-calendar text-[#1A1A1D] mr-1"></i>{{ $order->created_at->format('d M Y, H:i') }}</span>
                                    <span class="text-gray-300">|</span>
                                    <span class="text-sm font-bold text-[#1A1A1D]">#{{ $order->order_number }}</span>
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
                        <div class="p-6">
                            @foreach($order->orderItems->take(2) as $item)
                            <div class="flex items-start gap-5 {{ !$loop->last ? 'mb-4 pb-4 border-b border-gray-100' : '' }}">
                                @if($item->variation && $item->variation->product && $item->variation->product->images->first())
                                <img src="{{ asset('storage/products/' . $item->variation->product->images->first()->image) }}" 
                                     alt="{{ $item->variation->product->name }}"
                                     class="product-thumb mix-blend-multiply">
                                @else
                                <div class="product-thumb bg-gray-100 flex items-center justify-center">
                                    <i class="fas fa-image text-gray-300 text-xl"></i>
                                </div>
                                @endif
                                
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-bold text-[#1A1A1D] text-base leading-tight">
                                        {{ $item->variation->product->name ?? 'Produk tidak tersedia' }}
                                    </h4>
                                    <p class="text-xs text-gray-500 mt-1 uppercase tracking-wide font-semibold">
                                        {{ $item->variation->color ?? '-' }} / {{ $item->variation->size ?? '-' }}
                                    </p>
                                    <div class="flex items-center justify-between mt-3">
                                        <span class="px-2 py-1 bg-gray-100 rounded text-xs font-medium text-gray-600">
                                            Qty: {{ $item->quantity }}
                                        </span>
                                        @if ($item->price > 0)
                                        <span class="font-bold text-[#1A1A1D]">
                                            Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                        </span>
                                        @else
                                        <span class="font-bold text-[#1A1A1D] flex items-center gap-1">
                                            <i class="fas fa-coins text-yellow-500"></i>
                                            {{ number_format($item->point_price * $item->quantity, 0, ',', '.') }} Poin
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach

                            @if($order->orderItems->count() > 2)
                            <p class="text-sm text-gray-500 mt-4 font-medium">
                                <i class="fas fa-plus-circle mr-1"></i>{{ $order->orderItems->count() - 2 }} produk lainnya
                            </p>
                            @endif
                        </div>

                        <!-- Order Footer -->
                        <div class="p-5 bg-gray-50 border-t border-gray-100">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-1">Total Pesanan</p>
                                    <p class="text-2xl font-black text-[#1A1A1D]">
                                        Rp {{ number_format($order->total, 0, ',', '.') }}
                                    </p>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ route('customer.order-detail', $order->id) }}" 
                                       class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-700 hover:border-[#1A1A1D] hover:text-[#1A1A1D] transition-all text-sm font-bold shadow-sm">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                    
                                    @if(in_array($order->status, ['Shipped', 'Delivered']))
                                    <a href="{{ route('customer.track-order', $order->id) }}" 
                                       class="px-5 py-2.5 bg-[#1A1A1D] text-white rounded-xl text-sm font-bold hover:bg-gray-800 transition-all shadow-lg flex items-center gap-2">
                                        <i class="fas fa-search-location"></i> Lacak
                                    </a>

                                    {{-- Confirm Receipt Button if Delivered --}}
                                    @if($isDelivered)
                                    <form action="{{ route('customer.confirm-received', $order->id) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Konfirmasi bahwa pesanan sudah diterima?')"
                                          class="inline-block">
                                        @csrf
                                        <button type="submit" 
                                                class="px-5 py-2.5 bg-green-600 text-white rounded-xl text-sm font-bold hover:bg-green-700 transition-all shadow-md flex items-center gap-2 animate-pulse">
                                            <i class="fas fa-check-double"></i> Konfirmasi
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
                    <div class="w-24 h-24 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-6">
                        <i class="fas fa-box-open text-gray-400 text-4xl"></i>
                    </div>
                    <h4 class="text-2xl font-black text-[#1A1A1D] mb-3 tracking-tight">Belum Ada Pesanan</h4>
                    <p class="text-gray-500 mb-8 text-sm">
                        @if($status)
                            Tidak ada pesanan dengan status "{{ ucfirst($status) }}"
                        @else
                            Anda belum memiliki pesanan. Mulai belanja sekarang!
                        @endif
                    </p>
                    @if($status)
                    <a href="{{ route('customer.orders') }}" 
                       class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-white border border-gray-300 rounded-xl text-gray-700 hover:border-[#1A1A1D] hover:text-[#1A1A1D] transition-all font-bold shadow-sm">
                        <i class="fas fa-arrow-left"></i>
                        Lihat Semua Pesanan
                    </a>
                    @else
                    <a href="{{ route('home') }}" 
                       class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-[#1A1A1D] text-white rounded-2xl font-bold hover:bg-gray-800 transition-all shadow-lg">
                        <i class="fas fa-shopping-bag"></i>
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
