@extends('customer.layouts.app')

@section('title', 'Edit Alamat')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        <a href="{{ route('addresses.index') }}" class="text-gray-500 hover:text-purple-600 transition mb-4 inline-flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Alamat
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Edit Alamat</h1>
    </div>

    <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8">
        <form action="{{ route('addresses.update', $address->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Label Alamat --}}
                <div class="md:col-span-2">
                    <label for="label" class="block text-sm font-medium text-gray-700 mb-2">Label Alamat (Opsional)</label>
                    <input type="text" name="label" id="label" value="{{ old('label', $address->label) }}" 
                           class="w-full rounded-xl border-gray-300 focus:border-purple-500 focus:ring-purple-500"
                           placeholder="Contoh: Rumah, Kantor, Apartemen">
                    @error('label')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nama Penerima --}}
                <div>
                    <label for="recipient_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Penerima <span class="text-red-500">*</span></label>
                    <input type="text" name="recipient_name" id="recipient_name" value="{{ old('recipient_name', $address->recipient_name) }}" required
                           class="w-full rounded-xl border-gray-300 focus:border-purple-500 focus:ring-purple-500">
                    @error('recipient_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nomor Telepon --}}
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon <span class="text-red-500">*</span></label>
                    <input type="tel" name="phone" id="phone" value="{{ old('phone', $address->phone) }}" required
                           class="w-full rounded-xl border-gray-300 focus:border-purple-500 focus:ring-purple-500">
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Provinsi --}}
                <div>
                    <label for="province_id" class="block text-sm font-medium text-gray-700 mb-2">Provinsi <span class="text-red-500">*</span></label>
                    <select name="province_id" id="province_id" required
                            class="w-full rounded-xl border-gray-300 focus:border-purple-500 focus:ring-purple-500">
                        <option value="">Pilih Provinsi</option>
                        @foreach($provinces as $province)
                            <option value="{{ $province['province_id'] }}" {{ old('province_id', $address->province_id) == $province['province_id'] ? 'selected' : '' }}>
                                {{ $province['province'] }}
                            </option>
                        @endforeach
                    </select>
                    @error('province_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kota/Kabupaten --}}
                <div>
                    <label for="city_id" class="block text-sm font-medium text-gray-700 mb-2">Kota/Kabupaten <span class="text-red-500">*</span></label>
                    <select name="city_id" id="city_id" required
                            class="w-full rounded-xl border-gray-300 focus:border-purple-500 focus:ring-purple-500">
                        <option value="">Pilih Kota/Kabupaten</option>
                        @foreach($cities as $city)
                            <option value="{{ $city['city_id'] }}" {{ old('city_id', $address->city_id) == $city['city_id'] ? 'selected' : '' }}>
                                {{ $city['type'] }} {{ $city['city_name'] }}
                            </option>
                        @endforeach
                    </select>
                    @error('city_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kode Pos --}}
                <div>
                    <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-2">Kode Pos (Opsional)</label>
                    <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code', $address->postal_code) }}"
                           class="w-full rounded-xl border-gray-300 focus:border-purple-500 focus:ring-purple-500">
                    @error('postal_code')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Alamat Lengkap --}}
                <div class="md:col-span-2">
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap <span class="text-red-500">*</span></label>
                    <textarea name="address" id="address" rows="3" required
                              class="w-full rounded-xl border-gray-300 focus:border-purple-500 focus:ring-purple-500"
                              placeholder="Nama Jalan, No. Rumah, RT/RW, Kelurahan, Kecamatan">{{ old('address', $address->address) }}</textarea>
                    @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Catatan --}}
                <div class="md:col-span-2">
                    <label for="note" class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                    <input type="text" name="note" id="note" value="{{ old('note', $address->note) }}"
                           class="w-full rounded-xl border-gray-300 focus:border-purple-500 focus:ring-purple-500"
                           placeholder="Warna rumah, patokan, dll">
                </div>

                {{-- Jadikan Utama --}}
                <div class="md:col-span-2">
                    <label class="inline-flex items-center gap-3">
                        <input type="checkbox" name="is_primary" value="1" {{ old('is_primary', $address->is_primary) ? 'checked' : '' }}
                               class="w-5 h-5 rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                        <span class="text-gray-700 font-medium">Jadikan sebagai alamat utama</span>
                    </label>
                </div>
            </div>

            <div class="pt-6 border-t border-gray-100 flex justify-end gap-4">
                <a href="{{ route('addresses.index') }}" class="px-6 py-3 rounded-full border border-gray-300 text-gray-700 font-semibold hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-3 rounded-full bg-gradient-to-r from-purple-600 to-pink-600 text-white font-semibold hover:from-purple-700 hover:to-pink-700 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const provinceSelect = document.getElementById('province_id');
    const citySelect = document.getElementById('city_id');
    const oldCityId = "{{ old('city_id', $address->city_id) }}";
    
    // Function to load cities
    function loadCities(provinceId, selectedCityId = null) {
        if (!provinceId) {
            citySelect.innerHTML = '<option value="">Pilih Provinsi Terlebih Dahulu</option>';
            citySelect.disabled = true;
            citySelect.classList.add('bg-gray-50');
            return;
        }

        citySelect.innerHTML = '<option value="">Memuat...</option>';
        citySelect.disabled = true;

        fetch(`/api/provinces/${provinceId}/cities`)
            .then(response => response.json())
            .then(response => {
                const cities = response.data || response; // Handle wrapped or direct array
                citySelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
                
                if (Array.isArray(cities)) {
                    cities.forEach(city => {
                        const isSelected = selectedCityId == city.city_id ? 'selected' : '';
                        citySelect.innerHTML += `<option value="${city.city_id}" ${isSelected}>${city.type} ${city.name}</option>`;
                    });
                }
                
                citySelect.disabled = false;
                citySelect.classList.remove('bg-gray-50');
            })
            .catch(error => {
                console.error('Error:', error);
                citySelect.innerHTML = '<option value="">Gagal memuat kota</option>';
            });
    }

    // Event listener for province change
    provinceSelect.addEventListener('change', function() {
        loadCities(this.value);
    });

    // Check if we need to reload cities (e.g. validation error with different province)
    // If the rendered options don't match the selected province (which is rare unless validation error changed province but not city list)
    // Actually, to be safe and consistent with Create, we can just rely on PHP for initial load
    // BUT if validation failed and province changed, PHP $cities is wrong.
    // So we should check if the selected province is different from the address province.
    const originalProvinceId = "{{ $address->province_id }}";
    if (provinceSelect.value != originalProvinceId) {
        loadCities(provinceSelect.value, oldCityId);
    }
});
</script>
@endpush
