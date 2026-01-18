@extends('customer.layouts.app')

@section('title', 'Cart')

@push('styles')
<style>
    [x-cloak] { display: none !important; }
    
    /* Background Header (Sama seperti file baru - warna krem) */
    .header-bg {
        background-color: #E5DECC;
    }
    
    /* Checkbox Style - Hitam seperti file baru */
    .cart-checkbox {
        appearance: none;
        width: 22px;
        height: 22px;
        border: 2px solid #D1D5DB;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .cart-checkbox:checked {
        background-color: #000;
        border-color: #000;
    }
    .cart-checkbox:checked::after {
        content: '✓';
        color: #fff;
        font-size: 14px;
        font-weight: bold;
    }
    
    /* Quantity Button - Style dari file baru */
    .qty-btn {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        border: 1px solid #E5E7EB;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    .qty-btn:hover {
        background: #000;
        color: #fff;
        border-color: #000;
    }
</style>
@endpush

@section('content')
{{-- Hero Section - Style dari file baru --}}
<section class="header-bg bg-[#1A1A1D]">
    <div class="py-20 px-6 md:px-12">
        <p class="text-sm md:text-base text-white mb-2">
            Home / Shopping Cart
        </p>
        <h1 class="text-4xl md:text-5xl font-bold mb-4 text-left text-white">
            KERANJANG BELANJA
        </h1>
        <p class="text-lg md:text-xl mb-8 text-white">
            Periksa dan atur item yang ingin Anda beli. Update jumlah atau hapus produk sebelum checkout.
        </p>
    </div>
</section>

