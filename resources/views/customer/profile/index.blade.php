@extends('customer.layouts.app')

@section('title', 'Profil Saya')

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
            <span class="text-gray-900 font-medium">Profil Saya</span>
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
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">{{ $user->name }}</h2>
                        <p class="text-gray-500 text-sm">{{ $user->email }}</p>
                    </div>

                    <!-- Menu Navigation -->
                    <nav class="space-y-2">
                        <a href="{{ route('customer.index') }}" class="menu-item active">
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
                        <a href="{{ route('addresses.index') }}" class="menu-item">
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
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <!-- Points Card -->
                    <div class="stat-card p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-amber-800 text-sm font-medium">Total Poin</p>
                                <p class="text-3xl font-bold text-amber-900">{{ number_format($userPoint->total_points ?? 0) }}</p>
                            </div>
                            <div class="w-12 h-12 bg-amber-400 rounded-full flex items-center justify-center">
                                <i class="fas fa-coins text-white text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Total Orders Card -->
                    <div class="stat-card p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-amber-800 text-sm font-medium">Total Pesanan</p>
                                <p class="text-3xl font-bold text-amber-900">{{ $totalOrders }}</p>
                            </div>
                            <div class="w-12 h-12 bg-amber-400 rounded-full flex items-center justify-center">
                                <i class="fas fa-shopping-bag text-white text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Completed Orders Card -->
                    <div class="stat-card p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-amber-800 text-sm font-medium">Pesanan Selesai</p>
                                <p class="text-3xl font-bold text-amber-900">{{ $completedOrders }}</p>
                            </div>
                            <div class="w-12 h-12 bg-amber-400 rounded-full flex items-center justify-center">
                                <i class="fas fa-check-circle text-white text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Profile Information Card -->
                <div class="profile-card p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-900 flex items-center gap-3">
                            <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-user text-amber-600"></i>
                            </div>
                            Informasi Profil
                        </h3>
                        <a href="{{ route('customer.edit') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-amber-500 to-yellow-500 text-white rounded-lg font-semibold text-sm hover:from-amber-600 hover:to-yellow-600 transition-all duration-300">
                            <i class="fas fa-edit mr-2"></i>
                            Edit Profil
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div class="flex items-center p-4 bg-gray-50 rounded-xl">
                                <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center mr-4">
                                    <i class="fas fa-user text-amber-600"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wide">Nama Lengkap</p>
                                    <p class="text-gray-900 font-semibold">{{ $user->name }}</p>
                                </div>
                            </div>

                            <div class="flex items-center p-4 bg-gray-50 rounded-xl">
                                <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center mr-4">
                                    <i class="fas fa-envelope text-amber-600"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wide">Email</p>
                                    <p class="text-gray-900 font-semibold">{{ $user->email }}</p>
                                </div>
                            </div>

                            <div class="flex items-center p-4 bg-gray-50 rounded-xl">
                                <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center mr-4">
                                    <i class="fas fa-phone text-amber-600"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wide">Nomor Telepon</p>
                                    <p class="text-gray-900 font-semibold">{{ $user->phone ?? 'Belum diatur' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="flex items-center p-4 bg-gray-50 rounded-xl">
                                <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center mr-4">
                                    <i class="fas fa-birthday-cake text-amber-600"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wide">Tanggal Lahir</p>
                                    <p class="text-gray-900 font-semibold">
                                        {{ $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->format('d F Y') : 'Belum diatur' }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center p-4 bg-gray-50 rounded-xl">
                                <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center mr-4">
                                    <i class="fas fa-venus-mars text-amber-600"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wide">Jenis Kelamin</p>
                                    <p class="text-gray-900 font-semibold">
                                        @if($user->gender == 'male')
                                            Laki-laki
                                        @elseif($user->gender == 'female')
                                            Perempuan
                                        @else
                                            Belum diatur
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center p-4 bg-gray-50 rounded-xl">
                                <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center mr-4">
                                    <i class="fas fa-calendar-alt text-amber-600"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wide">Bergabung Sejak</p>
                                    <p class="text-gray-900 font-semibold">{{ $user->created_at->format('d F Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <a href="{{ route('customer.orders') }}" class="profile-card p-6 flex items-center hover:border-amber-400 group">
                        <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center mr-4 group-hover:bg-blue-200 transition-colors">
                            <i class="fas fa-box text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900">Lihat Pesanan</h4>
                            <p class="text-gray-500 text-sm">Cek status pesanan Anda</p>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400 ml-auto"></i>
                    </a>

                    <a href="{{ route('addresses.index') }}" class="profile-card p-6 flex items-center hover:border-amber-400 group">
                        <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center mr-4 group-hover:bg-green-200 transition-colors">
                            <i class="fas fa-map-marker-alt text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900">Kelola Alamat</h4>
                            <p class="text-gray-500 text-sm">Atur alamat pengiriman</p>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400 ml-auto"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
