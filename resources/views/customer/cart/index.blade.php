@extends('customer.layouts.app')

@section('title', 'Cart')

@push('styles')
<style>
    [x-cloak] { display: none !important; }
    .cart-hero-bg { background-color: #FAD470; }
    .cart-primary-btn { background-color: #000; color: #FAD471; }
    .cart-primary-btn:hover { background-color: #333; }
    .cart-secondary-btn { background-color: #FAD470; color: #000; }
    .cart-secondary-btn:hover { background-color: #F59E0B; }
    .cart-card { 
        background: #fff; 
        border: 2px solid #FAD470;
        border-radius: 1.5rem;
    }
    .cart-checkbox:checked {
        background-color: #FAD470;
        border-color: #FAD470;
    }
</style>
@endpush

@section('content')
{{-- Hero Section --}}
<section class="cart-hero-bg py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <p class="text-sm uppercase tracking-[0.3em] text-black/60 mb-4">Your Shopping Cart</p>
        <h1 class="font-bebas text-5xl md:text-6xl text-black">KERANJANG BELANJA</h1>
        <p class="mt-4 text-black/70 max-w-2xl mx-auto">
            Periksa dan atur item yang ingin Anda beli. Update jumlah atau hapus produk sebelum checkout.
        </p>
    </div>
</section>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 pb-20 relative z-10">
    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="mb-4"><x-alert type="success" :message="session('success')" /></div>
    @endif
    @if(session('error'))
        <div class="mb-4"><x-alert type="error" :message="session('error')" /></div>
    @endif
    @if($errors->any())
        <div class="mb-4"><x-alert type="error" :message="$errors->first()" /></div>
    @endif

    @if($cartItems->isEmpty())
        <div class="cart-card shadow-xl p-12 text-center">
            <div class="flex flex-col items-center space-y-6">
                <div class="w-24 h-24 rounded-full bg-[#FAD470]/30 flex items-center justify-center">
                    <i class="fas fa-cart-arrow-down text-4xl text-black"></i>
                </div>
                <h2 class="font-bebas text-3xl text-gray-900">KERANJANG MASIH KOSONG</h2>
                <p class="text-gray-600 max-w-md">
                    Jelajahi katalog kami lalu tambahkan produk favorit Anda. Semua barang yang dimasukkan akan tampil di sini.
                </p>
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 cart-primary-btn px-8 py-4 rounded-full font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition">
                    <i class="fas fa-store"></i> Mulai Belanja
                </a>
            </div>
        </div>
    @else
        {{-- Form khusus kirim selected produk ke session dulu --}}
        <form id="select-products-form" method="POST" action="{{ route('checkout.select-products') }}" class="hidden">
            @csrf
            <input type="hidden" name="selected_variations" id="selected-variations" value="">
        </form>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                {{-- Select All --}}
                <div class="cart-card shadow-lg p-4 flex items-center justify-between">
                    <label class="inline-flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" id="select-all" class="w-5 h-5 rounded border-2 border-[#FAD470] text-[#FAD470] focus:ring-[#FAD470] cart-checkbox">
                        <span class="text-sm font-semibold text-gray-700">Pilih Semua Produk</span>
                    </label>
                    <span class="text-xs text-gray-500 hidden md:block">Centang produk yang ingin di-checkout</span>
                </div>

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

                    <article class="cart-card shadow-lg p-6" aria-label="Cart item">
                        <div class="flex flex-col md:flex-row gap-6">
                            {{-- Checkbox + Image --}}
                            <div class="flex md:flex-col items-start gap-3 md:w-32 w-full">
                                <input type="checkbox"
                                       class="mt-1 md:mt-0 w-5 h-5 rounded border-2 border-[#FAD470] text-[#FAD470] focus:ring-[#FAD470] item-checkbox cart-checkbox"
                                       data-line-price="{{ $linePrice }}"
                                       data-line-point="{{ $linePointPrice }}"
                                       data-variation-id="{{ $variation->id }}">
                                <div class="flex-1 md:w-full">
                                    @if($productImage)
                                        <img src="{{ $productImage }}" alt="{{ $productName }}" class="w-full h-32 object-cover rounded-2xl border-2 border-gray-100">
                                    @else
                                        <div class="w-full h-32 bg-gray-100 rounded-2xl flex items-center justify-center text-gray-400">
                                            <i class="fas fa-image text-3xl"></i>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="flex-1 space-y-3">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900">{{ $productName }}</h3>
                                        <p class="text-sm text-gray-500 flex flex-wrap gap-2 mt-1">
                                            @if($variation)
                                                @if($variation->color)
                                                    <span class="inline-flex items-center gap-1 bg-gray-100 px-2 py-1 rounded-full text-xs">
                                                        <i class="fas fa-palette text-[#FAD470]"></i>{{ ucfirst($variation->color) }}
                                                    </span>
                                                @endif
                                                @if($variation->size)
                                                    <span class="inline-flex items-center gap-1 bg-gray-100 px-2 py-1 rounded-full text-xs">
                                                        <i class="fas fa-ruler text-[#FAD470]"></i>{{ strtoupper($variation->size) }}
                                                    </span>
                                                @endif
                                                <span class="inline-flex items-center gap-1 bg-gray-100 px-2 py-1 rounded-full text-xs">
                                                    <i class="fas fa-boxes text-[#FAD470]"></i>Stok: {{ $variation->stock ?? 0 }}
                                                </span>
                                            @else
                                                <span class="text-red-500">Variasi tidak ditemukan</span>
                                            @endif
                                        </p>
                                    </div>
                                    <form action="{{ route('cart.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus item ini dari keranjang?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-10 h-10 rounded-full bg-red-50 text-red-500 hover:bg-red-100 flex items-center justify-center transition" aria-label="Remove item">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>

                                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 pt-4 border-t border-gray-100">
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase tracking-wide">Harga Satuan</p>
                                        <p class="text-lg font-bold text-black">Rp {{ number_format($unitPrice, 0, ',', '.') }}</p>
                                        @if($unitPointPrice)
                                            <p class="text-sm text-amber-600 font-medium">
                                                <i class="fas fa-star text-xs"></i> {{ number_format($unitPointPrice, 0, ',', '.') }} poin
                                            </p>
                                        @endif
                                    </div>

                                    <div class="flex items-center gap-3">
                                        <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex items-center gap-2">
                                            @csrf
                                            @method('PUT')
                                            <button type="button" class="w-10 h-10 rounded-full bg-[#FAD470] flex items-center justify-center text-black font-bold hover:bg-[#F59E0B] transition" data-quantity-decrease>
                                                <span class="sr-only">Kurangi</span>-
                                            </button>
                                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="w-16 text-center border-2 border-[#FAD470] rounded-xl py-2 font-semibold focus:ring-2 focus:ring-[#FAD470] focus:border-[#FAD470]" data-quantity-input>
                                            <button type="button" class="w-10 h-10 rounded-full bg-[#FAD470] flex items-center justify-center text-black font-bold hover:bg-[#F59E0B] transition" data-quantity-increase>
                                                <span class="sr-only">Tambah</span>+
                                            </button>
                                            <button type="submit" class="hidden" aria-hidden="true">Update</button>
                                        </form>
                                    </div>

                                    <div class="text-right">
                                        <p class="text-xs text-gray-500 uppercase tracking-wide">Subtotal</p>
                                        <p class="text-xl font-bold text-black">Rp {{ number_format($linePrice, 0, ',', '.') }}</p>
                                        @if($linePointPrice)
                                            <p class="text-sm text-amber-600 font-medium">
                                                <i class="fas fa-star text-xs"></i> {{ number_format($linePointPrice, 0, ',', '.') }} poin
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <aside class="lg:col-span-1">
                <div class="cart-card shadow-xl p-8 space-y-6 sticky top-24">
                    <div class="text-center pb-4 border-b-2 border-[#FAD470]">
                        <h2 class="font-bebas text-3xl text-black">RINGKASAN</h2>
                        <p class="text-sm text-gray-500">{{ $cartItems->sum('quantity') }} barang dari {{ $cartItems->count() }} produk</p>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Total Harga</span>
                            <span class="font-bold text-xl text-black" id="selected-total-price">Rp 0</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600 flex items-center gap-2">
                                <i class="fas fa-star text-amber-500"></i> Total Poin
                            </span>
                            <span class="font-bold text-amber-600" id="selected-total-point">0 poin</span>
                        </div>
                    </div>

                    <div class="pt-4 border-t-2 border-[#FAD470] text-center">
                        <p class="text-xs text-gray-400 mb-4">Total mengikuti produk yang Anda centang</p>
                    </div>

                    <div class="space-y-3">
                        <button id="checkout-btn" type="button" class="w-full flex items-center justify-center gap-2 cart-primary-btn font-bold py-4 rounded-full shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition">
                            <i class="fas fa-credit-card"></i> <span>Lanjutkan Pembayaran</span>
                        </button>

                        <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Kosongkan seluruh keranjang?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full border-2 border-red-200 text-red-500 font-medium py-3 rounded-full hover:border-red-400 hover:bg-red-50 transition">
                                <i class="fas fa-trash-alt mr-2"></i> Kosongkan Keranjang
                            </button>
                        </form>

                        <a href="{{ route('home') }}" class="w-full flex items-center justify-center gap-2 cart-secondary-btn font-semibold py-3 rounded-full transition">
                            <i class="fas fa-arrow-left"></i> Lanjut Belanja
                        </a>
                    </div>
                </div>
            </aside>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
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

    const selectAllCheckbox = document.getElementById('select-all');
    const itemCheckboxes = document.querySelectorAll('.item-checkbox');
    const totalPriceEl = document.getElementById('selected-total-price');
    const totalPointEl = document.getElementById('selected-total-point');

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
        totalPriceEl.textContent = 'Rp ' + formatRupiah(totalPrice);
        totalPointEl.textContent = formatRupiah(totalPoint) + ' poin';
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

    // Tangani klik tombol lanjutkan pembayaran
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
