@extends('customer.layouts.app')

@section('title', 'Profil Saya')

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

    .stat-card {
        background: linear-gradient(135deg, #F9FAFB 0%, #F3F4F6 100%);
        border: 1px solid #E5E7EB;
        border-radius: 1rem;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
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
            <span class="text-[#1A1A1D] font-bold">Profil Saya</span>
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
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <!-- Points Card -->
                    <div class="stat-card p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-xs font-semibold uppercase tracking-wide mb-2">Total Poin</p>
                                <p class="text-4xl font-black text-[#1A1A1D] tracking-tight">{{ number_format($userPoint->total_points ?? 0) }}</p>
                            </div>
                            <div class="w-14 h-14 bg-[#1A1A1D] rounded-full flex items-center justify-center">
                                <i class="fas fa-coins text-white text-2xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Total Orders Card -->
                    <div class="stat-card p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-xs font-semibold uppercase tracking-wide mb-2">Total Pesanan</p>
                                <p class="text-4xl font-black text-[#1A1A1D] tracking-tight">{{ $totalOrders }}</p>
                            </div>
                            <div class="w-14 h-14 bg-[#1A1A1D] rounded-full flex items-center justify-center">
                                <i class="fas fa-shopping-bag text-white text-2xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Completed Orders Card -->
                    <div class="stat-card p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-xs font-semibold uppercase tracking-wide mb-2">Pesanan Selesai</p>
                                <p class="text-4xl font-black text-[#1A1A1D] tracking-tight">{{ $completedOrders }}</p>
                            </div>
                            <div class="w-14 h-14 bg-[#1A1A1D] rounded-full flex items-center justify-center">
                                <i class="fas fa-check-circle text-white text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Profile Information Card -->
                <div class="profile-card p-8">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-xl font-bold text-[#1A1A1D] flex items-center gap-2 uppercase tracking-wide">
                            <i class="fas fa-user"></i>
                            Informasi Profil
                        </h3>
                        <a href="{{ route('customer.edit') }}" 
                           class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-[#1A1A1D] text-white rounded-xl font-bold hover:bg-gray-800 transition-all shadow-lg text-sm">
                            <i class="fas fa-edit"></i>
                            Edit Profil
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div class="flex items-center p-4 bg-gray-50 rounded-xl border border-gray-100">
                                <div class="w-12 h-12 bg-[#1A1A1D] rounded-lg flex items-center justify-center mr-4">
                                    <i class="fas fa-user text-white"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Nama Lengkap</p>
                                    <p class="text-[#1A1A1D] font-bold">{{ $user->name }}</p>
                                </div>
                            </div>

                            <div class="flex items-center p-4 bg-gray-50 rounded-xl border border-gray-100">
                                <div class="w-12 h-12 bg-[#1A1A1D] rounded-lg flex items-center justify-center mr-4">
                                    <i class="fas fa-envelope text-white"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Email</p>
                                    <p class="text-[#1A1A1D] font-bold">{{ $user->email }}</p>
                                </div>
                            </div>

                            <div class="flex items-center p-4 bg-gray-50 rounded-xl border border-gray-100">
                                <div class="w-12 h-12 bg-[#1A1A1D] rounded-lg flex items-center justify-center mr-4">
                                    <i class="fas fa-phone text-white"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Nomor Telepon</p>
                                    <p class="text-[#1A1A1D] font-bold">{{ $user->phone ?? 'Belum diatur' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="flex items-center p-4 bg-gray-50 rounded-xl border border-gray-100">
                                <div class="w-12 h-12 bg-[#1A1A1D] rounded-lg flex items-center justify-center mr-4">
                                    <i class="fas fa-birthday-cake text-white"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Tanggal Lahir</p>
                                    <p class="text-[#1A1A1D] font-bold">
                                        {{ $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->format('d F Y') : 'Belum diatur' }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center p-4 bg-gray-50 rounded-xl border border-gray-100">
                                <div class="w-12 h-12 bg-[#1A1A1D] rounded-lg flex items-center justify-center mr-4">
                                    <i class="fas fa-venus-mars text-white"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Jenis Kelamin</p>
                                    <p class="text-[#1A1A1D] font-bold">
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

                            <div class="flex items-center p-4 bg-gray-50 rounded-xl border border-gray-100">
                                <div class="w-12 h-12 bg-[#1A1A1D] rounded-lg flex items-center justify-center mr-4">
                                    <i class="fas fa-calendar-alt text-white"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Bergabung Sejak</p>
                                    <p class="text-[#1A1A1D] font-bold">{{ $user->created_at->format('d F Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <a href="{{ route('customer.orders') }}" class="profile-card p-6 flex items-center hover:border-[#1A1A1D] group">
                        <div class="w-16 h-16 bg-black rounded-xl flex items-center justify-center mr-4 group-hover:bg-black transition-colors">
                            <i class="fas fa-box text-white text-2xl"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-black text-[#1A1A1D] text-base">Lihat Pesanan</h4>
                            <p class="text-gray-500 text-sm mt-1">Cek status pesanan Anda</p>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400 ml-2"></i>
                    </a>

                    <a href="{{ route('addresses.index') }}" class="profile-card p-6 flex items-center hover:border-[#1A1A1D] group">
                        <div class="w-16 h-16 bg-black rounded-xl flex items-center justify-center mr-4 group-hover:bg-black transition-colors">
                            <i class="fas fa-map-marker-alt text-white text-2xl"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-black text-[#1A1A1D] text-base">Kelola Alamat</h4>
                            <p class="text-gray-500 text-sm mt-1">Atur alamat pengiriman</p>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400 ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
