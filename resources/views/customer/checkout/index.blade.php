@extends('customer.layouts.app')

@section('title', 'Checkout - The Paranoia')

@push('styles')
<style>
    [x-cloak] { display: none !important; }
    .checkout-hero-gradient {
        background: linear-gradient(90deg, #9333ea, #ec4899, #ef4444);
    }
    .checkout-primary-gradient {
        background: linear-gradient(90deg, #9333ea, #ec4899);
    }
    .checkout-card-shadow {
        box-shadow: 0 4px 20px rgba(147, 51, 234, 0.1);
    }
</style>
@endpush

@section('content')
{{-- Hero Section --}}
<section class="checkout-hero-gradient text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <p class="text-sm uppercase tracking-[0.3em] text-white/80 mb-4">Secure Checkout</p>
        <h1 class="text-3xl md:text-4xl font-bold">Finalize Your Order</h1>
        <p class="mt-4 text-white/80 max-w-2xl mx-auto">
            Review your items, fill in shipping details, and complete your purchase securely.
        </p>
    </div>
</section>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-12 pb-20 relative z-10">
    
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

    @if($errors->any())
        <div class="mb-6">
            <x-alert type="error" :message="$errors->first()" />
        </div>
    @endif

    <form action="{{ route('orders.store') }}" method="POST">
        @csrf
        <input type="hidden" name="selected_variations" value="{{ e(old('selected_variations', json_encode(session('selected_variations', [])))) }}">
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Left Column - Shipping Info & Items --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- Shipping Address Card --}}
                <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-full checkout-primary-gradient flex items-center justify-center text-white shadow-lg">
                                <i class="fas fa-map-marker-alt text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900">Alamat Pengiriman</h2>
                                <p class="text-sm text-gray-500">Pilih alamat pengiriman Anda</p>
                            </div>
                        </div>
                        <a href="{{ route('addresses.create') }}" class="text-purple-600 font-semibold hover:text-purple-700 text-sm">
                            + Tambah Alamat
                        </a>
                    </div>
                    
                    <div class="space-y-4 mb-6">
                        @if($primaryAddress)
                            <div class="border border-purple-500 bg-purple-50/30 rounded-xl p-4 relative">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="font-bold text-gray-900">{{ $primaryAddress->label }}</span>
                                    <span class="px-2 py-0.5 bg-purple-100 text-purple-700 text-xs rounded-full font-medium">Utama</span>
                                </div>
                                <p class="text-gray-900 font-medium">{{ $primaryAddress->recipient_name }} ({{ $primaryAddress->phone }})</p>
                                <p class="text-gray-500 text-sm mt-1">{{ $primaryAddress->full_address }}</p>
                                
                                <input type="hidden" name="user_address_id" id="user_address_id" value="{{ $primaryAddress->id }}" data-city="{{ $primaryAddress->city_id }}">
                                
                                <div class="mt-4">
                                    <a href="{{ route('addresses.index') }}" class="text-sm text-purple-600 font-semibold hover:text-purple-700">
                                        Ubah Alamat Utama
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-8 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                                <p class="text-gray-500 mb-4">Anda belum mengatur alamat utama.</p>
                                <a href="{{ route('addresses.index') }}" class="inline-block px-6 py-2 bg-purple-600 text-white rounded-full hover:bg-purple-700 transition">
                                    Atur Alamat
                                </a>
                            </div>
                            <input type="hidden" name="user_address_id" required>
                        @endif
                        @error('user_address_id')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Courier Selection --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t border-gray-100 pt-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Pilih Kurir
                            </label>
                            <select name="courier" id="courier" class="w-full px-4 py-3 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200">
                                <option value="">Pilih Kurir</option>
                                <option value="jne">JNE</option>
                                <option value="pos">POS Indonesia</option>
                                <option value="tiki">TIKI</option>
                                <option value="sicepat">SiCepat</option>
                                <option value="jnt">J&T</option>
                                <option value="lion">Lion Parcel</option>
                            </select>
                            @error('courier')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            
                            {{-- Same City Info --}}
                            @if($primaryAddress && $primaryAddress->city_id == config('rajaongkir.origin_city'))
                                <div class="mt-2 bg-blue-50 border border-blue-200 rounded-xl p-3">
                                    <p class="text-xs text-blue-700 flex items-center gap-2">
                                        <i class="fas fa-info-circle"></i>
                                        <span>Pengiriman dalam kota. Beberapa kurir mungkin tidak tersedia.</span>
                                    </p>
                                </div>
                            @endif
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Layanan Pengiriman
                            </label>
                            <select name="shipping_service_select" id="shipping_service" disabled class="w-full px-4 py-3 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200 bg-gray-50">
                                <option value="">Pilih Kurir Terlebih Dahulu</option>
                            </select>
                            <div id="shipping_error" class="hidden mt-2 text-sm text-red-600 bg-red-50 p-3 rounded-xl border border-red-100"></div>
                            
                            {{-- Hidden inputs for form submission --}}
                            <input type="hidden" name="service" id="service_input">
                            <input type="hidden" name="shipping_cost" id="shipping_cost_input" value="0">
                            <input type="hidden" name="weight" value="{{ $totalWeight ?? 1000 }}">
                            
                            @error('service')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Catatan Pesanan <span class="text-gray-400 font-normal">(Opsional)</span>
                        </label>
                        <textarea name="notes" 
                                  rows="2"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200 resize-none"
                                  placeholder="Catatan untuk penjual, misalnya: warna alternatif, dll">{{ old('notes') }}</textarea>
                    </div>
                </div>

                {{-- Order Items Card --}}
                <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-full checkout-primary-gradient flex items-center justify-center text-white shadow-lg">
                                <i class="fas fa-shopping-bag text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900">Item Pesanan</h2>
                                <p class="text-sm text-gray-500">{{ $cartItems->count() }} produk</p>
                            </div>
                        </div>
                        <span class="bg-purple-100 text-purple-700 px-4 py-2 rounded-full text-sm font-semibold">
                            {{ $cartItems->sum('quantity') }} Item
                        </span>
                    </div>

                    <div class="space-y-4">
                        @foreach($cartItems as $item)
                            @php
                                $product = $item->variation->product;
                                $variation = $item->variation;
                            @endphp
                            
                            <article class="flex gap-4 pb-5 border-b border-gray-100 last:border-0 last:pb-0">
                                {{-- Product Image --}}
                                <div class="w-20 h-20 shrink-0">
                                    @if($product->images && $product->images->count() > 0)
                                        <img src="{{ asset('storage/products/' . $product->images->first()->image) }}" 
                                             alt="{{ $product->name }}"
                                             class="w-full h-full object-cover rounded-xl shadow-sm">
                                    @else
                                        <div class="w-full h-full bg-linear-to-br from-gray-200 to-gray-300 rounded-xl flex items-center justify-center">
                                            <i class="fas fa-image text-gray-400 text-2xl"></i>
                                        </div>
                                    @endif
                                </div>

                                {{-- Product Info --}}
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-bold text-gray-900 mb-1 truncate">{{ $product->name }}</h3>
                                    <p class="text-sm text-gray-500 flex flex-wrap gap-2 mb-2">
                                        @if($variation->color)
                                            <span class="inline-flex items-center gap-1">
                                                <i class="fas fa-palette text-xs text-purple-500"></i>
                                                {{ ucfirst($variation->color) }}
                                            </span>
                                        @endif
                                        @if($variation->size)
                                            <span class="inline-flex items-center gap-1">
                                                <i class="fas fa-ruler text-xs text-purple-500"></i>
                                                {{ strtoupper($variation->size) }}
                                            </span>
                                        @endif
                                        <span class="inline-flex items-center gap-1">
                                            <i class="fas fa-box text-xs text-purple-500"></i>
                                            Qty: {{ $item->quantity }}
                                        </span>
                                    </p>
                                </div>

                                {{-- Price --}}
                                <div class="text-right">
                                    <p class="font-bold text-purple-600 text-lg">
                                        Rp {{ number_format($product->price * $item->quantity, 0, ',', '.') }}
                                    </p>
                                    @if($product->point_price)
                                        <p class="text-sm text-amber-600 font-medium mt-1">
                                            <i class="fas fa-star text-xs"></i>
                                            {{ number_format($product->point_price * $item->quantity, 0, ',', '.') }} Poin
                                        </p>
                                    @endif
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Right Column - Order Summary --}}
            <aside class="lg:col-span-1">
                <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8 space-y-6 sticky top-24">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Ringkasan Pesanan</h2>
                        <p class="text-sm text-gray-500">{{ $cartItems->sum('quantity') }} barang</p>
                    </div>

                    <div class="space-y-4">
                        <div class="flex justify-between text-gray-700">
                            <span>Subtotal</span>
                            <span class="font-semibold">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        
                        <div class="flex justify-between text-gray-700">
                            <span class="flex items-center gap-2">
                                <i class="fas fa-truck text-purple-500"></i>
                                Ongkir
                            </span>
                            <span class="font-semibold" id="shipping-cost-display">Rp 0</span>
                        </div>

                        {{-- PRODUCT POINTS REQUIRED --}}
                        @if($totalPointsNeeded > 0)
                            <div class="pt-4 border-t border-gray-200">
                                <div class="bg-linear-to-r from-red-50 to-pink-50 rounded-2xl p-4 border-2 border-red-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <div>
                                            <p class="text-sm font-bold text-red-700 flex items-center gap-2">
                                                <i class="fas fa-exclamation-circle"></i>
                                                Poin Dibutuhkan
                                            </p>
                                            <p class="text-xs text-gray-600 mt-1">
                                                Otomatis terpotong saat checkout
                                            </p>
                                        </div>
                                        <p class="text-2xl font-bold text-red-600">
                                            <i class="fas fa-star"></i>
                                            {{ number_format($totalPointsNeeded, 0, ',', '.') }}
                                        </p>
                                    </div>
                                    
                                    @if(!$hasEnoughPoints)
                                        <div class="mt-3 bg-red-100 border border-red-300 rounded-xl p-3">
                                            <p class="text-sm font-semibold text-red-700 flex items-center gap-2">
                                                <i class="fas fa-times-circle"></i>
                                                Poin Anda Tidak Mencukupi!
                                            </p>
                                            <p class="text-xs text-red-600 mt-1">
                                                Anda punya <strong>{{ number_format($availablePoints, 0, ',', '.') }}</strong> poin, 
                                                butuh <strong>{{ number_format($totalPointsNeeded, 0, ',', '.') }}</strong> poin
                                            </p>
                                        </div>
                                    @else
                                        <div class="mt-3 bg-green-100 border border-green-300 rounded-xl p-3">
                                            <p class="text-sm font-semibold text-green-700 flex items-center gap-2">
                                                <i class="fas fa-check-circle"></i>
                                                Poin Anda Cukup
                                            </p>
                                            <p class="text-xs text-green-600 mt-1">
                                                Sisa poin: <strong>{{ number_format($availablePoints - $totalPointsNeeded, 0, ',', '.') }}</strong> poin
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <div class="pt-4 border-t-2 border-gray-200">
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-lg font-bold text-gray-900">Total Bayar</span>
                                <span class="text-3xl font-bold text-transparent bg-clip-text checkout-primary-gradient" id="total-display">
                                    Rp {{ number_format($subtotal, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        {{-- Points Reward Info --}}
                        <div class="bg-linear-to-r from-purple-50 to-pink-50 rounded-2xl p-4 border border-purple-200">
                            <p class="flex items-center text-sm font-semibold text-purple-700 gap-2">
                                <i class="fas fa-gift text-pink-500"></i>
                                Reward Poin dari Pesanan Ini:
                            </p>
                            <p class="text-2xl font-bold text-purple-600 mt-1">
                                <i class="fas fa-star text-amber-400"></i>
                                +{{ number_format($pointsWillEarn, 0, ',', '.') }} Poin
                            </p>
                            <p class="text-xs text-gray-600 mt-2 flex items-center gap-1">
                                <i class="fas fa-info-circle"></i>
                                Rp 10.000 = 1 Poin
                            </p>
                        </div>
                    </div>

                    @php
                        $disableCheckout = !$hasEnoughPoints && $totalPointsNeeded > 0;
                    @endphp
                    {{-- Submit Button --}}
                        <button type="submit"
                            @if($disableCheckout) disabled @endif
                            @class([
                                'w-full py-4 rounded-full font-bold text-lg shadow-xl transform transition duration-300',
                                'bg-gray-400 text-gray-700 cursor-not-allowed' => $disableCheckout,
                                'checkout-primary-gradient text-white hover:shadow-2xl hover:-translate-y-0.5' => ! $disableCheckout,
                            ])>
                        <i class="fas fa-lock mr-2"></i>
                        @if($disableCheckout)
                            Poin Tidak Mencukupi
                        @else
                            Buat Pesanan Sekarang
                        @endif
                    </button>

                    {{-- Back to Cart --}}
                    <a href="{{ route('cart.index') }}" 
                       class="w-full inline-flex items-center justify-center gap-2 border border-purple-100 text-purple-600 font-medium py-3 rounded-full hover:bg-purple-50 transition">
                        <i class="fas fa-arrow-left"></i>
                        Kembali ke Keranjang
                    </a>

                    {{-- Security Info --}}
                    <div class="space-y-3 text-xs text-gray-600">
                        <div class="flex items-start gap-3 p-3 bg-green-50 rounded-xl">
                            <i class="fas fa-shield-alt text-green-500 text-lg mt-0.5"></i>
                            <div>
                                <p class="font-semibold text-green-700">Transaksi Aman</p>
                                <p>Data Anda dilindungi dengan enkripsi SSL</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 p-3 bg-blue-50 rounded-xl">
                            <i class="fas fa-truck text-blue-500 text-lg mt-0.5"></i>
                            <div>
                                <p class="font-semibold text-blue-700">Pengiriman Cepat</p>
                                <p>Estimasi 2-3 hari kerja</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 p-3 bg-purple-50 rounded-xl">
                            <i class="fas fa-headset text-purple-500 text-lg mt-0.5"></i>
                            <div>
                                <p class="font-semibold text-purple-700">Bantuan 24/7</p>
                                <p>Customer service siap membantu</p>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const addressInput = document.getElementById('user_address_id');
    const courierSelect = document.getElementById('courier');
    const serviceSelect = document.getElementById('shipping_service');
    const shippingCostDisplay = document.getElementById('shipping-cost-display');
    const totalDisplay = document.getElementById('total-display');
    const shippingCostInput = document.getElementById('shipping_cost_input');
    const serviceInput = document.getElementById('service_input');
    
    const subtotal = {{ $subtotal }};
    
    function formatRupiah(amount) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
    }
    
    const shippingErrorDiv = document.getElementById('shipping_error');
    
    function calculateShipping() {
        const courier = courierSelect.value;
        
        // Reset error state
        shippingErrorDiv.classList.add('hidden');
        shippingErrorDiv.innerText = '';
        
        if (!addressInput || !courier) {
            serviceSelect.innerHTML = '<option value="">Pilih Kurir Terlebih Dahulu</option>';
            serviceSelect.disabled = true;
            serviceSelect.classList.add('bg-gray-50');
            return;
        }
        
        const cityId = addressInput.dataset.city;
        
        // Show loading state
        serviceSelect.innerHTML = '<option value="">Memuat Layanan...</option>';
        serviceSelect.disabled = true;
        
        fetch('/calculate-cart', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                destination_city_id: cityId,
                courier: courier
            })
        })
        .then(response => response.json())
        .then(data => {
            console.log('API Response:', data);
            
            // Check success and structure
            if (data.success && data.rajaongkir && data.rajaongkir.results && data.rajaongkir.results.length > 0) {
                const results = data.rajaongkir.results[0];
                
                if (!results.costs || results.costs.length === 0) {
                    serviceSelect.innerHTML = '<option value="">Tidak ada layanan tersedia</option>';
                    shippingErrorDiv.innerHTML = 'Tidak ada layanan pengiriman tersedia untuk rute ini.<br><small class="text-xs">Silakan pilih kurir lain atau hubungi customer service.</small>';
                    shippingErrorDiv.classList.remove('hidden');
                    return;
                }
                
                const costs = results.costs;
                
                serviceSelect.innerHTML = '<option value="">Pilih Layanan</option>';
                
                // FIX: Akses langsung ke cost object (bukan cost.cost)
                costs.forEach((cost, index) => {
                    const serviceName = cost.service;
                    const serviceDesc = cost.description;
                    const price = cost.cost;  // ✅ PERBAIKAN: Langsung ambil cost
                    const etd = cost.etd;
                    
                    const optionText = `${serviceName} (${serviceDesc}) - ${formatRupiah(price)} (Est: ${etd})`;
                    
                    const option = document.createElement('option');
                    option.value = price;
                    option.text = optionText;
                    option.dataset.service = serviceName;
                    
                    serviceSelect.appendChild(option);
                });
                
                serviceSelect.disabled = false;
                serviceSelect.classList.remove('bg-gray-50');
                
            } else {
                const msg = data.message || 'Layanan tidak tersedia untuk rute ini';
                serviceSelect.innerHTML = '<option value="">Tidak tersedia</option>';
                
                shippingErrorDiv.innerHTML = `
                    <div class="font-semibold">${msg}</div>
                    <small class="text-xs block mt-1">Coba pilih kurir lain atau hubungi customer service untuk bantuan.</small>
                `;
                shippingErrorDiv.classList.remove('hidden');
                
                console.warn('Shipping service unavailable:', data);
            }
        })
        .catch(error => {
            console.error('Fetch Error:', error);
            serviceSelect.innerHTML = '<option value="">Gagal memuat layanan</option>';
            
            shippingErrorDiv.innerHTML = `
                <div class="font-semibold">Terjadi kesalahan saat menghubungi server</div>
                <small class="text-xs block mt-1">Silakan refresh halaman dan coba lagi.</small>
            `;
            shippingErrorDiv.classList.remove('hidden');
        });
    }
    
    function updateTotal() {
        const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
        
        if (!selectedOption || !selectedOption.value) {
            shippingCostDisplay.innerText = formatRupiah(0);
            totalDisplay.innerText = formatRupiah(subtotal);
            shippingCostInput.value = 0;
            serviceInput.value = '';
            return;
        }

        const shippingCost = parseInt(selectedOption.value) || 0;
        const serviceName = selectedOption.dataset.service || '';
        
        const total = subtotal + shippingCost;
        
        shippingCostDisplay.innerText = formatRupiah(shippingCost);
        totalDisplay.innerText = formatRupiah(total);
        shippingCostInput.value = shippingCost;
        serviceInput.value = serviceName;
    }
    
    // Event Listeners
    courierSelect.addEventListener('change', calculateShipping);
    serviceSelect.addEventListener('change', updateTotal);
    
    // Initial check
    if (addressInput && courierSelect.value) {
        calculateShipping();
    }
});
</script>
@endpush
