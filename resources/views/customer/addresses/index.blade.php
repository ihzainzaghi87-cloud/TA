@extends('customer.layouts.app')

@section('title', 'Daftar Alamat')

@push('styles')
<style>
    .address-card-gradient {
        background: linear-gradient(135deg, #ffffff, #fdf4ff);
    }
    .primary-badge-gradient {
        background: linear-gradient(90deg, #9333ea, #ec4899);
    }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Daftar Alamat</h1>
            <p class="text-gray-500 mt-1">Kelola alamat pengiriman Anda untuk checkout lebih cepat.</p>
        </div>
        <a href="{{ route('addresses.create') }}" class="inline-flex items-center justify-center gap-2 bg-purple-600 text-white px-6 py-3 rounded-full font-semibold hover:bg-purple-700 transition shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
            <i class="fas fa-plus"></i>
            Tambah Alamat Baru
        </a>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="mb-6">
            <x-alert type="success" :message="session('success')" />
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6">
            <x-alert type="error" :message="session('error')" />
        </div>
    @endif

    @if($addresses->isEmpty())
        <div class="bg-white rounded-3xl shadow-xl p-12 text-center border border-gray-100">
            <div class="w-24 h-24 bg-purple-50 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-map-marker-alt text-4xl text-purple-500"></i>
            </div>
            <h2 class="text-2xl font-semibold text-gray-900 mb-2">Belum ada alamat tersimpan</h2>
            <p class="text-gray-500 max-w-md mx-auto mb-8">Tambahkan alamat pengiriman Anda untuk memudahkan proses pemesanan produk favorit Anda.</p>
            <a href="{{ route('addresses.create') }}" class="inline-flex items-center gap-2 text-purple-600 font-semibold hover:text-purple-700">
                <i class="fas fa-plus-circle"></i>
                Tambah Alamat Sekarang
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($addresses as $address)
                <div class="address-card-gradient rounded-2xl p-6 border {{ $address->is_primary ? 'border-purple-500 ring-2 ring-purple-500/20' : 'border-gray-200' }} shadow-lg hover:shadow-xl transition duration-300 relative group">
                    @if($address->is_primary)
                        <div class="absolute top-4 right-4">
                            <span class="primary-badge-gradient text-white text-xs font-bold px-3 py-1 rounded-full shadow-md">
                                Utama
                            </span>
                        </div>
                    @endif

                    <div class="mb-4">
                        <h3 class="font-bold text-gray-900 text-lg mb-1">{{ $address->label ?? 'Alamat' }}</h3>
                        <p class="text-sm text-gray-500 font-medium">{{ $address->recipient_name }}</p>
                        <p class="text-sm text-gray-500">{{ $address->phone }}</p>
                    </div>

                    <div class="mb-6 min-h-[80px]">
                        <p class="text-gray-600 text-sm leading-relaxed">
                            {{ $address->address }}<br>
                            {{ $address->city_name }}, {{ $address->province_name }} {{ $address->postal_code }}
                        </p>
                        @if($address->note)
                            <p class="text-xs text-gray-400 mt-2 italic">
                                <i class="fas fa-sticky-note mr-1"></i> {{ $address->note }}
                            </p>
                        @endif
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                        <div class="flex gap-2">
                            <a href="{{ route('addresses.edit', $address->id) }}" class="text-gray-400 hover:text-purple-600 transition p-2" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if(!$address->is_primary)
                                <form action="{{ route('addresses.destroy', $address->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus alamat ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-500 transition p-2" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </div>

                        @if(!$address->is_primary)
                            <form action="{{ route('addresses.setPrimary', $address->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="text-sm font-medium text-purple-600 hover:text-purple-800 transition">
                                    Jadikan Utama
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
