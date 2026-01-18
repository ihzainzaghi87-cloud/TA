@extends('customer.layouts.app')

@section('title', 'Checkout - The Paranoia')

@push('styles')
<style>
    [x-cloak] { display: none !important; }
    
    .checkout-hero-bg { background-color: #0c0c0c; } /* Darker Hero Background */

    /* Primary Button (Black) */
    .checkout-primary-btn { 
        background-color: #1A1A1D; 
        color: #ffffff; 
        border: 1px solid #1A1A1D;
        transition: all 0.3s ease;
    }
    .checkout-primary-btn:hover { 
        background-color: #333333; 
        border-color: #333333;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .checkout-primary-btn:disabled {
        background-color: #9ca3af;
        border-color: #9ca3af;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    /* Secondary Button (White/Outline) */
    .checkout-secondary-btn { 
        background-color: #ffffff; 
        color: #1A1A1D; 
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
    }
    .checkout-secondary-btn:hover { 
        background-color: #f3f4f6; 
        border-color: #1A1A1D;
        color: #000;
    }

    /* Cards */
    .checkout-card { 
        background: #ffffff; 
        border: 1px solid #e5e7eb;
        border-radius: 1.25rem; /* Rounded 20px */
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    /* Inputs & Selects */
    .checkout-input, select, textarea {
        background-color: #f9fafb;
        border: 1px solid #e5e7eb;
        color: #1A1A1D;
        border-radius: 0.75rem;
    }
    .checkout-input:focus, select:focus, textarea:focus {
        background-color: #ffffff;
        border-color: #1A1A1D;
        outline: none;
        box-shadow: 0 0 0 2px rgba(26, 26, 29, 0.1);
    }

    /* Custom Radio/Select Highlights */
    .selected-address-card {
        border-color: #1A1A1D;
        background-color: #f9fafb;
    }
</style>
@endpush

@section('content')
{{-- Hero Section --}}
<section class="checkout-hero-bg py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <p class="text-sm uppercase tracking-[0.3em] text-gray-400 mb-4 font-bold">Secure Checkout</p>
        <h1 class="font-bebas text-5xl md:text-6xl text-white tracking-wide">CHECKOUT</h1>
        <p class="mt-4 text-gray-400 max-w-2xl mx-auto">
            Complete your order details securely.
        </p>
    </div>
</section>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 pb-20 relative z-10">
    
    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center gap-2">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl flex items-center gap-2">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl flex items-center gap-2">
            <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('orders.store') }}" method="POST">
        @csrf
        <input type="hidden" name="selected_variations" value="{{ e(old('selected_variations', json_encode(session('selected_variations', [])))) }}">
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Left Column - Shipping Info & Items --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- Shipping Address Card --}}
                <div class="checkout-card p-6 md:p-8">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center text-[#1A1A1D]">
                                <i class="fas fa-map-marker-alt text-xl"></i>
                            </div>
                            <div>
                                <h2 class="font-bold text-xl text-[#1A1A1D] uppercase tracking-wide">SHIPPING ADDRESS</h2>
                                <p class="text-xs text-gray-500 font-medium">Select your delivery destination</p>
                            </div>
                        </div>
                        <a href="{{ route('addresses.create') }}" class="checkout-secondary-btn px-4 py-2 rounded-xl font-bold text-xs transition hover:shadow-sm">
                            + New Address
                        </a>
                    </div>
                    
                    <div class="space-y-4 mb-8">
                        @if($primaryAddress)
                            <div class="border border-[#1A1A1D] bg-gray-50 rounded-2xl p-5 relative">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-[#1A1A1D]">{{ $primaryAddress->label }}</span>
                                        <span class="px-2 py-0.5 bg-[#1A1A1D] text-white text-[10px] rounded uppercase font-bold tracking-wide">Primary</span>
                                    </div>
                                    <a href="{{ route('addresses.index') }}" class="text-xs text-gray-500 hover:text-black font-semibold flex items-center gap-1 transition-colors">
                                        <i class="fas fa-edit"></i> Change
                                    </a>
                                </div>
                                <p class="text-gray-900 font-bold text-sm">{{ $primaryAddress->recipient_name }} <span class="text-gray-500 font-normal">({{ $primaryAddress->phone }})</span></p>
                                <p class="text-gray-600 text-sm mt-1 leading-relaxed">{{ $primaryAddress->full_address }}</p>
                                
                                <input type="hidden" name="user_address_id" id="user_address_id" value="{{ $primaryAddress->id }}" data-city="{{ $primaryAddress->city_id }}">
                            </div>
                        @else
                            <div class="text-center py-10 bg-gray-50 rounded-2xl border border-dashed border-gray-300">
                                <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-400">
                                    <i class="fas fa-map-marked-alt text-2xl"></i>
                                </div>
                                <p class="text-gray-500 mb-4 text-sm">No primary address set.</p>
                                <a href="{{ route('addresses.index') }}" class="inline-block px-6 py-3 checkout-primary-btn rounded-xl font-bold text-sm transition">
                                    Set Address
                                </a>
                                <input type="hidden" name="user_address_id" required>
                            </div>
                        @endif
                        @error('user_address_id')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Courier Selection --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t border-gray-100 pt-8">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">
                                Courier Service
                            </label>
                            <div class="relative">
                                <select name="courier" id="courier" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#1A1A1D] transition duration-200 appearance-none bg-white">
                                    <option value="">Select Courier</option>
                                    <option value="jne">JNE</option>
                                    <option value="pos">POS Indonesia</option>
                                    <option value="tiki">TIKI</option>
                                    <option value="sicepat">SiCepat</option>
                                    <option value="jnt">J&T</option>
                                    <option value="lion">Lion Parcel</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-500">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                            @error('courier')
                                <p class="mt-2 text-xs text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                            
                            {{-- Same City Info --}}
                            @if($primaryAddress && $primaryAddress->city_id == config('rajaongkir.origin_city'))
                                <div class="mt-3 bg-blue-50 border border-blue-100 rounded-lg p-3">
                                    <p class="text-xs text-blue-700 flex items-start gap-2">
                                        <i class="fas fa-info-circle mt-0.5"></i>
                                        <span>Same-city delivery. Some couriers might be unavailable.</span>
                                    </p>
                                </div>
                            @endif
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">
                                Delivery Service
                            </label>
                            <div class="relative">
                                <select name="shipping_service_select" id="shipping_service" disabled class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#1A1A1D] transition duration-200 bg-gray-50 appearance-none text-gray-500">
                                    <option value="">Select Courier First</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-400">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                            <div id="shipping_error" class="hidden mt-2 text-xs text-red-600 bg-red-50 p-3 rounded-lg border border-red-100"></div>
                            
                            {{-- Hidden inputs for form submission --}}
                            <input type="hidden" name="service" id="service_input">
                            <input type="hidden" name="shipping_cost" id="shipping_cost_input" value="0">
                            <input type="hidden" name="weight" value="{{ $totalWeight ?? 1000 }}">
                            
                            @error('service')
                                <p class="mt-2 text-xs text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">
                            Order Notes <span class="text-gray-400 font-normal normal-case">(Optional)</span>
                        </label>
                        <textarea name="notes" 
                                  rows="2"
                                  class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#1A1A1D] transition duration-200 resize-none"
                                  placeholder="Notes for seller, e.g. alternative colors, drop-off instructions...">{{ old('notes') }}</textarea>
                    </div>
                </div>

                {{-- Order Items Card --}}
                <div class="checkout-card p-6 md:p-8">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center text-[#1A1A1D]">
                                <i class="fas fa-shopping-bag text-xl"></i>
                            </div>
                            <div>
                                <h2 class="font-bold text-xl text-[#1A1A1D] uppercase tracking-wide">ORDER ITEMS</h2>
                                <p class="text-xs text-gray-500 font-medium">{{ $cartItems->count() }} products</p>
                            </div>
                        </div>
                        <span class="bg-[#1A1A1D] text-white px-3 py-1 rounded text-xs font-bold uppercase tracking-wider">
                            {{ $cartItems->sum('quantity') }} Items
                        </span>
                    </div>

                    <div class="space-y-6">
                        @foreach($cartItems as $item)
                            @php
                                $product = $item->variation->product;
                                $variation = $item->variation;
                            @endphp
                            
                            <article class="flex gap-4 pb-6 border-b border-gray-100 last:border-0 last:pb-0">
                                {{-- Product Image --}}
                                <div class="w-20 h-20 shrink-0 bg-[#F3F5F9] rounded-xl overflow-hidden p-2 border border-gray-100">
                                    @if($product->images && $product->images->count() > 0)
                                        <img src="{{ asset('storage/products/' . $product->images->first()->image) }}" 
                                             alt="{{ $product->name }}"
                                             class="w-full h-full object-contain mix-blend-multiply">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-300">
                                            <i class="fas fa-image text-2xl"></i>
                                        </div>
                                    @endif
                                </div>

                                {{-- Product Info --}}
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-bold text-[#1A1A1D] text-sm mb-1 truncate">{{ $product->name }}</h3>
                                    <div class="flex flex-wrap gap-2 mb-2">
                                        @if($variation->color)
                                            <span class="inline-flex items-center gap-1 border border-gray-200 px-2 py-0.5 rounded text-[10px] uppercase font-bold text-gray-600">
                                                {{ ucfirst($variation->color) }}
                                            </span>
                                        @endif
                                        @if($variation->size)
                                            <span class="inline-flex items-center gap-1 border border-gray-200 px-2 py-0.5 rounded text-[10px] uppercase font-bold text-gray-600">
                                                {{ strtoupper($variation->size) }}
                                            </span>
                                        @endif
                                        <span class="inline-flex items-center gap-1 bg-gray-100 px-2 py-0.5 rounded text-[10px] font-bold text-gray-800">
                                            x{{ $item->quantity }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Price --}}
                                <div class="text-right">
                                    <p class="font-bold text-[#1A1A1D] text-sm">
                                        Rp {{ number_format($product->price * $item->quantity, 0, ',', '.') }}
                                    </p>
                                    @if($product->point_price)
                                        <p class="text-xs text-gray-500 font-medium mt-1 flex items-center justify-end gap-1">
                                            <i class="fas fa-coins text-yellow-500 text-[10px]"></i>
                                            {{ number_format($product->point_price * $item->quantity, 0, ',', '.') }} Pts
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
                <div class="checkout-card p-6 space-y-6 sticky top-24">
                    <div class="text-center pb-4 border-b border-gray-100">
                        <h2 class="font-bebas text-3xl text-[#1A1A1D]">ORDER SUMMARY</h2>
                        <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">Total {{ $cartItems->sum('quantity') }} items</p>
                    </div>

                    <div class="space-y-4">
                        <div class="flex justify-between text-gray-600 text-sm">
                            <span>Subtotal</span>
                            <span class="font-bold text-[#1A1A1D]">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        
                        <div class="flex justify-between text-gray-600 text-sm">
                            <span class="flex items-center gap-2">
                                Shipping
                            </span>
                            <span class="font-bold text-[#1A1A1D]" id="shipping-cost-display">Rp 0</span>
                        </div>

                        {{-- PRODUCT POINTS REQUIRED --}}
                        @if($totalPointsNeeded > 0)
                            <div class="pt-4 border-t border-gray-100">
                                <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <div>
                                            <p class="text-xs font-bold text-gray-700 uppercase tracking-wide flex items-center gap-2">
                                                Points Required
                                            </p>
                                            <p class="text-[10px] text-gray-500 mt-0.5">
                                                Auto-deducted at checkout
                                            </p>
                                        </div>
                                        <p class="text-lg font-black text-[#1A1A1D]">
                                            <i class="fas fa-coins text-yellow-500 mr-1 text-sm"></i>
                                            {{ number_format($totalPointsNeeded, 0, ',', '.') }}
                                        </p>
                                    </div>
                                    
                                    @if(!$hasEnoughPoints)
                                        <div class="mt-3 bg-red-50 border border-red-100 rounded-lg p-3">
                                            <p class="text-xs font-bold text-red-600 flex items-center gap-2">
                                                <i class="fas fa-times-circle"></i>
                                                Insufficient Points!
                                            </p>
                                            <p class="text-[10px] text-red-500 mt-1">
                                                You have <strong>{{ number_format($availablePoints, 0, ',', '.') }}</strong> pts, 
                                                need <strong>{{ number_format($totalPointsNeeded, 0, ',', '.') }}</strong> pts.
                                            </p>
                                        </div>
                                    @else
                                        <div class="mt-3 bg-green-50 border border-green-100 rounded-lg p-3">
                                            <p class="text-xs font-bold text-green-700 flex items-center gap-2">
                                                <i class="fas fa-check-circle"></i>
                                                Sufficient Points
                                            </p>
                                            <p class="text-[10px] text-green-600 mt-1">
                                                Remaining: <strong>{{ number_format($availablePoints - $totalPointsNeeded, 0, ',', '.') }}</strong> pts
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <div class="pt-4 border-t-2 border-[#1A1A1D]">
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-base font-bold text-gray-900 uppercase">Total Amount</span>
                                <span class="text-2xl font-black text-[#1A1A1D]" id="total-display">
                                    Rp {{ number_format($subtotal, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        {{-- Points Reward Info --}}
                        <div class="bg-[#1A1A1D] rounded-xl p-4 text-white">
                            <p class="flex items-center text-xs font-bold uppercase tracking-wide gap-2 text-gray-300">
                                <i class="fas fa-gift"></i>
                                Points Reward
                            </p>
                            <p class="text-xl font-bold mt-1 text-white">
                                +{{ number_format($pointsWillEarn, 0, ',', '.') }} Pts
                            </p>
                            <p class="text-[10px] text-gray-400 mt-1 flex items-center gap-1">
                                <i class="fas fa-info-circle"></i>
                                Rp 10.000 = 1 Point
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
                            'w-full py-4 rounded-xl font-bold text-base shadow-lg transform transition duration-300 flex items-center justify-center gap-2',
                            'bg-gray-300 text-gray-500 cursor-not-allowed' => $disableCheckout,
                            'checkout-primary-btn hover:shadow-xl hover:-translate-y-0.5' => ! $disableCheckout,
                        ])>
                        <i class="fas fa-lock text-sm"></i>
                        @if($disableCheckout)
                            Insufficient Points
                        @else
                            PLACE ORDER
                        @endif
                    </button>

                    {{-- Back to Cart --}}
                    <a href="{{ route('cart.index') }}" 
                       class="w-full inline-flex items-center justify-center gap-2 checkout-secondary-btn font-bold text-xs py-3 rounded-xl transition uppercase tracking-wide">
                        <i class="fas fa-arrow-left"></i>
                        Back to Cart
                    </a>

                    {{-- Security Info --}}
                    <div class="space-y-2 pt-4">
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-100">
                            <div class="w-8 h-8 rounded-full bg-white border border-gray-200 flex items-center justify-center shrink-0">
                                <i class="fas fa-shield-alt text-gray-600 text-xs"></i>
                            </div>
                            <div>
                                <p class="font-bold text-xs text-gray-800 uppercase">Secure Transaction</p>
                                <p class="text-[10px] text-gray-500">Encrypted with SSL</p>
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
    const shippingErrorDiv = document.getElementById('shipping_error');
    
    const subtotal = {{ $subtotal }};
    const weight = {{ $totalWeight }};
    
    function formatRupiah(amount) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
    }
    
    function calculateShipping() {
        const courier = courierSelect.value;
        
        // Reset error state
        shippingErrorDiv.classList.add('hidden');
        shippingErrorDiv.innerText = '';
        
        if (!addressInput || !courier) {
            serviceSelect.innerHTML = '<option value="">Select Courier First</option>';
            serviceSelect.disabled = true;
            serviceSelect.classList.add('bg-gray-50', 'text-gray-500');
            serviceSelect.classList.remove('bg-white', 'text-[#1A1A1D]');
            return;
        }
        
        const cityId = addressInput.dataset.city;
        
        if (!cityId) {
            console.error('❌ City ID not found');
            serviceSelect.innerHTML = '<option value="">Invalid Address</option>';
            shippingErrorDiv.innerHTML = 'Address missing city_id.';
            shippingErrorDiv.classList.remove('hidden');
            return;
        }
        
        // Show loading
        serviceSelect.innerHTML = '<option value="">Loading Services...</option>';
        serviceSelect.disabled = true;
        
        console.log('📦 Request:', {
            destination_city_id: cityId,
            courier: courier,
            weight: weight
        });
        
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
        .then(response => {
            if (!response.ok) throw new Error(`HTTP ${response.status}`);
            return response.json();
        })
        .then(data => {
            console.log('✅ Response:', data);
            
            let costs = [];
            let courierInfo = {};
            
            // Parse response (support 2 formats)
            if (data.success && data.rajaongkir && data.rajaongkir.results && data.rajaongkir.results.length > 0) {
                const result = data.rajaongkir.results[0];
                costs = result.costs || [];
                courierInfo = { code: result.code, name: result.name };
            } else if (data.success && data.data && data.data.costs) {
                costs = data.data.costs;
                courierInfo = data.data.courier;
            }
            
            if (costs.length > 0) {
                console.log(`✅ Found ${costs.length} options`);
                
                serviceSelect.innerHTML = '<option value="">Select Service</option>';
                
                costs.forEach((cost) => {
                    const option = document.createElement('option');
                    option.value = cost.cost;
                    option.text = `${cost.service} - ${cost.description} (${formatRupiah(cost.cost)}) • ${cost.etd} Days`;
                    option.dataset.service = cost.service;
                    option.dataset.courier = courierInfo.code || courier;
                    serviceSelect.appendChild(option);
                });
                
                serviceSelect.disabled = false;
                serviceSelect.classList.remove('bg-gray-50', 'text-gray-500');
                serviceSelect.classList.add('bg-white', 'text-[#1A1A1D]');
            } else {
                console.warn('⚠️ No costs');
                serviceSelect.innerHTML = '<option value="">Not Available</option>';
                shippingErrorDiv.innerHTML = `<div class="flex items-start gap-2"><i class="fas fa-exclamation-triangle mt-0.5"></i><div><div class="font-bold">No Service</div><small>Try another courier</small></div></div>`;
                shippingErrorDiv.classList.remove('hidden');
            }
        })
        .catch(error => {
            console.error('❌ Error:', error);
            serviceSelect.innerHTML = '<option value="">Failed</option>';
            shippingErrorDiv.innerHTML = `<div class="flex items-start gap-2"><i class="fas fa-times-circle mt-0.5"></i><div><div class="font-bold">Error</div><small>${error.message}</small></div></div>`;
            shippingErrorDiv.classList.remove('hidden');
        });
    }
    
    function updateTotal() {
        const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
        
        if (!selectedOption || !selectedOption.value) {
            shippingCostDisplay.innerText = formatRupiah(0);
            totalDisplay.innerText = formatRupiah(subtotal);
            shippingCostInput.value = '';
            serviceInput.value = '';
            return;
        }

        const shippingCost = parseInt(selectedOption.value) || 0;
        const serviceName = selectedOption.dataset.service || '';
        
        const total = subtotal + shippingCost;
        
        shippingCostDisplay.innerText = formatRupiah(shippingCost);
        totalDisplay.innerText = formatRupiah(total);
        
        // ✅ UPDATE HIDDEN INPUTS
        shippingCostInput.value = shippingCost;
        serviceInput.value = serviceName;
        
        console.log('✅ Updated hidden inputs:', {
            shipping_cost: shippingCostInput.value,
            service: serviceInput.value,
            courier: courierSelect.value
        });
    }
    
    // Event Listeners
    courierSelect.addEventListener('change', function() {
        serviceSelect.innerHTML = '<option value="">Select Courier First</option>';
        serviceSelect.disabled = true;
        serviceSelect.classList.add('bg-gray-50', 'text-gray-500');
        serviceSelect.classList.remove('bg-white', 'text-[#1A1A1D]');
        
        shippingCostDisplay.innerText = formatRupiah(0);
        totalDisplay.innerText = formatRupiah(subtotal);
        shippingCostInput.value = '';
        serviceInput.value = '';
        
        if (this.value) {
            calculateShipping();
        }
    });
    
    serviceSelect.addEventListener('change', updateTotal);
    
    // ✅ FORM VALIDATION & DEBUG
    const checkoutForm = document.querySelector('form');
    checkoutForm.addEventListener('submit', function(e) {
        // Get ALL form data untuk debug
        const formData = new FormData(this);
        const formObject = {};
        formData.forEach((value, key) => {
            formObject[key] = value;
        });
        
        console.log('🚀 ACTUAL FORM DATA BEING SUBMITTED:', formObject);
        
        // Validation
        const courier = formData.get('courier');
        const service = formData.get('service');
        const shippingCost = formData.get('shipping_cost');
        const userAddressId = formData.get('user_address_id');
        const weight = formData.get('weight');
        
        if (!userAddressId) {
            e.preventDefault();
            alert('Shipping address is required!');
            return false;
        }
        
        if (!courier) {
            e.preventDefault();
            alert('Please select a courier!');
            courierSelect.focus();
            return false;
        }
        
        if (!service || !shippingCost || shippingCost == '0' || shippingCost == '') {
            e.preventDefault();
            alert('Please select a shipping service!');
            serviceSelect.focus();
            return false;
        }
        
        console.log('✅ VALIDATION PASSED');
        console.log('📦 Shipping Data:', {
            user_address_id: userAddressId,
            courier: courier,
            service: service,
            shipping_cost: shippingCost,
            weight: weight
        });
        
        // Form will submit normally
        return true;
    });
});
</script>
@endpush