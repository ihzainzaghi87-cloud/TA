@extends('customer.layouts.app')

@section('title', 'Detail Alamat')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        <a href="{{ route('addresses.index') }}" class="text-gray-500 hover:text-purple-600 transition mb-4 inline-flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Alamat
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Detail Alamat</h1>
    </div>

    <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8 relative overflow-hidden">
        @if($address->is_primary)
            <div class="absolute top-0 right-0 bg-gradient-to-l from-purple-600 to-pink-600 text-white text-xs font-bold px-4 py-2 rounded-bl-2xl shadow-md">
                Alamat Utama
            </div>
        @endif

        <div class="space-y-6">
            <div>
                <h2 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Label</h2>
                <p class="text-xl font-bold text-gray-900">{{ $address->label ?? 'Tidak ada label' }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h2 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Penerima</h2>
                    <p class="text-lg text-gray-900">{{ $address->recipient_name }}</p>
                </div>
                <div>
                    <h2 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Telepon</h2>
                    <p class="text-lg text-gray-900">{{ $address->phone }}</p>
                </div>
            </div>

            <div>
                <h2 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Alamat Lengkap</h2>
                <p class="text-lg text-gray-900 leading-relaxed">
                    {{ $address->address }}<br>
                    {{ $address->city_name }}, {{ $address->province_name }} {{ $address->postal_code }}
                </p>
            </div>

            @if($address->note)
                <div>
                    <h2 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Catatan</h2>
                    <p class="text-gray-700 italic bg-gray-50 p-4 rounded-xl border border-gray-100">
                        "{{ $address->note }}"
                    </p>
                </div>
            @endif
        </div>

        <div class="mt-8 pt-6 border-t border-gray-100 flex gap-4">
            <a href="{{ route('addresses.edit', $address->id) }}" class="flex-1 bg-purple-50 text-purple-700 text-center py-3 rounded-xl font-semibold hover:bg-purple-100 transition">
                Edit Alamat
            </a>
            @if(!$address->is_primary)
                <form action="{{ route('addresses.destroy', $address->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Yakin ingin menghapus alamat ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full bg-red-50 text-red-600 py-3 rounded-xl font-semibold hover:bg-red-100 transition">
                        Hapus Alamat
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