<section class="bg-white py-12 px-6 md:px-12">
    <div class="max-w-7xl mx-auto">
        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="mb-6"><x-alert type="success" :message="session('success')" /></div>
        @endif
        @if(session('error'))
            <div class="mb-6"><x-alert type="error" :message="session('error')" /></div>
        @endif
        @if($errors->any())
            <div class="mb-6"><x-alert type="error" :message="$errors->first()" /></div>
        @endif

        @if($cartItems->isEmpty())
            <div class="text-center py-16">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-cart-arrow-down text-4xl text-gray-300"></i>
                </div>
                <h3 class="text-2xl font-bold text-black mb-2">KERANJANG MASIH KOSONG</h3>
                <p class="text-gray-500 mb-8 max-w-md mx-auto">
                    Jelajahi katalog kami lalu tambahkan produk favorit Anda. Semua barang yang dimasukkan akan tampil di sini.
                </p>
                <a href="{{ route('home') }}" class="inline-block bg-black text-white px-8 py-3.5 rounded-lg font-bold hover:bg-gray-800 transition-all shadow-lg hover:shadow-xl">
                    <i class="fas fa-store mr-2"></i> Mulai Belanja
                </a>
            </div>
        @else
            {{-- Form khusus kirim selected produk ke session dulu --}}
            <form id="select-products-form" method="POST" action="{{ route('checkout.select-products') }}" class="hidden">
                @csrf
                <input type="hidden" name="selected_variations" id="selected-variations" value="">
            </form>

            <div class="flex flex-col lg:flex-row gap-8 items-start">
                {{-- LEFT COLUMN: Cart Items --}}
                <div class="flex-1 w-full space-y-6">
                    {{-- Select All Header --}}
                    <div class="bg-white rounded-xl p-6 border border-gray-200 flex items-center gap-4 shadow-sm">
                        <input type="checkbox" id="select-all" class="cart-checkbox">
                        <label for="select-all" class="font-bold text-gray-900 cursor-pointer select-none">
                            Pilih Semua Produk
                        </label>
                    </div>

                    {{-- Loop Items --}}
                    @foreach($cartItems as $item)
                        @php
                            $variation = $item->variation;
                            $product = $variation->product ?? null;
                            $productName = $product->name ?? 'Produk tidak tersedia';
                            $unitPrice = $product->price ?? 0;
                            $unitPointPrice = $product->point_price ?? 0;
                            $linePrice = $unitPrice * $item->quantity;
                            $linePointPrice = $unitPointPrice ? $unitPointPrice * $item->quantity : 0;
                            $productImage = $product && $product->images && $product->images->count() > 0 ? asset('storage/products/' . $product->images->first()->image) : null;
                        @endphp

                        <div class="bg-white rounded-xl p-6 border border-gray-200 hover:border-black transition-all duration-300 shadow-sm hover:shadow-md flex flex-col md:flex-row gap-6 items-start md:items-center group">
                            {{-- Checkbox --}}
                            <div class="shrink-0 pt-2 md:pt-0">
                                <input type="checkbox"
                                       class="cart-checkbox item-checkbox"
                                       data-line-price="{{ $linePrice }}"
                                       data-line-point="{{ $linePointPrice }}"
                                       data-variation-id="{{ $variation->id }}">
                            </div>

                            {{-- Image --}}
                            <div class="w-24 h-24 md:w-32 md:h-32 shrink-0 bg-gray-50 rounded-lg overflow-hidden relative border border-gray-100">
                                @if($productImage)
                                    <img src="{{ $productImage }}" alt="{{ $productName }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-300">
                                        <i class="fas fa-image text-2xl"></i>
                                    </div>
                                @endif
                            </div>

                            {{-- Details --}}
                            <div class="flex-1 min-w-0 w-full">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <h3 class="text-lg font-bold text-black line-clamp-2 leading-tight mb-1">
                                            {{ $productName }}
                                        </h3>
                                        @if($variation)
                                            <div class="flex flex-wrap gap-2 mt-1">
                                                @if($variation->color)
                                                    <span class="text-sm text-gray-500 font-medium bg-gray-100 px-2 py-0.5 rounded-md inline-block">
                                                        <i class="fas fa-palette text-gray-400 text-xs"></i> {{ ucfirst($variation->color) }}
                                                    </span>
                                                @endif
                                                @if($variation->size)
                                                    <span class="text-sm text-gray-500 font-medium bg-gray-100 px-2 py-0.5 rounded-md inline-block">
                                                        <i class="fas fa-ruler text-gray-400 text-xs"></i> {{ strtoupper($variation->size) }}
                                                    </span>
                                                @endif
                                                <span class="text-sm text-gray-500 font-medium bg-gray-100 px-2 py-0.5 rounded-md inline-block">
                                                    <i class="fas fa-boxes text-gray-400 text-xs"></i> Stok: {{ $variation->stock ?? 0 }}
                                                </span>
                                            </div>
                                        @else
                                            <span class="text-red-500 text-sm">Variasi tidak ditemukan</span>
                                        @endif
                                    </div>

                                    {{-- Remove Button (Mobile) --}}
                                    <form action="{{ route('cart.destroy', $item->id) }}" method="POST" class="md:hidden" onsubmit="return confirm('Hapus item ini dari keranjang?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-500 transition p-2">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>

                                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mt-4 pt-4 border-t border-gray-100">
                                    {{-- Price --}}
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase">Harga Satuan</p>
                                        @if($unitPrice > 0)
                                            <div class="font-black text-xl text-black">
                                                Rp {{ number_format($unitPrice, 0, ',', '.') }}
                                            </div>
                                        @endif
                                        @if($unitPointPrice)
                                            <p class="text-xl text-black-600 font-black mt-1">
                                                <i class="fas fa-star text-xl"></i> {{ number_format($unitPointPrice, 0, ',', '.') }} poin
                                            </p>
                                        @endif
                                    </div>

                                    <div class="flex items-center gap-6 w-full md:w-auto justify-between md:justify-end">
                                        {{-- Quantity Control --}}
                                        <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex items-center gap-3 bg-gray-50 rounded-full px-2 py-1 border border-gray-200">
                                            @csrf @method('PUT')
                                            <button type="button" class="qty-btn border-none bg-transparent hover:bg-gray-200 hover:text-black" data-quantity-decrease>
                                                <i class="fas fa-minus text-[10px]"></i>
                                            </button>
                                            <input type="number" 
                                                   name="quantity" 
                                                   value="{{ $item->quantity }}" 
                                                   min="1"
                                                   class="w-8 text-center bg-transparent border-none p-0 font-bold text-black focus:ring-0" 
                                                   data-quantity-input>
                                            <button type="button" class="qty-btn border-none bg-transparent hover:bg-gray-200 hover:text-black" data-quantity-increase>
                                                <i class="fas fa-plus text-[10px]"></i>
                                            </button>
                                            <button type="submit" class="hidden">Update</button>
                                        </form>

                                        {{-- Remove Button (Desktop) --}}
                                        <form action="{{ route('cart.destroy', $item->id) }}" method="POST" class="hidden md:block" onsubmit="return confirm('Hapus item ini dari keranjang?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="w-10 h-10 rounded-full border border-gray-200 flex items-center justify-center text-gray-400 hover:bg-black hover:text-white hover:border-black transition-all" title="Remove Item">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>

                                    {{-- Subtotal --}}
                                    <div class="text-right">
                                        <p class="text-xs text-gray-500 uppercase">Subtotal</p>
                                        @if($linePrice > 0)
                                            <p class="text-xl font-bold text-black">Rp {{ number_format($linePrice, 0, ',', '.') }}</p>
                                        @endif
                                        @if($linePointPrice)
                                            <p class="text-xl text-black-600 font-bold mt-1">
                                                <i class="fas fa-star text-xl"></i> {{ number_format($linePointPrice, 0, ',', '.') }} poin
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- RIGHT COLUMN: Summary --}}
                <div class="w-full lg:w-[400px] shrink-0">
                    <div class="bg-white rounded-xl p-8 border border-gray-200 shadow-lg sticky top-24">
                        <h3 class="text-2xl font-bold text-black mb-2">RINGKASAN</h3>
                        <p class="text-sm text-gray-500 mb-6">{{ $cartItems->sum('quantity') }} barang dari {{ $cartItems->count() }} produk</p>

                        <div class="space-y-4 mb-6">
                            <div class="flex justify-between items-center text-gray-600">
                                <span>Total Harga</span>
                                <span class="font-bold text-xl text-black" id="selected-total-price">Rp 0</span>
                            </div>
                            <div class="flex justify-between items-center text-gray-600 pb-4 border-b border-gray-100">
                                <span class="flex items-center gap-2">
                                    <i class="fas fa-star text-black-500"></i> Total Poin
                                </span>
                                <span class="font-bold text-black-600" id="selected-total-point">0 poin</span>
                            </div>
                        </div>

                        {{-- Points Reward Info --}}
                        <div class="bg-black-100 rounded-xl p-4 border-2 border-black-200 mb-6">
                            <p class="flex items-center text-sm font-bold text-black gap-2">
                                <i class="fas fa-gift text-black-500"></i> Reward Poin dari Pesanan Ini
                            </p>
                            <p class="text-2xl font-bold text-black-600 mt-1" id="reward-points-display">
                                <i class="fas fa-star text-black-400"></i> 0 Poin
                            </p>
                            <p class="text-xs text-gray-600 mt-2 flex items-center gap-1">
                                <i class="fas fa-info-circle"></i> Rp 10.000 = 1 Poin
                            </p>
                        </div>

                        <div class="text-center mb-6">
                            <p class="text-xs text-gray-400">Total mengikuti produk yang Anda centang</p>
                        </div>

                        <div class="space-y-3">
                            <button type="button" id="checkout-btn" class="block w-full bg-black text-white font-bold py-4 rounded-lg hover:bg-gray-800 active:scale-95 transition-all duration-200 text-center shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                                <i class="fas fa-credit-card"></i> Lanjutkan Pembayaran
                            </button>

                            <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Kosongkan seluruh keranjang?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-full border-2 border-red-200 text-red-500 font-medium py-3 rounded-lg hover:border-red-400 hover:bg-red-50 transition">
                                    <i class="fas fa-trash-alt mr-2"></i> Kosongkan Keranjang
                                </button>
                            </form>

                            <a href="{{ route('home') }}" class="block text-center text-sm font-bold text-gray-500 hover:text-black transition decoration-2 hover:underline underline-offset-4 py-2">
                                <i class="fas fa-arrow-left mr-2"></i> Lanjut Belanja
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Quantity update logic
    const carts = document.querySelectorAll('[data-quantity-input]');
    carts.forEach(function (input) {
        const form = input.closest('form');
        const decreaseBtn = form.querySelector('[data-quantity-decrease]');
        const increaseBtn = form.querySelector('[data-quantity-increase]');
        const submitUpdate = () => form.requestSubmit();

        if (decreaseBtn) {
            decreaseBtn.addEventListener('click', () => {
                const current = parseInt(input.value, 10) || 1;
                if (current > 1) {
                    input.value = current - 1;
                    submitUpdate();
                }
            });
        }

        if (increaseBtn) {
            increaseBtn.addEventListener('click', () => {
                const current = parseInt(input.value, 10) || 1;
                input.value = current + 1;
                submitUpdate();
            });
        }

        input.addEventListener('change', submitUpdate);
    });

    // Checkbox and summary logic
    const selectAllCheckbox = document.getElementById('select-all');
    const itemCheckboxes = document.querySelectorAll('.item-checkbox');
    const totalPriceEl = document.getElementById('selected-total-price');
    const totalPointEl = document.getElementById('selected-total-point');
    const rewardPointsEl = document.getElementById('reward-points-display');

    function formatRupiah(number) {
        return new Intl.NumberFormat('id-ID').format(number);
    }

    function recalcSelectedTotals() {
        let totalPrice = 0;
        let totalPoint = 0;
        itemCheckboxes.forEach(cb => {
            if (cb.checked) {
                const linePrice = parseInt(cb.getAttribute('data-line-price')) || 0;
                const linePoint = parseInt(cb.getAttribute('data-line-point')) || 0;
                totalPrice += linePrice;
                totalPoint += linePoint;
            }
        });
        
        // Update total harga dan poin
        totalPriceEl.textContent = 'Rp ' + formatRupiah(totalPrice);
        totalPointEl.textContent = formatRupiah(totalPoint) + ' poin';
        
        // Hitung reward points (1 poin per Rp 10.000)
        const rewardPoints = Math.floor(totalPrice / 10000);
        rewardPointsEl.innerHTML = '<i class="fas fa-star text-black-400"></i> ' + formatRupiah(rewardPoints) + ' Poin';
    }

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function () {
            const checked = this.checked;
            itemCheckboxes.forEach(cb => { cb.checked = checked; });
            recalcSelectedTotals();
        });
    }
    
    itemCheckboxes.forEach(cb => {
        cb.addEventListener('change', function () {
            const allChecked = Array.from(itemCheckboxes).every(c => c.checked);
            const noneChecked = Array.from(itemCheckboxes).every(c => !c.checked);
            if (allChecked) {
                selectAllCheckbox.checked = true;
                selectAllCheckbox.indeterminate = false;
            } else if (noneChecked) {
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = false;
            } else {
                selectAllCheckbox.indeterminate = true;
            }
            recalcSelectedTotals();
        });
    });

    // Default tidak pilih semua saat load
    if (itemCheckboxes.length > 0) {
        selectAllCheckbox.checked = false;
        itemCheckboxes.forEach(cb => cb.checked = false);
        recalcSelectedTotals();
    }

    // Checkout button handler
    const selectProductsForm = document.getElementById('select-products-form');
    const selectedVariationsInput = document.getElementById('selected-variations');

    document.getElementById('checkout-btn').addEventListener('click', function () {
        let selectedIds = [];
        itemCheckboxes.forEach(cb => {
            if (cb.checked) {
                const variationId = cb.getAttribute('data-variation-id');
                if (variationId) selectedIds.push(variationId);
            }
        });

        if (selectedIds.length === 0) {
            alert('Pilih minimal satu produk untuk melanjutkan pembayaran.');
            return;
        }

        selectedVariationsInput.value = JSON.stringify(selectedIds);
        selectProductsForm.submit();
    });
});
</script>
@endpush
