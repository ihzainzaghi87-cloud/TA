@extends('admin.layouts.app')

@section('title', 'Edit Product')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="relative bg-gradient-to-r from-orange-600 to-red-600 dark:from-orange-800 dark:to-red-800 overflow-hidden shadow-lg rounded-xl">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="relative p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold mb-2">Edit Product</h1>
                    <p class="text-orange-100 dark:text-orange-200">Update product information: {{ $product->name }}</p>
                </div>
                <a href="{{ route('admin.products.show', $product) }}" 
                   class="bg-white text-orange-600 hover:bg-orange-50 dark:bg-gray-800 dark:text-orange-400 dark:hover:bg-gray-700 px-6 py-3 rounded-lg font-semibold shadow-lg transition-all duration-200 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back
                </a>
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

    <!-- Form Card -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
        <!-- Current Images Section -->
        @if($product->images->count() > 0)
        <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
            <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4">Current Images</h2>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($product->images as $image)
                <div class="relative group">
                    <img src="{{ asset('storage/' . $image->image) }}" 
                            alt="Product Image" 
                            class="w-full h-full object-cover rounded-lg">
                    @can('products.destroy-image')
                    <form action="{{ route('admin.products.destroyImage', $image) }}" 
                            method="POST" 
                            onsubmit="return confirm('Delete this image?');"
                            class="absolute top-2 right-2">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg shadow-lg opacity-0 group-hover:opacity-100 transition-opacity">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </form>
                    @endcan
                </div>
                @endforeach
            </div>
        </div>
        @endif
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="p-6 space-y-6">
                <!-- Basic Information Section -->
                <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4">Update Product Information</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Product Name -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Product Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   value="{{ old('name', $product->name) }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-100 @error('name') border-red-500 @enderror" 
                                   required>
                            @error('name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Category <span class="text-red-500">*</span>
                            </label>
                            <select name="category_id" 
                                    class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-100 @error('category_id') border-red-500 @enderror"
                                    required>
                                <option value="">Select category</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('category_id')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Slug -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Slug
                            </label>
                            <input type="text" 
                                   name="slug" 
                                   value="{{ old('slug', $product->slug) }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-100 @error('slug') border-red-500 @enderror">
                            @error('slug')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Price -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Price (Rp) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                   name="price" 
                                   value="{{ old('price', $product->price) }}"
                                   min="0"
                                   step="0.01"
                                   class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-100 @error('price') border-red-500 @enderror" 
                                   required>
                            @error('price')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Stock -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Stock <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                   name="stock" 
                                   value="{{ old('stock', $product->stock) }}"
                                   min="0"
                                   class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-100 @error('stock') border-red-500 @enderror" 
                                   required>
                            @error('stock')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Description
                            </label>
                            <textarea name="description" 
                                      rows="4"
                                      class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-100 @error('description') border-red-500 @enderror">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Add New Images Section -->
                <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4">Add New Images</h2>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            New Images <span class="text-gray-500 text-xs">(JPEG, JPG, PNG, max 2MB each)</span>
                        </label>
                        <input type="file" 
                               name="images[]" 
                               multiple
                               accept="image/jpeg,image/jpg,image/png"
                               class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-100">
                    </div>
                </div>

                <!-- Product Variations Section -->
                <div x-data="variationsManager({{ json_encode($product->variations->map(function($v) {
                    return ['id' => $v->id, 'name' => $v->name, 'values' => $v->values, 'valuesInput' => implode(', ', $v->values)];
                })->toArray()) }})">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4">Product Variations</h2>
                    
                    <template x-for="(variation, index) in variations" :key="index">
                        <div class="mb-4 p-4 border border-gray-200 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                            <div class="flex items-start gap-4">
                                <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <input type="hidden" :name="'variations[' + index + '][id]'" x-model="variation.id">
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                            Variation Name
                                        </label>
                                        <input type="text" 
                                               :name="'variations[' + index + '][name]'" 
                                               x-model="variation.name"
                                               class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-100">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                            Values <span class="text-gray-500 text-xs">(comma-separated)</span>
                                        </label>
                                        <input type="text" 
                                               x-model="variation.valuesInput"
                                               @input="updateVariationValues(index, $event.target.value)"
                                               class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-100">
                                        <template x-for="(value, vIndex) in variation.values" :key="vIndex">
                                            <input type="hidden" :name="'variations[' + index + '][values][]'" :value="value">
                                        </template>
                                    </div>
                                </div>
                                <button type="button" 
                                        @click="removeVariation(index)"
                                        class="mt-7 text-red-600 hover:text-red-800 dark:text-red-400">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </template>

                    <button type="button" 
                            @click="addVariation()"
                            class="mt-4 px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add Variation
                    </button>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="bg-gray-50 dark:bg-gray-900 px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex justify-end gap-3">
                <a href="{{ route('admin.products.show', $product) }}" 
                   class="px-6 py-2.5 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors font-semibold">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 bg-orange-600 hover:bg-orange-700 text-white rounded-lg transition-colors font-semibold shadow-lg">
                    Update Product
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function variationsManager(existingVariations = []) {
    return {
        variations: existingVariations,
        addVariation() {
            this.variations.push({ id: null, name: '', values: [], valuesInput: '' });
        },
        removeVariation(index) {
            this.variations.splice(index, 1);
        },
        updateVariationValues(index, input) {
            this.variations[index].values = input.split(',').map(v => v.trim()).filter(v => v);
        }
    }
}
</script>
@endsection
