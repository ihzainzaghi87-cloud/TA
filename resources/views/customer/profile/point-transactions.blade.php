@extends('customer.layouts.app')

@section('title', 'Riwayat Transaksi Poin')

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
            <a href="{{ route('home') }}" class="text-gray-500 hover:text-gray-600 transition-colors">
                <i class="fas fa-home"></i>
            </a>
            <i class="fas fa-chevron-right text-gray-300 mx-3 text-xs"></i>
            <a href="{{ route('customer.index') }}" class="text-gray-500 hover:text-gray-600 transition-colors">Profil</a>
            <i class="fas fa-chevron-right text-gray-300 mx-3 text-xs"></i>
            <a href="{{ route('customer.points') }}" class="text-gray-500 hover:text-gray-600 transition-colors">Poin</a>
            <i class="fas fa-chevron-right text-gray-300 mx-3 text-xs"></i>
            <span class="text-gray-900 font-medium">Riwayat Transaksi</span>
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
                    <div class="bg-gradient-to-r from-gray-500 to-gray-500 rounded-xl p-4 mb-6 text-center">
                        <p class="text-white/80 text-xs mb-1">Saldo Poin</p>
                        <p class="text-white text-2xl font-bold">{{ number_format($currentBalance) }}</p>
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
                <div class="profile-card p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                        <h3 class="text-xl font-bold text-gray-900 flex items-center gap-3">
                            <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-history text-gray-600"></i>
                            </div>
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
                        <div class="transaction-item py-4 flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center
                                    {{ $transaction->type == 'earned' ? 'bg-green-100' : ($transaction->type == 'redeemed' ? 'bg-red-100' : 'bg-blue-100') }}">
                                    <i class="fas {{ $transaction->type == 'earned' ? 'fa-plus text-green-600' : ($transaction->type == 'redeemed' ? 'fa-minus text-red-600' : 'fa-undo text-blue-600') }} text-lg"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $transaction->description ?? ucfirst($transaction->type) }}</p>
                                    <p class="text-sm text-gray-500">{{ $transaction->created_at->format('d M Y, H:i') }}</p>
                                    @if($transaction->transactionable)
                                    <p class="text-xs text-gray-400 mt-1">
                                        Order: #{{ $transaction->transactionable->order_number ?? $transaction->transactionable_id }}
                                    </p>
                                    @endif
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-lg {{ $transaction->type == 'earned' || $transaction->type == 'refund' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $transaction->type == 'earned' || $transaction->type == 'refund' ? '+' : '-' }}{{ number_format($transaction->points) }}
                                </p>
                                <span class="text-xs px-2 py-1 rounded-full badge-{{ $transaction->type }}">
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
                        <div class="w-20 h-20 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-history text-gray-400 text-3xl"></i>
                        </div>
                        <h4 class="text-gray-900 font-semibold mb-2">Belum Ada Transaksi</h4>
                        <p class="text-gray-500 text-sm">
                            @if($type)
                                Tidak ada transaksi dengan tipe "{{ $type }}"
                            @else
                                Mulai belanja untuk mendapatkan poin!
                            @endif
                        </p>
                        @if($type)
                        <a href="{{ route('customer.point-transactions') }}" 
                           class="inline-block mt-4 text-gray-600 hover:text-gray-700 font-semibold text-sm">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Lihat Semua Transaksi
                        </a>
                        @else
                        <a href="{{ route('home') }}#products" 
                           class="inline-block mt-4 bg-gradient-to-r from-gray-500 to-gray-500 text-white px-6 py-2 rounded-lg font-semibold text-sm hover:from-gray-600 hover:to-gray-600 transition-all">
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
