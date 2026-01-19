@extends('customer.layouts.app')

@section('title', 'Riwayat Transaksi Poin')

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
            <a href="{{ route('customer.points') }}" class="text-gray-400 hover:text-black transition-colors">Poin</a>
            <i class="fas fa-chevron-right text-gray-300 mx-3 text-xs"></i>
            <span class="text-[#1A1A1D] font-bold">Riwayat Transaksi</span>
        </nav>

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

                    <!-- Current Balance -->
                    <div class="bg-gradient-to-r from-[#1A1A1D] to-gray-700 rounded-2xl p-5 mb-6 text-center">
                        <p class="text-white/80 text-xs font-bold uppercase tracking-widest mb-1">Saldo Poin</p>
                        <p class="text-white text-3xl font-black tracking-tight">{{ number_format($currentBalance) }}</p>
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
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-3">
                <div class="profile-card p-8">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
                        <h3 class="text-2xl font-black text-[#1A1A1D] tracking-tight flex items-center gap-2 uppercase tracking-wide">
                            <i class="fas fa-history"></i>
                            Riwayat Transaksi Poin
                        </h3>

                        <!-- Filter Buttons -->
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('customer.point-transactions') }}" 
                               class="filter-btn {{ !$type ? 'active' : '' }}">
                                Semua
                            </a>
                            <a href="{{ route('customer.point-transactions', ['type' => 'earned']) }}" 
                               class="filter-btn {{ $type == 'earned' ? 'active' : '' }}">
                                Didapat
                            </a>
                            <a href="{{ route('customer.point-transactions', ['type' => 'redeemed']) }}" 
                               class="filter-btn {{ $type == 'redeemed' ? 'active' : '' }}">
                                Digunakan
                            </a>
                            <!-- <a href="{{ route('customer.point-transactions', ['type' => 'refund']) }}" 
                               class="filter-btn {{ $type == 'refund' ? 'active' : '' }}">
                                Refund
                            </a> -->
                        </div>
                    </div>

                    @if($transactions->count() > 0)
                    <div class="divide-y divide-gray-100">
                        @foreach($transactions as $transaction)
                        <div class="transaction-item py-5 flex items-center justify-between">
                            <div class="flex items-center gap-5">
                                <div class="w-14 h-14 rounded-xl flex items-center justify-center
                                    {{ $transaction->type == 'earned' ? 'bg-green-100' : ($transaction->type == 'redeemed' ? 'bg-red-100' : 'bg-blue-100') }}">
                                    <i class="fas {{ $transaction->type == 'earned' ? 'fa-plus text-green-600' : ($transaction->type == 'redeemed' ? 'fa-minus text-red-600' : 'fa-undo text-blue-600') }} text-xl"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-[#1A1A1D] text-base">{{ $transaction->description ?? ucfirst($transaction->type) }}</p>
                                    <p class="text-sm text-gray-500 mt-1"><i class="fas fa-calendar text-[#1A1A1D] mr-1"></i>{{ $transaction->created_at->format('d M Y, H:i') }}</p>
                                    @if($transaction->transactionable)
                                    <p class="text-xs text-gray-400 mt-1">
                                        Order: #{{ $transaction->transactionable->order_number ?? $transaction->transactionable_id }}
                                    </p>
                                    @endif
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-black text-2xl {{ $transaction->type == 'earned' || $transaction->type == 'refund' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $transaction->type == 'earned' || $transaction->type == 'refund' ? '+' : '-' }}{{ number_format($transaction->points) }}
                                </p>
                                <span class="text-xs px-3 py-1 rounded-full badge-{{ $transaction->type }} inline-block mt-1">
                                    {{ $transaction->type == 'earned' ? 'Didapat' : ($transaction->type == 'redeemed' ? 'Digunakan' : 'Refund') }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $transactions->appends(['type' => $type])->links() }}
                    </div>
                    @else
                    <div class="text-center py-12">
                        <div class="w-24 h-24 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-6">
                            <i class="fas fa-history text-gray-400 text-4xl"></i>
                        </div>
                        <h4 class="text-2xl font-black text-[#1A1A1D] mb-3 tracking-tight">Belum Ada Transaksi</h4>
                        <p class="text-gray-500 text-sm mb-8">
                            @if($type)
                                Tidak ada transaksi dengan tipe "{{ $type }}"
                            @else
                                Mulai belanja untuk mendapatkan poin!
                            @endif
                        </p>
                        @if($type)
                        <a href="{{ route('customer.point-transactions') }}" 
                           class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-white border border-gray-300 rounded-xl text-gray-700 hover:border-[#1A1A1D] hover:text-[#1A1A1D] transition-all font-bold shadow-sm">
                            <i class="fas fa-arrow-left"></i>
                            Lihat Semua Transaksi
                        </a>
                        @else
                        <a href="{{ route('home') }}#products" 
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
</div>
@endsection
