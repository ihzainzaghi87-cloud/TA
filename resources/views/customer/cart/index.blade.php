@extends('customer.layouts.app')

@section('title', 'Shopping Cart')

@push('styles')
<style>
    [x-cloak] { display: none !important; }

    /* Background Header (Sama seperti Reward) */
    .header-bg {
        background-color: #E5DECC; /* Warna krem dari gambar Reward */
    }

    /* Checkbox Style - Senada dengan tema hitam */
    .cart-checkbox {
        appearance: none;
        width: 22px;
        height: 22px;
        border: 2px solid #D1D5DB; /* Gray-300 */
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

    /* Quantity Button */
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

<section class="header-bg">
    <div class="py-20 px-6 md:px-12">
        <p class="text-sm md:text-base text-gray-700 mb-2">
            Home / Shopping Cart
        </p>
        <h1 class="text-4xl md:text-5xl font-bold mb-4 text-left text-black">
            Your Cart
        </h1>
        <p class="text-lg md:text-xl mb-8 text-gray-800">
            Review your selections and proceed to checkout.
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

        @if($cartItems->isEmpty())
            <div class="text-center py-16">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-shopping-basket text-4xl text-gray-300"></i>
                </div>
                <h3 class="text-2xl font-bold text-black mb-2">Cart is Empty</h3>
                <p class="text-gray-500 mb-8 max-w-md mx-auto">Looks like you haven't added anything to your cart yet.</p>
                <a href="{{ route('home') }}" class="inline-block bg-black text-white px-8 py-3.5 rounded-lg font-bold hover:bg-gray-800 transition-all shadow-lg hover:shadow-xl">
                    Start Shopping
                </a>
            </div>
        @else
            <form id="select-products-form" method="POST" action="{{ route('checkout.select-products') }}">
                @csrf
                <input type="hidden" name="selected_variations" id="selected-variations" value="">

                <div class="flex flex-col lg:flex-row gap-8 items-start">

                    {{-- LEFT COLUMN: Cart Items --}}
                    <div class="flex-1 w-full space-y-6">

                        {{-- Select All Header --}}
                        <div class="bg-white rounded-xl p-6 border border-gray-200 flex items-center gap-4 shadow-sm">
                            <input type="checkbox" id="select-all" class="cart-checkbox">
                            <label for="select-all" class="font-bold text-gray-900 cursor-pointer select-none">Select All Items</label>
                        </div>

                        {{-- Loop Items --}}
                        @foreach($cartItems as $item)
                            @php
                                $variation = $item->variation;
                                $product = $variation->product ?? null;
                                $productName = $product->name ?? 'Produk tidak tersedia';
                                $unitPrice = $product->price ?? 0;
                                $linePrice = $unitPrice * $item->quantity;
                                $productImage = $product && $product->images && $product->images->count() > 0 ? asset('storage/products/' . $product->images->first()->image) : null;
                            @endphp

                            <div class="bg-white rounded-xl p-6 border border-gray-200 hover:border-black transition-all duration-300 shadow-sm hover:shadow-md flex flex-col md:flex-row gap-6 items-start md:items-center group">

                                {{-- Checkbox --}}
                                <div class="shrink-0 pt-2 md:pt-0">
                                    <input type="checkbox"
                                           class="cart-checkbox item-checkbox"
                                           data-line-price="{{ $linePrice }}"
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
                                                <p class="text-sm text-gray-500 font-medium bg-gray-100 px-2 py-0.5 rounded-md inline-block">
                                                    {{ ucfirst($variation->color ?? '-') }} / {{ strtoupper($variation->size ?? '-') }}
                                                </p>
                                            @endif
                                        </div>

                                        {{-- Remove Button (Mobile aligned right) --}}
                                        <form action="{{ route('cart.destroy', $item->id) }}" method="POST" class="md:hidden">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-gray-400 hover:text-red-500 transition p-2">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>

                                    <div class="flex flex-col md:flex-row justify-between items-end md:items-center gap-4 mt-4">
                                        <div class="font-black text-xl text-black">
                                            Rp {{ number_format($unitPrice, 0, ',', '.') }}
                                        </div>

                                        <div class="flex items-center gap-6 w-full md:w-auto justify-between md:justify-end">
                                            {{-- Quantity Control --}}
                                            <div class="flex items-center gap-3 bg-gray-50 rounded-full px-2 py-1 border border-gray-200">
                                                <button type="button" class="qty-btn border-none bg-transparent hover:bg-gray-200 hover:text-black" onclick="updateQty({{ $item->id }}, -1)">
                                                    <i class="fas fa-minus text-[10px]"></i>
                                                </button>
                                                <input type="number"
                                                       value="{{ $item->quantity }}"
                                                       class="w-8 text-center bg-transparent border-none p-0 font-bold text-black focus:ring-0"
                                                       readonly>
                                                <button type="button" class="qty-btn border-none bg-transparent hover:bg-gray-200 hover:text-black" onclick="updateQty({{ $item->id }}, 1)">
                                                    <i class="fas fa-plus text-[10px]"></i>
                                                </button>

                                                <form id="update-form-{{ $item->id }}" action="{{ route('cart.update', $item->id) }}" method="POST" class="hidden">
                                                    @csrf @method('PUT')
                                                    <input type="hidden" name="quantity" id="input-qty-{{ $item->id }}" value="{{ $item->quantity }}">
                                                </form>
                                            </div>

                                            {{-- Remove Button (Desktop) --}}
                                            <form action="{{ route('cart.destroy', $item->id) }}" method="POST" class="hidden md:block">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="w-10 h-10 rounded-full border border-gray-200 flex items-center justify-center text-gray-400 hover:bg-black hover:text-white hover:border-black transition-all" title="Remove Item">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- RIGHT COLUMN: Summary --}}
                    <div class="w-full lg:w-[400px] shrink-0">
                        <div class="bg-white rounded-xl p-8 border border-gray-200 shadow-lg sticky top-24">
                            <h3 class="text-2xl font-bold text-black mb-6">Summary</h3>

                            <div class="space-y-4 mb-8">
                                <div class="flex justify-between items-center text-gray-600">
                                    <span>Selected Items</span>
                                    <span class="font-bold text-black bg-gray-100 px-2 py-0.5 rounded" id="selected-count">0</span>
                                </div>
                                <div class="flex justify-between items-center pt-4 border-t border-gray-100">
                                    <span class="text-gray-900 font-bold text-lg">Total Price</span>
                                    <span class="font-black text-2xl text-black" id="total-summary">Rp 0</span>
                                </div>
                            </div>

                            <button type="button" id="checkout-btn" class="block w-full bg-black text-white font-bold py-4 rounded-lg hover:bg-gray-800 active:scale-95 transition-all duration-200 text-center shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                                Checkout Now <i class="fas fa-arrow-right"></i>
                            </button>

                            <div class="mt-6 text-center">
                                <a href="{{ route('home') }}" class="text-sm font-bold text-gray-500 hover:text-black transition decoration-2 hover:underline underline-offset-4">
                                    Continue Shopping
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </form>
        @endif
    </div>
