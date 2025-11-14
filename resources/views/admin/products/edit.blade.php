@extends('admin.layouts.app')

@section('title', 'Edit Product')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400 mb-2">
            <a href="{{ route('admin.products.index') }}" class="hover:text-blue-600">Products</a>
            <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span>Edit Product</span>
        </div>
        <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Edit Product</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Update product information</p>
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
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
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
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Price (Rp) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="price" value="{{ old('price', $product->price) }}" required min="0" step="0.01"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('price')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
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

                    <div class="flex items-center">
                        <input type="checkbox" name="is_reward" id="is_reward" value="1" 
                               {{ old('is_reward', $product->is_reward) ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                        <label for="is_reward" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Reward Product
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
                    class="mt-4 bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-150">
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
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition duration-150">
                Update Product
            </button>
        </div>
    </form>

    <!-- Delete Product -->
    @can('products.delete')
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mt-6 border-l-4 border-red-500">
        <h2 class="text-xl font-semibold text-red-600 mb-2">Danger Zone</h2>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
            Once you delete this product, all its data will be permanently removed. This action cannot be undone.
        </p>
        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" 
              onsubmit="return confirm('Are you sure you want to delete this product? This action cannot be undone!');">
            @csrf
            @method('DELETE')
            <button type="submit" 
                    class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-6 rounded-lg transition duration-150">
                Delete Product
            </button>
        </form>
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
@endsection
