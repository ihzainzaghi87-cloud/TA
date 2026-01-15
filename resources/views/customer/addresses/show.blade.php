@extends('customer.layouts.app')

@section('title', 'Detail Alamat')

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

    .info-row {
        display: flex;
        align-items: flex-start;
        padding: 16px;
        background: #f9fafb;
        border-radius: 12px;
    }

    .info-icon {
        width: 40px;
        height: 40px;
        background: #fef3c7;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 16px;
        flex-shrink: 0;
    }

    .info-icon i {
        color: #d97706;
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
            <a href="{{ route('customer.index') }}" class="text-gray-500 hover:text-amber-600 transition-colors">
                Profil
            </a>
            <i class="fas fa-chevron-right text-gray-300 mx-3 text-xs"></i>
            <a href="{{ route('addresses.index') }}" class="text-gray-500 hover:text-amber-600 transition-colors">
                Alamat
            </a>
            <i class="fas fa-chevron-right text-gray-300 mx-3 text-xs"></i>
            <span class="text-gray-900 font-medium">Detail</span>
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
                           class="w-10 h-10 bg-gray-100 hover:bg-amber-100 rounded-lg flex items-center justify-center text-gray-600 hover:text-amber-600 transition-colors">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <div class="flex-1">
                            <h1 class="text-2xl font-bold text-gray-900">Detail Alamat</h1>
                            <p class="text-gray-500 text-sm">{{ $address->label ?? 'Alamat' }}</p>
                        </div>
                        @if($address->is_primary)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-amber-100 text-amber-800">
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
                                <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Label</p>
                                <p class="text-gray-900 font-semibold">{{ $address->label ?? 'Tidak ada label' }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="info-row">
                                <div class="info-icon">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Penerima</p>
                                    <p class="text-gray-900 font-semibold">{{ $address->recipient_name }}</p>
                                </div>
                            </div>

                            <div class="info-row">
                                <div class="info-icon">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Telepon</p>
                                    <p class="text-gray-900 font-semibold">{{ $address->phone }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Alamat Lengkap</p>
                                <p class="text-gray-900 font-semibold leading-relaxed">
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
                                <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Catatan</p>
                                <p class="text-gray-700 italic">"{{ $address->note }}"</p>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="mt-8 pt-6 border-t border-gray-100 flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('addresses.edit', $address->id) }}" 
                           class="flex-1 flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-amber-500 to-yellow-500 text-white rounded-xl font-semibold hover:from-amber-600 hover:to-yellow-600 shadow-lg hover:shadow-xl transition">
                            <i class="fas fa-edit"></i>
                            Edit Alamat
                        </a>
                        
                        @if(!$address->is_primary)
                            <form action="{{ route('addresses.setPrimary', $address->id) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" 
                                        class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-amber-50 text-amber-700 rounded-xl font-semibold hover:bg-amber-100 transition">
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
                                    class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-red-50 text-red-600 rounded-xl font-semibold hover:bg-red-100 transition">
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
