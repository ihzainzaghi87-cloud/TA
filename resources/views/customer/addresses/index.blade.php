@extends('customer.layouts.app')

@section('title', 'Daftar Alamat')

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

    .stat-card {
        background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
        border: 1px solid #fcd34d;
        border-radius: 16px;
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

    .address-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        transition: all 0.3s ease;
    }

    .address-card:hover {
        border-color: #FAD470;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
    }

    .address-card.primary {
        border-color: #FAD470;
        background: linear-gradient(135deg, #fffbeb 0%, #ffffff 100%);
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
            <span class="text-gray-900 font-medium">Alamat</span>
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
            <div class="lg:col-span-3 space-y-6">
                <!-- Header with Add Button -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Daftar Alamat</h1>
                        <p class="text-gray-500 text-sm mt-1">Kelola alamat pengiriman Anda</p>
                    </div>
                    <a href="{{ route('addresses.create') }}" 
                       class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-amber-500 to-yellow-500 text-white rounded-xl font-semibold hover:from-amber-600 hover:to-yellow-600 transition-all duration-300 shadow-lg hover:shadow-xl">
                        <i class="fas fa-plus"></i>
                        Tambah Alamat
                    </a>
                </div>

                <!-- Stats Card -->
                <div class="stat-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-amber-800 text-sm font-medium">Total Alamat Tersimpan</p>
                            <p class="text-3xl font-bold text-amber-900">{{ $addresses->count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-amber-400 rounded-full flex items-center justify-center">
                            <i class="fas fa-map-marker-alt text-white text-xl"></i>
                        </div>
                    </div>
                </div>

                @if($addresses->isEmpty())
                    <!-- Empty State -->
                    <div class="profile-card p-12 text-center">
                        <div class="w-20 h-20 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-map-marker-alt text-amber-600 text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Belum Ada Alamat</h3>
                        <p class="text-gray-500 max-w-md mx-auto mb-6">
                            Tambahkan alamat pengiriman Anda untuk memudahkan proses checkout.
                        </p>
                        <a href="{{ route('addresses.create') }}" 
                           class="inline-flex items-center gap-2 text-amber-600 font-semibold hover:text-amber-700">
                            <i class="fas fa-plus-circle"></i>
                            Tambah Alamat Sekarang
                        </a>
                    </div>
                @else
                    <!-- Address List -->
                    <div class="space-y-4">
                        @foreach($addresses as $address)
                        <div class="address-card {{ $address->is_primary ? 'primary' : '' }} p-6">
                            <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                                <!-- Address Info -->
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-3">
                                        <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-map-marker-alt text-amber-600"></i>
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-gray-900">{{ $address->label ?? 'Alamat' }}</h3>
                                            @if($address->is_primary)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                                    <i class="fas fa-star mr-1 text-xs"></i> Utama
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="ml-13 space-y-2">
                                        <div class="flex items-center text-gray-700">
                                            <i class="fas fa-user w-5 text-gray-400 text-sm"></i>
                                            <span class="ml-2 font-medium">{{ $address->recipient_name }}</span>
                                        </div>
                                        <div class="flex items-center text-gray-600">
                                            <i class="fas fa-phone w-5 text-gray-400 text-sm"></i>
                                            <span class="ml-2">{{ $address->phone }}</span>
                                        </div>
                                        <div class="flex items-start text-gray-600">
                                            <i class="fas fa-home w-5 text-gray-400 text-sm mt-0.5"></i>
                                            <span class="ml-2">
                                                {{ $address->address }}<br>
                                                {{ $address->city_name }}, {{ $address->province_name }} {{ $address->postal_code }}
                                            </span>
                                        </div>
                                        @if($address->note)
                                        <div class="flex items-center text-gray-500 text-sm italic">
                                            <i class="fas fa-sticky-note w-5 text-gray-400 text-sm"></i>
                                            <span class="ml-2">{{ $address->note }}</span>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex flex-row md:flex-col items-center gap-2">
                                    <a href="{{ route('addresses.edit', $address->id) }}" 
                                       class="w-10 h-10 bg-gray-100 hover:bg-amber-100 rounded-lg flex items-center justify-center text-gray-600 hover:text-amber-600 transition-colors"
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    @if(!$address->is_primary)
                                        <form action="{{ route('addresses.setPrimary', $address->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" 
                                                    class="w-10 h-10 bg-gray-100 hover:bg-amber-100 rounded-lg flex items-center justify-center text-gray-600 hover:text-amber-600 transition-colors"
                                                    title="Jadikan Utama">
                                                <i class="fas fa-star"></i>
                                            </button>
                                        </form>

                                        <form action="{{ route('addresses.destroy', $address->id) }}" method="POST" 
                                              onsubmit="return confirm('Yakin ingin menghapus alamat ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="w-10 h-10 bg-gray-100 hover:bg-red-100 rounded-lg flex items-center justify-center text-gray-600 hover:text-red-600 transition-colors"
                                                    title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
