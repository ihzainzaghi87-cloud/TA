@extends('admin.layouts.app')

@section('title', 'Category Detail')

@section('content')
<div class="space-y-6">
    <!-- Header Section - Compact -->
    <div class="relative bg-gradient-to-r from-indigo-600 to-purple-600 dark:from-indigo-800 dark:to-purple-800 overflow-hidden shadow-lg rounded-xl">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="relative p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-bold mb-1">Category Details</h1>
                    <p class="text-indigo-100 text-sm">Category: {{ $category->name }}</p>
                </div>
                <div class="flex gap-2">
                    @can('categories.update')
                    <a href="{{ route('admin.categories.edit', $category) }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 bg-white bg-opacity-20 backdrop-blur-sm border border-white border-opacity-30 rounded-lg text-white hover:bg-opacity-30 transition-all duration-200 text-sm font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit
                    </a>
                    @endcan
                    <a href="{{ route('admin.categories.index') }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 bg-white bg-opacity-20 backdrop-blur-sm border border-white border-opacity-30 rounded-lg text-white hover:bg-opacity-30 transition-all duration-200 text-sm font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Categories
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-5 gap-6">
        <!-- Left Side: Summary + Category Info -->
        <section class="xl:col-span-2 space-y-6">
            <!-- Summary Stats - Compact -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700 p-5">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white">Category Summary</h3>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Statistics & overview</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4">
                    <div class="text-center p-3 bg-gradient-to-br from-blue-50 to-purple-50 dark:from-blue-900/20 dark:to-purple-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center mx-auto mb-2">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <div class="text-lg font-bold text-gray-900 dark:text-white">{{ $products->total() }}</div>
                        <div class="text-xs text-gray-600 dark:text-gray-400">Total Products</div>
                    </div>
                </div>
            </div>

            <!-- Category Information -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700">
                <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">Category Information</h3>
                </div>
                <div class="p-5 space-y-4">
                    <div>
                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Name</label>
                        <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">{{ $category->name }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Slug</label>
                        <p class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200">
                                {{ $category->slug }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Created At</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $category->created_at->format('F d, Y H:i') }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Last Updated</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $category->updated_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Right Side: Products List -->
        <section class="xl:col-span-3">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700">
                <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white">Products in this Category</h3>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Total {{ $products->total() }} products</p>
                        </div>
                        @can('products.create')
                        <a href="{{ route('admin.products.create') }}?category={{ $category->id }}" 
                           class="inline-flex items-center gap-1 px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-medium transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add Product
                        </a>
                        @endcan
                    </div>
                </div>

                @if($products->count() > 0)
                <div class="p-5">
                    <!-- Products Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($products as $product)
                        <div class="bg-white dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600 overflow-hidden hover:shadow-lg transition-shadow duration-200 group">
                            <!-- Product Image -->
                            <div class="relative h-48 bg-gray-100 dark:bg-gray-600 overflow-hidden">
                                @if($product->images->first())
                                <img src="{{ asset('storage/' . $product->images->first()->image) }}" 
                                     alt="{{ $product->name }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-600 dark:to-gray-700">
                                    <svg class="w-16 h-16 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                @endif
                                
                                <!-- Image Count Badge -->
                                @if($product->images->count() > 1)
                                <div class="absolute top-2 right-2 bg-black bg-opacity-70 text-white text-xs px-2 py-1 rounded-full flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ $product->images->count() }}
                                </div>
                                @endif

                                <!-- Stock Badge -->
                                <div class="absolute top-2 left-2">
                                    @if($product->stock > 0)
                                    <span class="bg-green-500 text-white text-xs px-2 py-1 rounded-full font-medium">In Stock</span>
                                    @else
                                    <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full font-medium">Out of Stock</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Product Info -->
                            <div class="p-4">
                                <h4 class="text-sm font-semibold text-gray-900 dark:text-white line-clamp-2 mb-2 hover:text-indigo-600 dark:hover:text-indigo-400">
                                    <a href="{{ route('admin.products.show', $product) }}">
                                        {{ $product->name }}
                                    </a>
                                </h4>
                                
                                @if($product->description)
                                <p class="text-xs text-gray-600 dark:text-gray-400 line-clamp-2 mb-3">
                                    {{ Str::limit($product->description, 80) }}
                                </p>
                                @endif

                                <!-- Price and Stock -->
                                <div class="flex items-center justify-between mb-3">
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Price</p>
                                        <p class="text-lg font-bold text-indigo-600 dark:text-indigo-400">
                                            Rp {{ number_format($product->price, 0, ',', '.') }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Stock</p>
                                        <p class="text-lg font-bold text-gray-900 dark:text-white">
                                            {{ $product->stock }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.products.show', $product) }}" 
                                       class="flex-1 inline-flex justify-center items-center gap-1 px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-medium transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        View
                                    </a>
                                    @can('products.update')
                                    <a href="{{ route('admin.products.edit', $product) }}" 
                                       class="flex-1 inline-flex justify-center items-center gap-1 px-3 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-lg text-xs font-medium transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Edit
                                    </a>
                                    @endcan
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($products->hasPages())
                    <div class="mt-6">
                        {{ $products->links() }}
                    </div>
                    @endif
                </div>
                @else
                <!-- Empty State -->
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No products yet</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">This category doesn't have any products yet.</p>
                    @can('products.create')
                    <div class="mt-6">
                        <a href="{{ route('admin.products.create') }}?category={{ $category->id }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add First Product
                        </a>
                    </div>
                    @endcan
                </div>
                @endif
            </div>
        </section>
    </div>
</div>
@endsection