</section>

@endsection

@push('scripts')
<script>
    function updateQty(itemId, change) {
        const input = document.getElementById('input-qty-' + itemId);
        let newVal = parseInt(input.value) + change;
        if(newVal < 1) newVal = 1;
        input.value = newVal;
        document.getElementById('update-form-' + itemId).submit();
    }

    document.addEventListener('DOMContentLoaded', function () {
        const selectAll = document.getElementById('select-all');
        const checkboxes = document.querySelectorAll('.item-checkbox');
        const totalDisplay = document.getElementById('total-summary');
        const countDisplay = document.getElementById('selected-count');
        const checkoutBtn = document.getElementById('checkout-btn');
        const form = document.getElementById('select-products-form');
        const hiddenInput = document.getElementById('selected-variations');

        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID').format(number);
        }

        function calculateTotal() {
            let total = 0;
            let count = 0;
            let selectedIds = [];

            checkboxes.forEach(cb => {
                if (cb.checked) {
                    total += parseInt(cb.getAttribute('data-line-price'));
                    selectedIds.push(cb.getAttribute('data-variation-id'));
                    count++;
                }
            });

            totalDisplay.textContent = 'Rp ' + formatRupiah(total);
            countDisplay.textContent = count;
            hiddenInput.value = JSON.stringify(selectedIds);
        }

        if(selectAll) {
            selectAll.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = this.checked);
                calculateTotal();
            });
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                const allChecked = Array.from(checkboxes).every(c => c.checked);
                if(selectAll) selectAll.checked = allChecked;
                calculateTotal();
            });
        });

        checkoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const selectedIds = JSON.parse(hiddenInput.value || '[]');

            if (selectedIds.length === 0) {
                alert('Please select at least one item to checkout.');
                return;
            }

            form.submit();
        });
    });
</script>
@endpush
