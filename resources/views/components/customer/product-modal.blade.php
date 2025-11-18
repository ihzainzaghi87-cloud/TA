@props(['product'])

@php
    // Extract unique colors
    $colors = $product->variations
        ->pluck('color')
        ->filter()
        ->unique()
        ->values();
    
    // Extract unique sizes
    $sizes = $product->variations
        ->pluck('size')
        ->filter()
        ->unique()
        ->values();
    
    // Convert variations to JSON for Alpine.js
    $variationsData = $product->variations->map(function($v) {
        return [
            'id' => $v->id,
            'color' => $v->color,
            'size' => $v->size,
            'stock' => $v->stock,
        ];
    });
@endphp

{{-- Modal dengan Alpine.js x-data lengkap --}}
<template x-if="showModal">
    <div x-data="{
        selectedColor: null,
        selectedSize: null,
        selectedVariation: null,
        quantity: 1,
        errorMessage: '',
        variations: {{ $variationsData->toJson() }},
        
        get currentStock() {
            return this.selectedVariation ? this.selectedVariation.stock : 0;
        },
        
        selectColor(color) {
            this.selectedColor = color;
            this.updateVariation();
        },
        
        selectSize(size) {
            this.selectedSize = size;
            this.updateVariation();
        },
        
        updateVariation() {
            const match = this.variations.find(v => {
                const colorMatch = this.selectedColor ? v.color === this.selectedColor : true;
                const sizeMatch = this.selectedSize ? v.size === this.selectedSize : true;
                return colorMatch && sizeMatch;
            });
            
            if (match) {
                this.selectedVariation = match;
                this.errorMessage = '';
                if (this.quantity > match.stock) {
                    this.quantity = Math.max(1, match.stock);
                }
            } else {
                this.selectedVariation = null;
                if (this.selectedColor && this.selectedSize) {
                    this.errorMessage = 'Kombinasi warna dan ukuran tidak tersedia';
                }
            }
        },
        
        incrementQuantity() {
            if (this.selectedVariation && this.quantity < this.currentStock) {
                this.quantity++;
            }
        },
        
        decrementQuantity() {
            if (this.quantity > 1) {
                this.quantity--;
            }
        },
        
        addToCart() {
            this.errorMessage = '';
            
            if (!this.selectedColor) {
                this.errorMessage = 'Silakan pilih warna';
                return;
            }
            
            if (!this.selectedSize) {
                this.errorMessage = 'Silakan pilih ukuran';
                return;
            }
            
            if (!this.selectedVariation) {
                this.errorMessage = 'Kombinasi tidak tersedia';
                return;
            }
            
            if (this.currentStock <= 0) {
                this.errorMessage = 'Stok habis';
                return;
            }
            
            if (this.quantity < 1 || this.quantity > this.currentStock) {
                this.errorMessage = 'Jumlah tidak valid';
                return;
            }
            
            document.getElementById('addToCartForm_{{ $product->id }}').submit();
        }
    }"
    x-cloak
    @click.self="showModal = false"
    @keydown.escape.window="showModal = false"
    class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center p-4"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0">
        
        <div class="bg-white rounded-xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto"
             @click.stop
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95">
            
            {{-- Modal Header --}}
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h3 class="text-2xl font-bold text-gray-900">Tambah ke Keranjang</h3>
                <button @click="showModal = false" 
                        type="button"
                        class="text-gray-400 hover:text-gray-600 transition duration-300">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>

            {{-- Modal Content --}}
            <div class="p-6">
                <div class="grid md:grid-cols-2 gap-6">
                    {{-- Product Image --}}
                    <div class="aspect-square bg-gray-200 rounded-lg overflow-hidden">
                        @if($product->images && $product->images->count() > 0)
                            <img src="{{ asset('storage/products/' . $product->images->first()->image) }}" 
                                 alt="{{ $product->name }}"
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
                                <i class="fas fa-image text-6xl text-gray-400"></i>
                            </div>
                        @endif
                    </div>

                    {{-- Product Details & Form --}}
                    <div>
                        <h4 class="text-xl font-bold text-gray-900 mb-2">{{ $product->name }}</h4>
                        <p class="text-sm text-gray-600 mb-4">
                            <i class="fas fa-tag mr-1"></i>
                            {{ $product->category->name ?? 'Uncategorized' }}
                        </p>
                        
                        {{-- Price --}}
                        <div class="mb-4">
                            <p class="text-2xl font-bold text-purple-600">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </p>
                            @if($product->point_price)
                            <p class="text-amber-600">
                                <i class="fas fa-star mr-1"></i>
                                {{ number_format($product->point_price, 0, ',', '.') }} Points
                            </p>
                            @endif
                        </div>

                        {{-- Description --}}
                        @if($product->description)
                        <p class="text-gray-600 text-sm mb-6">{{ Str::limit($product->description, 150) }}</p>
                        @endif

                        {{-- Error Message Display --}}
                        <div x-show="errorMessage" 
                             x-cloak
                             class="mb-4 p-3 bg-red-50 border border-red-200 text-red-800 rounded-lg text-sm"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform scale-95"
                             x-transition:enter-end="opacity-100 transform scale-100">
                            <div class="flex items-start">
                                <i class="fas fa-exclamation-circle text-red-500 mt-0.5 mr-2"></i>
                                <span x-text="errorMessage"></span>
                            </div>
                        </div>

                        {{-- Add to Cart Form --}}
                        <form id="addToCartForm_{{ $product->id }}" 
                              action="{{ route('cart.store') }}" 
                              method="POST">
                            @csrf
                            
                            {{-- Color Selector --}}
                            @if($colors->count() > 0)
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-3">
                                    Pilih Warna <span class="text-red-500">*</span>
                                </label>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($colors as $color)
                                    <button type="button"
                                            @click.prevent="selectColor('{{ $color }}')"
                                            :class="selectedColor === '{{ $color }}' ? 'border-purple-600 bg-purple-50 ring-2 ring-purple-600' : 'border-gray-300 hover:border-purple-400'"
                                            class="border-2 rounded-lg px-4 py-2 transition duration-200 focus:outline-none">
                                        <div class="flex items-center gap-2">
                                            <span class="w-4 h-4 rounded-full border border-gray-300" 
                                                  style="background-color: {{ strtolower($color) }}"></span>
                                            <span class="text-sm font-medium capitalize">{{ $color }}</span>
                                        </div>
                                    </button>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            {{-- Size Selector --}}
                            @if($sizes->count() > 0)
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-3">
                                    Pilih Ukuran <span class="text-red-500">*</span>
                                </label>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($sizes as $size)
                                    <button type="button"
                                            @click.prevent="selectSize('{{ $size }}')"
                                            :class="selectedSize === '{{ $size }}' ? 'border-purple-600 bg-purple-50 ring-2 ring-purple-600' : 'border-gray-300 hover:border-purple-400'"
                                            class="border-2 rounded-lg px-4 py-2 transition duration-200 focus:outline-none min-w-[60px]">
                                        <span class="text-sm font-semibold uppercase">{{ $size }}</span>
                                    </button>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            {{-- Stock Information --}}
                            <div class="mb-6 p-4 bg-gray-50 rounded-lg" 
                                 x-show="selectedColor || selectedSize"
                                 x-cloak>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-700">Stok Tersedia:</span>
                                    <span class="text-lg font-bold" 
                                          :class="currentStock > 0 ? 'text-green-600' : 'text-red-600'"
                                          x-text="currentStock > 0 ? currentStock + ' unit' : 'Stok Habis'">
                                    </span>
                                </div>
                                <div class="mt-2 text-xs text-gray-600" 
                                     x-show="selectedColor && selectedSize"
                                     x-cloak>
                                    <i class="fas fa-info-circle mr-1"></i>
                                    <span x-text="'Dipilih: ' + (selectedColor || '') + (selectedColor && selectedSize ? ' - ' : '') + (selectedSize || '')"></span>
                                </div>
                            </div>

                            {{-- Hidden input untuk variations_id --}}
                            <input type="hidden" 
                                   name="variation_id" 
                                   :value="selectedVariation ? selectedVariation.id : ''">

                            {{-- Quantity Selector --}}
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-3">
                                    Jumlah <span class="text-red-500">*</span>
                                </label>
                                <div class="flex items-center gap-4">
                                    <div class="flex items-center border-2 border-gray-300 rounded-lg">
                                        <button type="button" 
                                                @click.prevent="decrementQuantity()"
                                                :disabled="quantity <= 1"
                                                :class="quantity <= 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-100'"
                                                class="px-4 py-2 text-gray-600 transition duration-200">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <input type="number" 
                                               name="quantity"
                                               x-model="quantity"
                                               min="1"
                                               :max="currentStock"
                                               class="w-20 text-center border-0 focus:ring-0 py-2 font-semibold"
                                               readonly>
                                        <button type="button" 
                                                @click.prevent="incrementQuantity()"
                                                :disabled="!selectedVariation || quantity >= currentStock"
                                                :class="!selectedVariation || quantity >= currentStock ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-100'"
                                                class="px-4 py-2 text-gray-600 transition duration-200">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                    <span class="text-sm text-gray-600" 
                                          x-show="selectedVariation"
                                          x-cloak>
                                        (Maks: <span x-text="currentStock"></span>)
                                    </span>
                                </div>
                            </div>

                            {{-- Submit Button --}}
                            <button type="button"
                                    @click.prevent="addToCart()"
                                    :disabled="!selectedVariation || currentStock <= 0"
                                    :class="!selectedVariation || currentStock <= 0 ? 'opacity-50 cursor-not-allowed' : 'hover:from-purple-700 hover:to-pink-700 transform hover:scale-105'"
                                    class="w-full bg-gradient-to-r from-purple-600 to-pink-600 text-white py-3 rounded-lg transition duration-300 font-bold text-lg shadow-lg">
                                <i class="fas fa-shopping-cart mr-2"></i>
                                Tambah ke Keranjang
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
