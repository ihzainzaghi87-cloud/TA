@extends('customer.layouts.app')

@section('title', 'Detail Alamat')

@push('styles')
<style>
    /* Card Styling - Sharp & Clean */
    .profile-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 1.5rem; /* 24px */
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
        background: linear-gradient(135deg, #1A1A1D 0%, #374151 100());
    }

    .info-row {
        display: flex;
        align-items: flex-start;
        padding: 16px;
        background: #f9fafb;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        transition: all 0.2s ease;
    }

    .info-row:hover {
        border-color: #1A1A1D;
        background: #ffffff;
    }

    .info-icon {
        width: 40px;
        height: 40px;
        background: #1A1A1D;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 16px;
        flex-shrink: 0;
    }

    .info-icon i {
        color: #ffffff;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="flex items-center mb-8 text-sm">
            <a href="{{ route('home') }}" class="text-gray-400 hover:text-black transition-colors">
                <i class="fas fa-home"></i>
            </a>
            <i class="fas fa-chevron-right text-gray-300 mx-3 text-xs"></i>
            <a href="{{ route('customer.index') }}" class="text-gray-400 hover:text-black transition-colors">
                Profile
            </a>
            <i class="fas fa-chevron-right text-gray-300 mx-3 text-xs"></i>
            <a href="{{ route('addresses.index') }}" class="text-gray-400 hover:text-black transition-colors">
                Alamat
            </a>
            <i class="fas fa-chevron-right text-gray-300 mx-3 text-xs"></i>
            <span class="text-[#1A1A1D] font-bold">Detail</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Sidebar Menu -->
            <div class="lg:col-span-1">
                <div class="profile-card p-6">
                    <!-- User Avatar & Info -->
                    <div class="text-center mb-6">
                        <div class="w-24 h-24 mx-auto avatar-ring rounded-full flex items-center justify-center text-white text-3xl font-bold mb-4">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">{{ auth()->user()->name }}</h2>
                        <p class="text-gray-500 text-sm">{{ auth()->user()->email }}</p>
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
                        <a href="{{ route('customer.orders') }}" class="menu-item">
                            <i class="fas fa-box w-5 mr-3"></i>
                            <span>Pesanan Saya</span>
                        </a>
                        <a href="{{ route('addresses.index') }}" class="menu-item active">
                            <i class="fas fa-map-marker-alt w-5 mr-3"></i>
                            <span>Alamat</span>
                        </a>
                        <a href="{{ route('customer.change-password') }}" class="menu-item">
                            <i class="fas fa-lock w-5 mr-3"></i>
                            <span>Ubah Password</span>
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="mt-4">
                            @csrf
                            <button type="submit" class="menu-item w-full text-red-600 hover:bg-red-50 hover:text-red-700">
                                <i class="fas fa-sign-out-alt w-5 mr-3"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-3">
                <div class="profile-card p-6 md:p-8">
                    <!-- Header -->
                    <div class="flex items-center gap-4 mb-8">
                        <a href="{{ route('addresses.index') }}" 
                           class="w-10 h-10 bg-gray-100 hover:bg-[#1A1A1D] rounded-xl flex items-center justify-center text-gray-600 hover:text-white transition-all shadow-sm">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <div class="flex-1">
                            <h1 class="text-2xl font-black text-[#1A1A1D] tracking-tight uppercase">Detail Alamat</h1>
                            <p class="text-gray-500 text-sm">{{ $address->label ?? 'Alamat' }}</p>
                        </div>
                        @if($address->is_primary)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-[#1A1A1D] text-white uppercase tracking-wide border border-[#1A1A1D]">
                                <i class="fas fa-star mr-1"></i> Alamat Utama
                            </span>
                        @endif
                    </div>

                    <!-- Address Details -->
                    <div class="space-y-4">
                        <div class="info-row">
                            <div class="info-icon">
                                <i class="fas fa-tag"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wide mb-1 font-bold">Label</p>
                                <p class="text-[#1A1A1D] font-black">{{ $address->label ?? 'Tidak ada label' }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="info-row">
                                <div class="info-icon">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1 font-bold">Penerima</p>
                                    <p class="text-[#1A1A1D] font-black">{{ $address->recipient_name }}</p>
                                </div>
                            </div>

                            <div class="info-row">
                                <div class="info-icon">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1 font-bold">Telepon</p>
                                    <p class="text-[#1A1A1D] font-black">{{ $address->phone }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wide mb-1 font-bold">Alamat Lengkap</p>
                                <p class="text-[#1A1A1D] font-black leading-relaxed">
                                    {{ $address->address }}<br>
                                    {{ $address->city_name }}, {{ $address->province_name }} {{ $address->postal_code }}
                                </p>
                            </div>
                        </div>

                        @if($address->note)
                        <div class="info-row">
                            <div class="info-icon">
                                <i class="fas fa-sticky-note"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wide mb-1 font-bold">Catatan</p>
                                <p class="text-gray-700 italic font-semibold">"{{ $address->note }}"</p>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="mt-8 pt-6 border-t border-gray-200 flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('addresses.edit', $address->id) }}" 
                           class="flex-1 flex items-center justify-center gap-2 px-6 py-4 bg-[#1A1A1D] text-white rounded-2xl font-bold hover:bg-gray-800 shadow-lg hover:shadow-xl transition-all">
                            <i class="fas fa-edit"></i>
                            Edit Alamat
                        </a>
                        
                        @if(!$address->is_primary)
                            <form action="{{ route('addresses.setPrimary', $address->id) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" 
                                        class="w-full flex items-center justify-center gap-2 px-6 py-4 bg-gray-100 text-[#1A1A1D] rounded-2xl font-bold hover:bg-gray-200 transition-all shadow-sm">
                                    <i class="fas fa-star"></i>
                                    Jadikan Utama
                                </button>
                            </form>
                        @endif

                        <form action="{{ route('addresses.destroy', $address->id) }}" method="POST" class="flex-1"
                              onsubmit="return confirm('Yakin ingin menghapus alamat ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full flex items-center justify-center gap-2 px-6 py-4 bg-red-50 border border-red-200 text-red-600 rounded-2xl font-bold hover:bg-red-100 transition-all shadow-sm">
                                <i class="fas fa-trash"></i>
                                Hapus Alamat
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
