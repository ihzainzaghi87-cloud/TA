@extends('customer.layouts.app')

@section('title', 'Tambah Alamat Baru')

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

    .form-input {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        font-size: 14px;
        transition: all 0.2s ease;
    }

    .form-input:focus {
        outline: none;
        border-color: #FAD470;
        box-shadow: 0 0 0 3px rgba(250, 212, 112, 0.2);
    }

    .form-input:disabled {
        background-color: #f9fafb;
    }

    .form-label {
        display: block;
        font-size: 14px;
        font-weight: 500;
        color: #374151;
        margin-bottom: 8px;
    }

    .form-label .required {
        color: #ef4444;
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
            <a href="{{ route('customer.index') }}" class="text-gray-500 hover:text-amber-600 transition-colors">
                Profil
            </a>
            <i class="fas fa-chevron-right text-gray-300 mx-3 text-xs"></i>
            <a href="{{ route('addresses.index') }}" class="text-gray-500 hover:text-amber-600 transition-colors">
                Alamat
            </a>
            <i class="fas fa-chevron-right text-gray-300 mx-3 text-xs"></i>
            <span class="text-gray-900 font-medium">Tambah</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Sidebar Menu -->
            <div class="lg:col-span-1">
                <div class="profile-card p-6">
                    <!-- User Avatar & Info -->
                    <div class="text-center mb-6">
                        <div class="w-24 h-24 mx-auto avatar-ring rounded-full flex items-center justify-center text-white text-3xl font-bold mb-4">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">{{ auth()->user()->name }}</h2>
                        <p class="text-gray-500 text-sm">{{ auth()->user()->email }}</p>
                    </div>

                    <!-- Menu Navigation -->
                    <nav class="space-y-2">
                        <a href="{{ route('customer.index') }}" class="menu-item">
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
                        <a href="{{ route('addresses.index') }}" class="menu-item active">
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
            <div class="lg:col-span-3">
                <div class="profile-card p-6 md:p-8">
                    <!-- Header -->
                    <div class="flex items-center gap-4 mb-8">
                        <a href="{{ route('addresses.index') }}" 
                           class="w-10 h-10 bg-gray-100 hover:bg-amber-100 rounded-lg flex items-center justify-center text-gray-600 hover:text-amber-600 transition-colors">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Tambah Alamat Baru</h1>
                            <p class="text-gray-500 text-sm">Isi detail alamat pengiriman Anda</p>
                        </div>
                    </div>

                    <form action="{{ route('addresses.store') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Label Alamat --}}
                            <div class="md:col-span-2">
                                <label for="label" class="form-label">Label Alamat (Opsional)</label>
                                <input type="text" name="label" id="label" value="{{ old('label') }}" 
                                       class="form-input"
                                       placeholder="Contoh: Rumah, Kantor, Apartemen">
                                @error('label')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Nama Penerima --}}
                            <div>
                                <label for="recipient_name" class="form-label">
                                    Nama Penerima <span class="required">*</span>
                                </label>
                                <input type="text" name="recipient_name" id="recipient_name" value="{{ old('recipient_name') }}" required
                                       class="form-input" placeholder="Nama lengkap penerima">
                                @error('recipient_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Nomor Telepon --}}
                            <div>
                                <label for="phone" class="form-label">
                                    Nomor Telepon <span class="required">*</span>
                                </label>
                                <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" required
                                       class="form-input" placeholder="08xxxxxxxxxx">
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Provinsi --}}
                            <div>
                                <label for="province_id" class="form-label">
                                    Provinsi <span class="required">*</span>
                                </label>
                                <select name="province_id" id="province_id" required class="form-input">
                                    <option value="">Pilih Provinsi</option>
                                    @if(!empty($provinces) && is_array($provinces))
                                        @foreach($provinces as $province)
                                            @php
                                                if (is_array($province)) {
                                                    $provinceId = $province['province_id'] ?? $province['id'] ?? ($province['provinceId'] ?? '');
                                                    $provinceName = $province['province'] ?? $province['name'] ?? ($province['province_name'] ?? 'Unknown Province');
                                                } else {
                                                    $provinceId = $province->province_id ?? $province->id ?? ($province->provinceId ?? '');
                                                    $provinceName = $province->province ?? $province->name ?? ($province->province_name ?? 'Unknown Province');
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
                                <label for="city_id" class="form-label">
                                    Kota/Kabupaten <span class="required">*</span>
                                </label>
                                <select name="city_id" id="city_id" required disabled class="form-input">
                                    <option value="">Pilih Provinsi Terlebih Dahulu</option>
                                </select>
                                <input type="hidden" name="city_name" id="city_name">
                                @error('city_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Kode Pos --}}
                            <div>
                                <label for="postal_code" class="form-label">Kode Pos (Opsional)</label>
                                <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code') }}"
                                       class="form-input" placeholder="Kode pos">
                                @error('postal_code')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Alamat Lengkap --}}
                            <div class="md:col-span-2">
                                <label for="address" class="form-label">
                                    Alamat Lengkap <span class="required">*</span>
                                </label>
                                <textarea name="address" id="address" rows="3" required
                                          class="form-input"
                                          placeholder="Nama Jalan, No. Rumah, RT/RW, Kelurahan, Kecamatan">{{ old('address') }}</textarea>
                                @error('address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Catatan --}}
                            <div class="md:col-span-2">
                                <label for="note" class="form-label">Catatan (Opsional)</label>
                                <input type="text" name="note" id="note" value="{{ old('note') }}"
                                       class="form-input"
                                       placeholder="Warna rumah, patokan, dll">
                            </div>

                            {{-- Jadikan Utama --}}
                            <div class="md:col-span-2">
                                <label class="inline-flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" name="is_primary" value="1" {{ old('is_primary') ? 'checked' : '' }}
                                           class="w-5 h-5 rounded border-gray-300 text-amber-500 focus:ring-amber-500">
                                    <span class="text-gray-700 font-medium">Jadikan sebagai alamat utama</span>
                                </label>
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t border-gray-100 flex flex-col sm:flex-row justify-end gap-4">
                            <a href="{{ route('addresses.index') }}" 
                               class="px-6 py-3 rounded-xl border border-gray-300 text-gray-700 font-semibold hover:bg-gray-50 transition text-center">
                                Batal
                            </a>
                            <button type="submit" 
                                    class="px-6 py-3 rounded-xl bg-gradient-to-r from-amber-500 to-yellow-500 text-white font-semibold hover:from-amber-600 hover:to-yellow-600 shadow-lg hover:shadow-xl transition">
                                <i class="fas fa-save mr-2"></i>
                                Simpan Alamat
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
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

    function updateHiddenNames() {
        if (provinceSelect.selectedIndex >= 0) {
            provinceNameInput.value = provinceSelect.options[provinceSelect.selectedIndex].text.trim();
        }
        if (citySelect.selectedIndex >= 0) {
            cityNameInput.value = citySelect.options[citySelect.selectedIndex].text.trim();
        }
    }

    function loadCities(provinceId, selectedCityId = null) {
        if (!provinceId) {
            citySelect.innerHTML = '<option value="">Pilih Provinsi Terlebih Dahulu</option>';
            citySelect.disabled = true;
            return;
        }

        citySelect.innerHTML = '<option value="">Memuat...</option>';
        citySelect.disabled = true;

        fetch(`/api/provinces/${provinceId}/cities`)
            .then(response => response.json())
            .then(response => {
                let cities = [];
                
                if (response.success && response.data) {
                    cities = Array.isArray(response.data) ? response.data : [];
                } else if (Array.isArray(response)) {
                    cities = response;
                } else if (response.data && Array.isArray(response.data)) {
                    cities = response.data;
                }
                
                if (cities.length === 0) {
                    citySelect.innerHTML = '<option value="">Tidak ada kota ditemukan</option>';
                    return;
                }
                
                citySelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
                
                cities.forEach(city => {
                    const cityId = city.city_id || city.id || city.cityId || '';
                    const cityType = city.type || '';
                    const cityName = city.city_name || city.name || city.cityName || '';
                    
                    if (cityId) {
                        const isSelected = selectedCityId == cityId ? 'selected' : '';
                        const displayName = cityType ? `${cityType} ${cityName}` : cityName;
                        citySelect.innerHTML += `<option value="${cityId}" ${isSelected}>${displayName}</option>`;
                    }
                });
                
                citySelect.disabled = false;
                updateHiddenNames();
            })
            .catch(error => {
                console.error('Error:', error);
                citySelect.innerHTML = '<option value="">Gagal memuat kota</option>';
            });
    }

    provinceSelect.addEventListener('change', function() {
        updateHiddenNames();
        loadCities(this.value);
    });
    
    citySelect.addEventListener('change', function() {
        updateHiddenNames();
    });

    if (provinceSelect.value) {
        updateHiddenNames();
        loadCities(provinceSelect.value, oldCityId);
    }
});
</script>
@endpush
