@extends('customer.layouts.app')

@section('title', 'Cart')

@push('styles')
<style>
    [x-cloak] { display: none !important; }
    .cart-hero-gradient {
        background: linear-gradient(90deg, #9333ea, #ec4899, #ef4444);
    }
    .cart-empty-icon {
        background: linear-gradient(135deg, #ede9fe, #fdf2f8);
    }
    .cart-placeholder-img {
        background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
    }
    .cart-primary-gradient {
        background: linear-gradient(90deg, #9333ea, #ec4899);
    }
    .cart-subtle-gradient {
        background: linear-gradient(135deg, #ede9fe, #f9a8d4);
    }
</style>
@endpush

@section('content')
<section class="cart-hero-gradient text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <p class="text-sm uppercase tracking-[0.3em] text-white/80 mb-4">Your Shopping Cart</p>
        <h1 class="text-3xl md:text-4xl font-bold">Review &amp; fine-tune the items you love</h1>
        <p class="mt-4 text-white/80 max-w-2xl mx-auto">
            Update quantities, remove items, or convert your purchases into reward points before checking out.
        </p>
    </div>
</section>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-12 pb-20 relative z-10">
    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="mb-4">
            <x-alert type="success" :message="session('success')" />
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4">
            <x-alert type="error" :message="session('error')" />
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4">
            <x-alert type="error" :message="$errors->first()" />
        </div>
    @endif

    @if($cartItems->isEmpty())
        <div class="bg-white rounded-3xl shadow-xl p-12 text-center">
            <div class="flex flex-col items-center space-y-6">
                <div class="w-24 h-24 rounded-full cart-empty-icon flex items-center justify-center">
                    <i class="fas fa-cart-arrow-down text-4xl text-purple-500"></i>
                </div>
                <h2 class="text-2xl font-semibold text-gray-900">Keranjang masih kosong</h2>
                <p class="text-gray-600 max-w-md">
                    Jelajahi katalog kami lalu tambahkan produk favorit Anda. Semua barang yang dimasukkan akan tampil di sini.
                </p>
                <a href="{{ route('home') }}"
                   class="inline-flex items-center gap-2 cart-primary-gradient text-white px-6 py-3 rounded-full font-semibold shadow-lg hover:shadow-2xl transform hover:-translate-y-0.5 transition">
                    <i class="fas fa-store"></i>
                    Mulai Belanja
                </a>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                {{-- Select All --}}
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-4 flex items-center justify-between">
                    <label class="inline-flex items-center gap-3">
                        <input type="checkbox" id="select-all"
                               class="w-5 h-5 rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                        <span class="text-sm font-semibold text-gray-700">Pilih Semua Produk</span>
                    </label>
                    <span class="text-xs text-gray-500">
                        Centang produk yang ingin dihitung dan di-checkout
                    </span>
                </div>

                @foreach($cartItems as $item)
                    @php
                        $variation      = $item->variation;
                        $product        = $variation->product ?? null;
                        $productName    = $product->name ?? 'Produk tidak tersedia';
                        $unitPrice      = $product->price ?? 0;
                        $unitPointPrice = $product->point_price ?? 0;
                        $linePrice      = $unitPrice * $item->quantity;
                        $linePointPrice = $unitPointPrice ? $unitPointPrice * $item->quantity : 0;
                        $productImage   = null;

                        if ($product && $product->images && $product->images->count() > 0) {
                            $productImage = asset('storage/products/' . $product->images->first()->image);
                        }
                    @endphp

                    <article class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 flex flex-col gap-4" aria-label="Cart item">
                        <div class="flex flex-col md:flex-row gap-6">
                            {{-- Checkbox + Image --}}
                            <div class="flex md:flex-col items-start gap-3 md:w-32 w-full">
                                <input type="checkbox"
                                       class="mt-1 md:mt-0 w-5 h-5 rounded border-gray-300 text-purple-600 focus:ring-purple-500 item-checkbox"
                                       data-line-price="{{ $linePrice }}"
                                       data-line-point="{{ $linePointPrice }}">
                                <div class="flex-1 md:w-full">
                                    @if($productImage)
                                        <img src="{{ $productImage }}"
                                             alt="{{ $productName }}"
                                             class="w-full h-32 object-cover rounded-xl">
                                    @else
                                        <div class="w-full h-32 cart-placeholder-img rounded-xl flex items-center justify-center text-gray-400">
                                            <i class="fas fa-image text-3xl"></i>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="flex-1 space-y-3">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $productName }}</h3>
                                        <p class="text-sm text-gray-500 flex flex-wrap gap-2 mt-1">
                                            @if($variation)
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
                                                    <i class="fas fa-boxes text-xs text-purple-500"></i>
                                                    Stok: {{ $variation->stock ?? 0 }}
                                                </span>
                                            @else
                                                <span class="text-red-500">Variasi tidak ditemukan</span>
                                            @endif
                                        </p>
                                    </div>
                                    <form action="{{ route('cart.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus item ini dari keranjang?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-gray-400 hover:text-red-500 transition"
                                                aria-label="Remove item">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>

                                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                    <div>
                                        <p class="text-sm text-gray-500">Harga satuan</p>
                                        <p class="text-lg font-semibold text-purple-600">
                                            Rp {{ number_format($unitPrice, 0, ',', '.') }}
                                        </p>
                                        @if($unitPointPrice)
                                            <p class="text-sm text-amber-600">
                                                <i class="fas fa-star text-xs"></i>
                                                {{ number_format($unitPointPrice, 0, ',', '.') }} poin
                                            </p>
                                        @endif
                                    </div>

                                    <div class="flex items-center gap-3">
                                        <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex items-center gap-2">
                                            @csrf
                                            @method('PUT')
                                            <button type="button" class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center text-gray-600 hover:border-purple-500 hover:text-purple-600" data-quantity-decrease>
                                                <span class="sr-only">Kurangi</span>
                                                -
                                            </button>
                                            <input type="number"
                                                   name="quantity"
                                                   value="{{ $item->quantity }}"
                                                   min="1"
                                                   class="w-16 text-center border border-gray-200 rounded-lg py-1 focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                                   data-quantity-input>
                                            <button type="button" class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center text-gray-600 hover:border-purple-500 hover:text-purple-600" data-quantity-increase>
                                                <span class="sr-only">Tambah</span>
                                                +
                                            </button>
                                            <button type="submit" class="hidden" aria-hidden="true">Update</button>
                                        </form>
                                    </div>

                                    <div class="text-right">
                                        <p class="text-sm text-gray-500">Subtotal</p>
                                        <p class="text-xl font-bold text-gray-900">
                                            Rp {{ number_format($linePrice, 0, ',', '.') }}
                                        </p>
                                        @if($linePointPrice)
                                            <p class="text-sm text-amber-600">
                                                <i class="fas fa-star text-xs"></i>
                                                {{ number_format($linePointPrice, 0, ',', '.') }} poin
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
                <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8 space-y-6">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-900">Ringkasan</h2>
                        <p class="text-sm text-gray-500">
                            {{ $cartItems->sum('quantity') }} barang dari {{ $cartItems->count() }} produk
                        </p>
                        <p class="text-xs text-gray-400 mt-1">
                            Total di bawah mengikuti produk yang Anda centang.
                        </p>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between text-gray-600">
                            <span>Total Harga</span>
                            <span class="font-semibold text-gray-900" id="selected-total-price">
                                Rp 0
                            </span>
                        </div>
                        <div class="flex items-center justify-between text-amber-600">
                            <span>Total Poin</span>
                            <span class="font-semibold" id="selected-total-point">
                                0 poin
                            </span>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Kosongkan seluruh keranjang?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full border border-gray-200 text-gray-600 font-medium py-3 rounded-full hover:border-red-400 hover:text-red-500 transition">
                                <i class="fas fa-trash-alt mr-2"></i>
                                Kosongkan Keranjang
                            </button>
                        </form>

                        <a href="{{ route('home') }}" class="w-full inline-flex items-center justify-center gap-2 border border-purple-100 text-purple-600 font-medium py-3 rounded-full hover:bg-purple-50 transition">
                            <i class="fas fa-arrow-left"></i>
                            Lanjut Belanja
                        </a>

                        <a href="{{ route('checkout') }}"
                        class="w-full flex items-center justify-center gap-2 cart-primary-gradient text-white font-semibold py-3 rounded-full shadow-lg hover:shadow-2xl transform hover:-translate-y-0.5 transition">
                            <i class="fas fa-credit-card"></i>
                            <span>Lanjutkan Pembayaran</span>
                        </a>
                        <p class="text-xs text-gray-500 text-center">
                            Hanya produk yang Anda centang yang akan dihitung di ringkasan ini, namun saat ini checkout masih global untuk semua item.
                        </p>
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
        // Quantity update (sudah ada)
        const carts = document.querySelectorAll('[data-quantity-input]');
        carts.forEach(function (input) {
            const form = input.closest('form');
            const decreaseBtn = form.querySelector('[data-quantity-decrease]');
            const increaseBtn = form.querySelector('[data-quantity-increase]');

            const submitUpdate = () => {
                form.requestSubmit();
            };

            if (decreaseBtn) {
                decreaseBtn.addEventListener('click', function () {
                    const current = parseInt(input.value, 10) || 1;
                    if (current > 1) {
                        input.value = current - 1;
                        submitUpdate();
                    }
                });
            }

            if (increaseBtn) {
                increaseBtn.addEventListener('click', function () {
                    const current = parseInt(input.value, 10) || 1;
                    input.value = current + 1;
                    submitUpdate();
                });
            }

            input.addEventListener('change', submitUpdate);
        });

        // Checkbox + select all + total selected
        const selectAllCheckbox = document.getElementById('select-all');
        const itemCheckboxes   = document.querySelectorAll('.item-checkbox');
        const totalPriceEl     = document.getElementById('selected-total-price');
        const totalPointEl     = document.getElementById('selected-total-point');

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
                itemCheckboxes.forEach(cb => {
                    cb.checked = checked;
                });
                recalcSelectedTotals();
            });
        }

        itemCheckboxes.forEach(cb => {
            cb.addEventListener('change', function () {
                // Sync select-all state
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

        // Optional: default tidak pilih semua saat load
        if (itemCheckboxes.length > 0) {
            selectAllCheckbox.checked = false;
            itemCheckboxes.forEach(cb => cb.checked = false);
            recalcSelectedTotals();
        }
    });
</script>
@endpush
