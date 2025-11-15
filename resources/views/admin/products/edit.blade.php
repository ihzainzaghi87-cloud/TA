@extends('admin.layouts.app')

@section('title', 'Edit Product')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="relative mb-6 bg-gradient-to-r from-amber-600 to-orange-600 dark:from-amber-800 dark:to-orange-800 overflow-hidden shadow-lg rounded-xl">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="relative p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-bold mb-1">Edit Product</h1>
                    <p class="text-amber-100 text-sm">Edit product information: {{ $product->name }}</p>
                </div>
                <a href="{{ route('admin.products.index') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-white bg-opacity-20 backdrop-blur-sm border border-white border-opacity-30 rounded-lg text-white hover:bg-opacity-30 transition-all duration-200 text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Categories
                </a>
            </div>
        </div>
    </div>

    <!-- Error Messages -->
    @if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Basic Information -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow border p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Basic Information</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">Update basic product information</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Product Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Product Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Slug -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Slug
                    </label>
                    <input type="text" name="slug" value="{{ old('slug', $product->slug) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('slug')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Category <span class="text-red-500">*</span>
                    </label>
                    <select name="category_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('category_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Price -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Price (Rp) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="price" value="{{ old('price', $product->price) }}" required min="0" step="0.01"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('price')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Is Reward Checkbox -->
                <div class="mb-4">
                    <div class="flex items-center">
                        <input type="checkbox" name="is_reward" id="is_reward" value="1"
                            {{ old('is_reward', $product->is_reward) ? 'checked' : '' }}
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                        <label for="is_reward" class="ml-2 text-sm font-medium text-gray-700">
                            Reward Product (Can be purchased with points)
                        </label>
                    </div>
                </div>

                <!-- Point Price Field (Shown when is_reward is checked) -->
                <div id="point_price_field" class="mb-4" style="display: {{ old('is_reward', $product->is_reward) ? 'block' : 'none' }};">
                    <label for="point_price" class="block text-sm font-medium text-gray-700 mb-2">
                        Point Price <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="point_price" id="point_price" min="0"
                        value="{{ old('point_price', $product->point_price) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('point_price') border-red-500 @enderror">
                    @error('point_price')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Description
                    </label>
                    <textarea name="description" rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('description', $product->description) }}</textarea>
                    @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status Checkboxes -->
                <div class="md:col-span-2 flex gap-6">
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1" 
                               {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                        <label for="is_active" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Active
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Existing Images -->
        @if($product->images->count() > 0)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Existing Images</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($product->images as $image)
                <div class="relative">
                    <img src="{{ asset('storage/products/' . $image->image) }}" 
                         alt="Product Image" 
                         class="w-full h-32 object-cover rounded-lg">
                    <div class="absolute top-2 right-2">
                        <input type="checkbox" name="delete_images[]" value="{{ $image->id }}"
                               class="w-5 h-5 text-red-600 bg-gray-100 border-gray-300 rounded focus:ring-red-500">
                    </div>
                </div>
                @endforeach
            </div>
            <p class="text-xs text-gray-500 mt-2">Check images to delete them</p>
        </div>
        @endif

        <!-- Add New Images -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Add New Images</h2>
            <div>
                <input type="file" name="images[]" multiple accept="image/*"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <p class="text-xs text-gray-500 mt-1">Accepted formats: JPG, PNG, GIF, WEBP. Max size: 2MB per image.</p>
            </div>
        </div>

        <!-- Existing Variations -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Product Variations</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">Manage product variations (color, size, stock)</p>

            @if($product->variations->count() > 0)
            <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-4">Existing Variations</h3>
            <div id="existing-variations-container" class="mb-6">
                @foreach($product->variations as $index => $variation)
                <div class="existing-variation-row grid grid-cols-1 md:grid-cols-5 gap-4 mb-4">
                    <input type="hidden" name="variations[{{ $index }}][id]" value="{{ $variation->id }}">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Color</label>
                        <input type="text" name="variations[{{ $index }}][color]" value="{{ $variation->color }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Size</label>
                        <input type="text" name="variations[{{ $index }}][size]" value="{{ $variation->size }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Stock</label>
                        <input type="number" name="variations[{{ $index }}][stock]" value="{{ $variation->stock }}" min="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" name="delete_variations[]" value="{{ $variation->id }}"
                               class="w-4 h-4 text-red-600 bg-gray-100 border-gray-300 rounded focus:ring-red-500">
                        <label class="ml-2 text-sm text-gray-700 dark:text-gray-300">Delete</label>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-4 mt-6">Add New Variations</h3>
            <div id="new-variations-container"></div>

            <button type="button" onclick="addNewVariation()" 
                    class="mt-4 bg-gradient-to-r from-blue-600 to-cyan-600 dark:from-blue-800 dark:to-cyan-800 text-white font-semibold py-2 px-4 rounded-lg transition duration-150">
                + Add Variation
            </button>
        </div>

        <!-- Submit Buttons -->
        <div class="flex justify-end gap-4">
            <a href="{{ route('admin.products.index') }}" 
               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-6 rounded-lg transition duration-150">
                Cancel
            </a>
            <button type="submit" 
                    class="bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-700 hover:to-orange-700 text-white font-semibold py-2 px-6 rounded-lg transition duration-150">
                Update Product
            </button>
        </div>
    </form>

    <!-- Delete Product -->
    @can('products.delete')
    <div class="mt-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 rounded-xl p-5">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
            </div>
            <div class="ml-4 flex-1">
                <h3 class="text-sm font-medium text-red-800 dark:text-red-200">Delete Product</h3>
                <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                    <p>Once you delete this product, all its data will be permanently removed. This action cannot be undone.</p>
                </div>
                <div class="mt-4">
                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}" 
                          onsubmit="return confirm('Are you sure you want to delete this product? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:focus:ring-offset-red-900 text-sm transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Delete Product
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endcan
</div>

<script>
let newVariationIndex = {{ $product->variations->count() }};

function addNewVariation() {
    const container = document.getElementById('new-variations-container');
    const newRow = document.createElement('div');
    newRow.className = 'new-variation-row grid grid-cols-1 md:grid-cols-4 gap-4 mb-4';
    newRow.innerHTML = `
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Color</label>
            <input type="text" name="variations[${newVariationIndex}][color]" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                   placeholder="e.g., Red, Blue">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Size</label>
            <input type="text" name="variations[${newVariationIndex}][size]" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                   placeholder="e.g., S, M, L">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Stock</label>
            <input type="number" name="variations[${newVariationIndex}][stock]" min="0" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                   placeholder="0">
        </div>
        <div class="flex items-end">
            <button type="button" onclick="removeNewVariation(this)" 
                    class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-150">
                Remove
            </button>
        </div>
    `;
    container.appendChild(newRow);
    newVariationIndex++;
}

function removeNewVariation(button) {
    const row = button.closest('.new-variation-row');
    row.remove();
}
</script>

<script>
    // Toggle point price field based on is_reward checkbox
    document.getElementById('is_reward').addEventListener('change', function() {
        const pointPriceField = document.getElementById('point_price_field');
        const pointPriceInput = document.getElementById('point_price');
        
        if (this.checked) {
            pointPriceField.style.display = 'block';
            pointPriceInput.required = true;
        } else {
            pointPriceField.style.display = 'none';
            pointPriceInput.required = false;
            pointPriceInput.value = '';
        }
    });

    // Check on page load
    if (document.getElementById('is_reward').checked) {
        document.getElementById('point_price_field').style.display = 'block';
        document.getElementById('point_price').required = true;
    }
</script>
@endsection
