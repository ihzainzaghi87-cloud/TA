@extends('customer.layouts.app')

@section('title', 'Poin Saya')

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

    .points-display {
        background: linear-gradient(135deg, #1A1A1D 0%, #374151 100%);
        border-radius: 1.5rem;
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
        border: 1px solid #86efac;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .badge-redeemed {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fca5a5;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .badge-refund {
        background: #dbeafe;
        color: #1e40af;
        border: 1px solid #93c5fd;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
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
            <a href="{{ route('customer.index') }}" class="text-gray-400 hover:text-black transition-colors">Profile</a>
            <i class="fas fa-chevron-right text-gray-300 mx-3 text-xs"></i>
            <span class="text-[#1A1A1D] font-bold">Poin Saya</span>
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
                        <a href="{{ route('customer.points') }}" class="menu-item active">
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
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-3 space-y-6">
                <!-- Points Balance Card -->
                <div class="points-display p-10 text-center">
                    <div class="w-24 h-24 mx-auto bg-white/20 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-coins text-white text-5xl"></i>
                    </div>
                    <p class="text-white/80 text-sm font-bold uppercase tracking-widest mb-2">Total Poin Anda</p>
                    <h2 class="text-6xl font-black text-white mb-4 tracking-tight">{{ number_format($userPoint->total_points ?? 0) }}</h2>
                    <p class="text-white/80 text-sm font-medium">Poin dapat digunakan untuk mendapatkan diskon</p>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="stat-card p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-xs font-semibold uppercase tracking-wide mb-2">Total Poin Didapat</p>
                                <p class="text-4xl font-black text-[#1A1A1D] tracking-tight">{{ number_format($totalEarned) }}</p>
                            </div>
                            <div class="w-14 h-14 bg-green-500 rounded-full flex items-center justify-center">
                                <i class="fas fa-arrow-up text-white text-2xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-xs font-semibold uppercase tracking-wide mb-2">Total Poin Digunakan</p>
                                <p class="text-4xl font-black text-[#1A1A1D] tracking-tight">{{ number_format($totalRedeemed) }}</p>
                            </div>
                            <div class="w-14 h-14 bg-red-500 rounded-full flex items-center justify-center">
                                <i class="fas fa-arrow-down text-white text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Transactions -->
                <div class="profile-card p-8">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-xl font-bold text-[#1A1A1D] flex items-center gap-2 uppercase tracking-wide">
                            <i class="fas fa-history"></i>
                            Transaksi Terakhir
                        </h3>
                        <a href="{{ route('customer.point-transactions') }}" 
                           class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-700 hover:border-[#1A1A1D] hover:text-[#1A1A1D] transition-all text-sm font-bold shadow-sm">
                            Lihat Semua
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>

                    @if($recentTransactions->count() > 0)
                    <div class="divide-y divide-gray-100">
                        @foreach($recentTransactions as $transaction)
                        <div class="transaction-item py-5 flex items-center justify-between">
                            <div class="flex items-center gap-5">
                                <div class="w-14 h-14 rounded-xl flex items-center justify-center
                                    {{ $transaction->type == 'earned' ? 'bg-green-100' : ($transaction->type == 'redeemed' ? 'bg-red-100' : 'bg-blue-100') }}">
                                    <i class="fas {{ $transaction->type == 'earned' ? 'fa-plus text-green-600' : ($transaction->type == 'redeemed' ? 'fa-minus text-red-600' : 'fa-undo text-blue-600') }} text-xl"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-[#1A1A1D] text-base">{{ $transaction->description ?? ucfirst($transaction->type) }}</p>
                                    <p class="text-sm text-gray-500 mt-1"><i class="fas fa-calendar text-[#1A1A1D] mr-1"></i>{{ $transaction->created_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-black text-2xl {{ $transaction->type == 'earned' ? 'text-green-600' : ($transaction->type == 'redeemed' ? 'text-red-600' : 'text-blue-600') }}">
                                    {{ $transaction->type == 'earned' || $transaction->type == 'refund' ? '+' : '-' }}{{ number_format($transaction->points) }}
                                </p>
                                <span class="text-xs px-3 py-1 rounded-full badge-{{ $transaction->type }} inline-block mt-1">
                                    {{ $transaction->type == 'earned' ? 'Didapat' : ($transaction->type == 'redeemed' ? 'Digunakan' : 'Refund') }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-12">
                        <div class="w-24 h-24 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-6">
                            <i class="fas fa-coins text-gray-400 text-4xl"></i>
                        </div>
                        <h4 class="text-2xl font-black text-[#1A1A1D] mb-3 tracking-tight">Belum Ada Transaksi</h4>
                        <p class="text-gray-500 text-sm mb-8">Mulai belanja untuk mendapatkan poin!</p>
                        <a href="{{ route('home') }}#products" 
                           class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-[#1A1A1D] text-white rounded-2xl font-bold hover:bg-gray-800 transition-all shadow-lg">
                            <i class="fas fa-shopping-bag"></i>
                            Mulai Belanja
                        </a>
                    </div>
                    @endif
                </div>

                <!-- How to Earn Points -->
                <div class="profile-card p-8">
                    <h3 class="text-xl font-bold text-[#1A1A1D] mb-8 flex items-center gap-2 uppercase tracking-wide">
                        <i class="fas fa-lightbulb"></i>
                        Cara Mendapatkan Poin
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <div class="text-center p-6 bg-gray-50 rounded-xl border border-gray-100 hover:border-[#1A1A1D] transition-all">
                            <div class="w-16 h-16 mx-auto bg-[#1A1A1D] rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-shopping-bag text-white text-2xl"></i>
                            </div>
                            <h4 class="font-bold text-[#1A1A1D] mb-2">Belanja</h4>
                            <p class="text-sm text-gray-500">Setiap Rp 10.000 = 1 poin</p>
                        </div>
                        <div class="text-center p-6 bg-gray-50 rounded-xl border border-gray-100 hover:border-[#1A1A1D] transition-all">
                            <div class="w-16 h-16 mx-auto bg-[#1A1A1D] rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-star text-white text-2xl"></i>
                            </div>
                            <h4 class="font-bold text-[#1A1A1D] mb-2">Review Produk</h4>
                            <p class="text-sm text-gray-500">10 poin per review</p>
                        </div>
                        <div class="text-center p-6 bg-gray-50 rounded-xl border border-gray-100 hover:border-[#1A1A1D] transition-all">
                            <div class="w-16 h-16 mx-auto bg-[#1A1A1D] rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-birthday-cake text-white text-2xl"></i>
                            </div>
                            <h4 class="font-bold text-[#1A1A1D] mb-2">Ulang Tahun</h4>
                            <p class="text-sm text-gray-500">Bonus 100 poin</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
