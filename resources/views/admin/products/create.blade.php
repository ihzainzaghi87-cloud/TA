@extends('admin.layouts.app')

@section('title', 'Add Product')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="relative mb-6 bg-gradient-to-r from-emerald-600 to-green-600 dark:from-emerald-800 dark:to-green-800 overflow-hidden shadow-lg rounded-xl">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="relative p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-bold mb-1">Create New Product</h1>
                    <p class="text-emerald-100 text-sm">Create a new product for your catalog</p>
                </div>
                <a href="{{ route('admin.products.index') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-white bg-opacity-20 backdrop-blur-sm border border-white border-opacity-30 rounded-lg text-white hover:bg-opacity-30 transition-all duration-200 text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Products
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

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Basic Information -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow border p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Basic Information</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">Enter basic product information</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Product Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Product Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                           placeholder="Enter product name">
                    @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Slug -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Slug <span class="text-gray-500 text-xs">(Optional, auto-generated)</span>
                    </label>
                    <input type="text" name="slug" value="{{ old('slug') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                           placeholder="product-slug">
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
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                    <input type="number" name="price" value="{{ old('price') }}" required min="0" step="0.01"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                           placeholder="0">
                    @error('price')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Is Reward Checkbox -->
                <div class="mb-4">
                    <div class="flex items-center">
                        <input type="checkbox" name="is_reward" id="is_reward" value="1"
                            {{ old('is_reward') ? 'checked' : '' }}
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                        <label for="is_reward" class="ml-2 text-sm font-medium text-gray-700">
                            Reward Product (Can be purchased with points)
                        </label>
                    </div>
                </div>

                <!-- Point Price Field (Hidden by default, shown when is_reward is checked) -->
                <div id="point_price_field" class="mb-4" style="display: none;">
                    <label for="point_price" class="block text-sm font-medium text-gray-700 mb-2">
                        Point Price <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="point_price" id="point_price" min="0"
                        value="{{ old('point_price') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('point_price') border-red-500 @enderror">
                    @error('point_price')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Weight -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Weight (grams) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                        name="weight" 
                        step="0.01"
                        min="0"
                        value="{{ old('weight') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        placeholder="Enter product weight in grams">
                    @error('weight')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Description
                    </label>
                    <textarea name="description" rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                              placeholder="Enter product description">{{ old('description') }}</textarea>
                    @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status Checkboxes -->
                <div class="md:col-span-2 flex gap-6">
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1" 
                               {{ old('is_active', true) ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                        <label for="is_active" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Active
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Images -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Product Images</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">Upload product images (max 5 images)</p>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Images
                </label>
                <input type="file" name="images[]" multiple accept="image/*"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <p class="text-xs text-gray-500 mt-1">Accepted formats: JPG, PNG, GIF, WEBP. Max size: 2MB per image.</p>
                @error('images')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Product Variations -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Product Variations</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">Add product variations (color, size, stock) - Optional</p>

            <div id="variations-container">
                <!-- Variation Row Template -->
                <div class="variation-row grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Color</label>
                        <input type="text" name="variations[0][color]" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                               placeholder="e.g., Red, Blue">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Size</label>
                        <input type="text" name="variations[0][size]" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                               placeholder="e.g., S, M, L">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Stock</label>
                        <input type="number" name="variations[0][stock]" min="0" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                               placeholder="0">
                    </div>
                    <div class="flex items-end">
                        <button type="button" onclick="removeVariation(this)" 
                                class="w-full bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-semibold py-2 px-4 rounded-lg transition duration-150">
                            Remove
                        </button>
                    </div>
                </div>
            </div>

            <button type="button" onclick="addVariation()" 
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
                    class="bg-gradient-to-r from-emerald-600 to-green-600 hover:from-emerald-700 hover:to-green-700 text-white font-semibold py-2 px-6 rounded-lg transition duration-150">
                Create Product
            </button>
        </div>
    </form>
</div>

<script>
let variationIndex = 1;

function addVariation() {
    const container = document.getElementById('variations-container');
    const newRow = document.createElement('div');
    newRow.className = 'variation-row grid grid-cols-1 md:grid-cols-4 gap-4 mb-4';
    newRow.innerHTML = `
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Color</label>
            <input type="text" name="variations[${variationIndex}][color]" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                   placeholder="e.g., Red, Blue">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Size</label>
            <input type="text" name="variations[${variationIndex}][size]" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                   placeholder="e.g., S, M, L">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Stock</label>
            <input type="number" name="variations[${variationIndex}][stock]" min="0" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                   placeholder="0">
        </div>
        <div class="flex items-end">
            <button type="button" onclick="removeVariation(this)" 
                    class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-150">
                Remove
            </button>
        </div>
    `;
    container.appendChild(newRow);
    variationIndex++;
}

function removeVariation(button) {
    const row = button.closest('.variation-row');
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

    // Check on page load (for old input)
    if (document.getElementById('is_reward').checked) {
        document.getElementById('point_price_field').style.display = 'block';
        document.getElementById('point_price').required = true;
    }
</script>
<script>
    // Toggle point price field based on is_reward checkbox
    document.getElementById('is_reward').addEventListener('change', function() {
        const pointPriceField = document.getElementById('point_price_field');
        const pointPriceInput = document.getElementById('point_price');
        const priceInput = document.querySelector('input[name="price"]');
        
        if (this.checked) {
            pointPriceField.style.display = 'block';
            pointPriceInput.required = true;
            // Set price to 0 and make readonly
            priceInput.value = '0';
            priceInput.readOnly = true;
            priceInput.classList.add('bg-gray-100', 'cursor-not-allowed');
        } else {
            pointPriceField.style.display = 'none';
            pointPriceInput.required = false;
            pointPriceInput.value = '';
            // Enable price input
            priceInput.readOnly = false;
            priceInput.classList.remove('bg-gray-100', 'cursor-not-allowed');
        }
    });

    // Check on page load (for old input)
    if (document.getElementById('is_reward').checked) {
        document.getElementById('point_price_field').style.display = 'block';
        document.getElementById('point_price').required = true;
        const priceInput = document.querySelector('input[name="price"]');
        priceInput.value = '0';
        priceInput.readOnly = true;
        priceInput.classList.add('bg-gray-100', 'cursor-not-allowed');
    }
</script>
@endsection
