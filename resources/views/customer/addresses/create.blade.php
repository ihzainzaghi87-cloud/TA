@extends('customer.layouts.app')

@section('title', 'Tambah Alamat Baru')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        <a href="{{ route('addresses.index') }}" class="text-gray-500 hover:text-purple-600 transition mb-4 inline-flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Alamat
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Tambah Alamat Baru</h1>
    </div>

    <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8">
        <form action="{{ route('addresses.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Label Alamat --}}
                <div class="md:col-span-2">
                    <label for="label" class="block text-sm font-medium text-gray-700 mb-2">Label Alamat (Opsional)</label>
                    <input type="text" name="label" id="label" value="{{ old('label') }}" 
                           class="w-full rounded-xl border-gray-300 focus:border-purple-500 focus:ring-purple-500"
                           placeholder="Contoh: Rumah, Kantor, Apartemen">
                    @error('label')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nama Penerima --}}
                <div>
                    <label for="recipient_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Penerima <span class="text-red-500">*</span></label>
                    <input type="text" name="recipient_name" id="recipient_name" value="{{ old('recipient_name') }}" required
                           class="w-full rounded-xl border-gray-300 focus:border-purple-500 focus:ring-purple-500">
                    @error('recipient_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nomor Telepon --}}
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon <span class="text-red-500">*</span></label>
                    <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" required
                           class="w-full rounded-xl border-gray-300 focus:border-purple-500 focus:ring-purple-500">
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Provinsi --}}
                <div>
                    <label for="province_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Provinsi <span class="text-red-500">*</span>
                    </label>
                    <select name="province_id" id="province_id" required
                            class="w-full rounded-xl border-gray-300 focus:border-purple-500 focus:ring-purple-500">
                        <option value="">Pilih Provinsi</option>
                        @if(!empty($provinces) && is_array($provinces))
                            @foreach($provinces as $province)
                                @php
                                    // ✅ PERBAIKAN: Handle different possible structures
                                    // Check if it's an array
                                    if (is_array($province)) {
                                        // Try different possible key names from API
                                        $provinceId = $province['province_id'] ?? 
                                                    $province['id'] ?? 
                                                    ($province['provinceId'] ?? '');
                                        
                                        $provinceName = $province['province'] ?? 
                                                    $province['name'] ?? 
                                                    ($province['province_name'] ?? 'Unknown Province');
                                    } else {
                                        // If it's an object
                                        $provinceId = $province->province_id ?? 
                                                    $province->id ?? 
                                                    ($province->provinceId ?? '');
                                        
                                        $provinceName = $province->province ?? 
                                                    $province->name ?? 
                                                    ($province->province_name ?? 'Unknown Province');
                                    }
                                @endphp
                                
                                @if($provinceId && $provinceName !== 'Unknown Province')
                                    <option value="{{ $provinceId }}" {{ old('province_id') == $provinceId ? 'selected' : '' }}>
                                        {{ $provinceName }}
                                    </option>
                                @endif
                            @endforeach
                        @else
                            <option value="">Gagal memuat provinsi - Coba refresh halaman</option>
                        @endif
                    </select>
                    <input type="hidden" name="province_name" id="province_name">
                    @error('province_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kota/Kabupaten --}}
                <div>
                    <label for="city_id" class="block text-sm font-medium text-gray-700 mb-2">Kota/Kabupaten <span class="text-red-500">*</span></label>
                    <select name="city_id" id="city_id" required disabled
                            class="w-full rounded-xl border-gray-300 focus:border-purple-500 focus:ring-purple-500 bg-gray-50">
                        <option value="">Pilih Provinsi Terlebih Dahulu</option>
                    </select>
                    <input type="hidden" name="city_name" id="city_name">
                    @error('city_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kode Pos --}}
                <div>
                    <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-2">Kode Pos (Opsional)</label>
                    <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code') }}"
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
                              placeholder="Nama Jalan, No. Rumah, RT/RW, Kelurahan, Kecamatan">{{ old('address') }}</textarea>
                    @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Catatan --}}
                <div class="md:col-span-2">
                    <label for="note" class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                    <input type="text" name="note" id="note" value="{{ old('note') }}"
                           class="w-full rounded-xl border-gray-300 focus:border-purple-500 focus:ring-purple-500"
                           placeholder="Warna rumah, patokan, dll">
                </div>

                {{-- Jadikan Utama --}}
                <div class="md:col-span-2">
                    <label class="inline-flex items-center gap-3">
                        <input type="checkbox" name="is_primary" value="1" {{ old('is_primary') ? 'checked' : '' }}
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
                    Simpan Alamat
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
    const provinceNameInput = document.getElementById('province_name');
    const cityNameInput = document.getElementById('city_name');
    const oldCityId = "{{ old('city_id') }}";

    // Function to update hidden names
    function updateHiddenNames() {
        if (provinceSelect.selectedIndex >= 0) {
            provinceNameInput.value = provinceSelect.options[provinceSelect.selectedIndex].text.trim();
        }
        if (citySelect.selectedIndex >= 0) {
            cityNameInput.value = citySelect.options[citySelect.selectedIndex].text.trim();
        }
    }

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

        console.log('Fetching cities for province:', provinceId);  // Debug log

        fetch(`/api/provinces/${provinceId}/cities`)
            .then(response => {
                console.log('Response status:', response.status);  // Debug log
                return response.json();
            })
            .then(response => {
                console.log('API Response:', response);  // ✅ Debug log
                
                // ✅ PERBAIKAN: Check multiple possible response structures
                let cities = [];
                
                if (response.success && response.data) {
                    cities = Array.isArray(response.data) ? response.data : [];
                } else if (Array.isArray(response)) {
                    // If response is direct array
                    cities = response;
                } else if (response.data && Array.isArray(response.data)) {
                    // Fallback check
                    cities = response.data;
                }
                
                console.log('Cities count:', cities.length);  // Debug log
                
                if (cities.length === 0) {
                    citySelect.innerHTML = '<option value="">Tidak ada kota ditemukan untuk provinsi ini</option>';
                    return;
                }
                
                citySelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
                
                cities.forEach((city, index) => {
                    // ✅ PERBAIKAN: Handle different key names
                    const cityId = city.city_id || city.id || city.cityId || '';
                    const cityType = city.type || '';
                    const cityName = city.city_name || city.name || city.cityName || 'Unknown City';
                    
                    if (cityId) {
                        const isSelected = selectedCityId == cityId ? 'selected' : '';
                        const displayName = cityType ? `${cityType} ${cityName}` : cityName;
                        
                        citySelect.innerHTML += `<option value="${cityId}" ${isSelected}>${displayName}</option>`;
                    }
                    
                    // Debug first few items
                    if (index < 3) {
                        console.log(`City ${index}:`, {cityId, cityType, cityName});
                    }
                });
                
                citySelect.disabled = false;
                citySelect.classList.remove('bg-gray-50');
                
                // Update hidden name for city
                updateHiddenNames();
            })
            .catch(error => {
                console.error('Fetch Error:', error);  // Debug log
                citySelect.innerHTML = '<option value="">Gagal memuat kota - Silakan coba lagi</option>';
            });
    }

    // Event listener for province change
    provinceSelect.addEventListener('change', function() {
        console.log('Province changed to:', this.value);  // Debug log
        updateHiddenNames();
        loadCities(this.value);
    });
    
    // Event listener for city change
    citySelect.addEventListener('change', function() {
        updateHiddenNames();
    });

    // Load cities if province is already selected
    if (provinceSelect.value) {
        updateHiddenNames();
        loadCities(provinceSelect.value, oldCityId);
    }
});
</script>
@endpush
