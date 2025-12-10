@extends('customer.layouts.app')

@section('title', 'Poin Saya')

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

    .points-display {
        background: linear-gradient(135deg, #FAD470 0%, #F8B500 100%);
        border-radius: 20px;
    }

    .transaction-item {
        border-bottom: 1px solid #f3f4f6;
        transition: all 0.2s ease;
    }

    .transaction-item:hover {
        background: #f9fafb;
    }

    .transaction-item:last-child {
        border-bottom: none;
    }

    .badge-earned {
        background: #dcfce7;
        color: #166534;
    }

    .badge-redeemed {
        background: #fee2e2;
        color: #991b1b;
    }

    .badge-refund {
        background: #dbeafe;
        color: #1e40af;
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
            <span class="text-gray-900 font-medium">Poin Saya</span>
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
                        <a href="{{ route('customer.points') }}" class="menu-item active">
                            <i class="fas fa-coins w-5 mr-3"></i>
                            <span>Poin Saya</span>
                        </a>
                        <a href="{{ route('orders.index') }}" class="menu-item">
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
                <!-- Points Balance Card -->
                <div class="points-display p-8 text-center">
                    <div class="w-20 h-20 mx-auto bg-white/20 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-coins text-white text-4xl"></i>
                    </div>
                    <p class="text-white/80 text-sm font-medium mb-2">Total Poin Anda</p>
                    <h2 class="text-5xl font-bold text-white mb-4">{{ number_format($userPoint->total_points ?? 0) }}</h2>
                    <p class="text-white/70 text-sm">Poin dapat digunakan untuk mendapatkan diskon</p>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="stat-card p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-amber-800 text-sm font-medium">Total Poin Didapat</p>
                                <p class="text-3xl font-bold text-amber-900">{{ number_format($totalEarned) }}</p>
                            </div>
                            <div class="w-12 h-12 bg-green-400 rounded-full flex items-center justify-center">
                                <i class="fas fa-arrow-up text-white text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-amber-800 text-sm font-medium">Total Poin Digunakan</p>
                                <p class="text-3xl font-bold text-amber-900">{{ number_format($totalRedeemed) }}</p>
                            </div>
                            <div class="w-12 h-12 bg-red-400 rounded-full flex items-center justify-center">
                                <i class="fas fa-arrow-down text-white text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Transactions -->
                <div class="profile-card p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-900 flex items-center gap-3">
                            <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-history text-amber-600"></i>
                            </div>
                            Transaksi Terakhir
                        </h3>
                        <a href="{{ route('customer.point-transactions') }}" 
                           class="text-amber-600 hover:text-amber-700 text-sm font-semibold flex items-center gap-2">
                            Lihat Semua
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>

                    @if($recentTransactions->count() > 0)
                    <div class="divide-y divide-gray-100">
                        @foreach($recentTransactions as $transaction)
                        <div class="transaction-item py-4 flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center
                                    {{ $transaction->type == 'earned' ? 'bg-green-100' : ($transaction->type == 'redeemed' ? 'bg-red-100' : 'bg-blue-100') }}">
                                    <i class="fas {{ $transaction->type == 'earned' ? 'fa-plus text-green-600' : ($transaction->type == 'redeemed' ? 'fa-minus text-red-600' : 'fa-undo text-blue-600') }} text-lg"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $transaction->description ?? ucfirst($transaction->type) }}</p>
                                    <p class="text-sm text-gray-500">{{ $transaction->created_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-lg {{ $transaction->type == 'earned' ? 'text-green-600' : ($transaction->type == 'redeemed' ? 'text-red-600' : 'text-blue-600') }}">
                                    {{ $transaction->type == 'earned' || $transaction->type == 'refund' ? '+' : '-' }}{{ number_format($transaction->points) }}
                                </p>
                                <span class="text-xs px-2 py-1 rounded-full badge-{{ $transaction->type }}">
                                    {{ $transaction->type == 'earned' ? 'Didapat' : ($transaction->type == 'redeemed' ? 'Digunakan' : 'Refund') }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-12">
                        <div class="w-20 h-20 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-coins text-gray-400 text-3xl"></i>
                        </div>
                        <h4 class="text-gray-900 font-semibold mb-2">Belum Ada Transaksi</h4>
                        <p class="text-gray-500 text-sm">Mulai belanja untuk mendapatkan poin!</p>
                        <a href="{{ route('home') }}#products" 
                           class="inline-block mt-4 bg-gradient-to-r from-amber-500 to-yellow-500 text-white px-6 py-2 rounded-lg font-semibold text-sm hover:from-amber-600 hover:to-yellow-600 transition-all">
                            Mulai Belanja
                        </a>
                    </div>
                    @endif
                </div>

                <!-- How to Earn Points -->
                <div class="profile-card p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                        <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-lightbulb text-amber-600"></i>
                        </div>
                        Cara Mendapatkan Poin
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="text-center p-4 bg-gray-50 rounded-xl">
                            <div class="w-14 h-14 mx-auto bg-amber-100 rounded-full flex items-center justify-center mb-3">
                                <i class="fas fa-shopping-bag text-amber-600 text-xl"></i>
                            </div>
                            <h4 class="font-semibold text-gray-900 mb-1">Belanja</h4>
                            <p class="text-sm text-gray-500">Setiap Rp 10.000 = 1 poin</p>
                        </div>
                        <div class="text-center p-4 bg-gray-50 rounded-xl">
                            <div class="w-14 h-14 mx-auto bg-amber-100 rounded-full flex items-center justify-center mb-3">
                                <i class="fas fa-star text-amber-600 text-xl"></i>
                            </div>
                            <h4 class="font-semibold text-gray-900 mb-1">Review Produk</h4>
                            <p class="text-sm text-gray-500">10 poin per review</p>
                        </div>
                        <div class="text-center p-4 bg-gray-50 rounded-xl">
                            <div class="w-14 h-14 mx-auto bg-amber-100 rounded-full flex items-center justify-center mb-3">
                                <i class="fas fa-birthday-cake text-amber-600 text-xl"></i>
                            </div>
                            <h4 class="font-semibold text-gray-900 mb-1">Ulang Tahun</h4>
                            <p class="text-sm text-gray-500">Bonus 100 poin</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
