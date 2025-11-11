@extends('admin.layouts.app')

@section('title', 'Product Details')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="relative bg-gradient-to-r from-indigo-600 to-purple-600 dark:from-indigo-800 dark:to-purple-800 overflow-hidden shadow-lg rounded-xl">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="relative p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold mb-2">Product Details</h1>
                    <p class="text-indigo-100 dark:text-indigo-200">Complete product information: {{ $product->name }}</p>
                </div>
                <div class="flex gap-3">
                    @can('products.update')
                    <a href="{{ route('admin.products.edit', $product) }}" 
                       class="bg-white text-indigo-600 hover:bg-indigo-50 dark:bg-gray-800 dark:text-indigo-400 dark:hover:bg-gray-700 px-6 py-3 rounded-lg font-semibold shadow-lg transition-all duration-200 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit
                    </a>
                    @endcan
                    <a href="{{ route('admin.products.index') }}" 
                       class="bg-white text-indigo-600 hover:bg-indigo-50 dark:bg-gray-800 dark:text-indigo-400 dark:hover:bg-gray-700 px-6 py-3 rounded-lg font-semibold shadow-lg transition-all duration-200 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
         class="bg-green-50 dark:bg-green-900/30 border-l-4 border-green-500 p-4 rounded-lg">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <p class="text-green-700 dark:text-green-300 font-medium">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Product Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 dark:from-indigo-800 dark:to-purple-800 px-6 py-4">
                    <h2 class="text-xl font-bold text-white">Basic Information</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 dark:text-gray-400 mb-1">Product Name</label>
                            <p class="text-gray-900 dark:text-gray-100 font-medium">{{ $product->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 dark:text-gray-400 mb-1">Slug</label>
                            <p class="text-gray-900 dark:text-gray-100 font-mono text-sm">{{ $product->slug }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 dark:text-gray-400 mb-1">Category</label>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                {{ $product->category->name ?? 'N/A' }}
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 dark:text-gray-400 mb-1">Price</label>
                            <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 dark:text-gray-400 mb-1">Stock</label>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold {{ $product->stock > 10 ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : ($product->stock > 0 ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300') }}">
                                {{ $product->stock }} units
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 dark:text-gray-400 mb-1">Created At</label>
                            <p class="text-gray-900 dark:text-gray-100">{{ $product->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-600 dark:text-gray-400 mb-1">Description</label>
                            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                                {{ $product->description ?: 'No description available' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Images Card -->
            @if($product->images->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 dark:from-indigo-800 dark:to-purple-800 px-6 py-4">
                    <h2 class="text-xl font-bold text-white">Product Images</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($product->images as $image)
                        <div class="relative group">
                            <img src="{{ asset('storage/' . $image->image) }}" 
                                 alt="Product Image" 
                                 class="w-full h-48 object-cover rounded-lg shadow-md hover:shadow-xl transition-shadow cursor-pointer"
                                 onclick="window.open('{{ asset('storage/' . $image->image) }}', '_blank')">
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all rounded-lg flex items-center justify-center">
                                <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                                </svg>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Product Variations Card -->
            @if($product->variations->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 dark:from-indigo-800 dark:to-purple-800 px-6 py-4">
                    <h2 class="text-xl font-bold text-white">Product Variations</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($product->variations as $variation)
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 bg-gray-50 dark:bg-gray-700/50">
                            <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">{{ $variation->name }}</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($variation->values as $value)
                                <span class="px-3 py-1 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-300 text-sm font-medium rounded-full">
                                    {{ $value }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar Actions -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden sticky top-6">
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 dark:from-indigo-800 dark:to-purple-800 px-6 py-4">
                    <h2 class="text-xl font-bold text-white">Actions</h2>
                </div>
                <div class="p-6 space-y-3">
                    @can('products.update')
                    <a href="{{ route('admin.products.edit', $product) }}" 
                       class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-orange-600 hover:bg-orange-700 text-white rounded-lg font-semibold transition-colors shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit Product
                    </a>
                    @endcan

                    @can('products.delete')
                    <form action="{{ route('admin.products.destroy', $product) }}" 
                          method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete this product? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg font-semibold transition-colors shadow-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Delete Product
                        </button>
                    </form>
                    @endcan

                    <a href="{{ route('admin.products.index') }}" 
                       class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gray-600 hover:bg-gray-700 dark:bg-gray-700 dark:hover:bg-gray-600 text-white rounded-lg font-semibold transition-colors shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to List
                    </a>
                </div>

                <!-- Product Statistics -->
                <div class="border-t border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-4">Quick Stats</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Total Images</span>
                            <span class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ $product->images->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Variations</span>
                            <span class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ $product->variations->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Last Updated</span>
                            <span class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ $product->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
