@extends('admin.layouts.app')

@section('title', 'Edit Banner')

@section('content')
<div class="space-y-6">
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
        <a href="{{ route('admin.banners.index') }}" class="hover:text-blue-600 dark:hover:text-blue-400">Banners</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
        <span class="text-gray-900 dark:text-white font-medium">Edit</span>
    </div>

    <!-- Header -->
    <div class="relative bg-gradient-to-r from-amber-600 to-orange-600 dark:from-amber-800 dark:to-orange-800 overflow-hidden shadow-lg rounded-xl">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="relative p-6 text-white">
            <h1 class="text-xl font-bold mb-1">Edit Banner</h1>
            <p class="text-amber-100 text-sm">Update banner details</p>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Banner Details</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Update the banner information below</p>
        </div>

        <form action="{{ route('admin.banners.update', $banner) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Current Image -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Current Image
                </label>
                <div class="relative inline-block">
                    <img src="{{ asset('storage/' . $banner->image) }}" 
                         alt="{{ $banner->title }}" 
                         class="h-48 rounded-lg border-2 border-gray-200 dark:border-gray-600 object-cover">
                    @if($banner->is_active)
                    <span class="absolute top-2 right-2 inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                        <span class="w-2 h-2 mr-1 rounded-full bg-green-500"></span>
                        Active
                    </span>
                    @else
                    <span class="absolute top-2 right-2 inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                        <span class="w-2 h-2 mr-1 rounded-full bg-gray-500"></span>
                        Inactive
                    </span>
                    @endif
                </div>
            </div>

            <!-- New Image Upload -->
            <div>
                <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Change Banner Image <span class="text-gray-400 text-xs">(Optional)</span>
                </label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-lg hover:border-amber-400 dark:hover:border-amber-500 transition-colors duration-200 bg-gray-50 dark:bg-gray-900/40">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <div class="flex text-sm text-gray-600 dark:text-gray-400">
                            <label for="image" class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-amber-600 hover:text-amber-500">
                                <span>Upload a new file</span>
                                <input id="image" name="image" type="file" class="sr-only" accept="image/*" onchange="previewImage(event)">
                            </label>
                            <p class="pl-1">or drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG, GIF, WEBP up to 2MB</p>
                    </div>
                </div>
                <div id="image-preview" class="mt-4 hidden">
                    <div class="relative inline-block">
                        <img src="" alt="Preview" class="max-h-64 rounded-lg border-2 border-gray-200 dark:border-gray-600">
                        <button type="button" onclick="removePreview()" class="absolute top-2 right-2 bg-red-600 text-white rounded-full p-1 hover:bg-red-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                @error('image')
                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Title <span class="text-gray-400 text-xs">(Optional)</span>
                </label>
                <input type="text" 
                       name="title" 
                       id="title" 
                       value="{{ old('title', $banner->title) }}"
                       class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 dark:bg-gray-700 dark:text-white transition-colors duration-200 @error('title') border-red-300 @enderror"
                       placeholder="Enter banner title">
                @error('title')
                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Subtitle -->
            <div>
                <label for="subtitle" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Subtitle <span class="text-gray-400 text-xs">(Optional)</span>
                </label>
                <input type="text" 
                       name="subtitle" 
                       id="subtitle" 
                       value="{{ old('subtitle', $banner->subtitle) }}"
                       class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 dark:bg-gray-700 dark:text-white transition-colors duration-200 @error('subtitle') border-red-300 @enderror"
                       placeholder="Enter banner subtitle">
                @error('subtitle')
                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Status
                </label>
                <div class="flex items-center p-4 bg-gray-50 dark:bg-gray-900/40 rounded-lg border border-gray-200 dark:border-gray-600">
                    <input type="checkbox" 
                           name="is_active" 
                           id="is_active" 
                           value="1"
                           {{ old('is_active', $banner->is_active) ? 'checked' : '' }}
                           class="h-4 w-4 text-amber-600 focus:ring-amber-500 border-gray-300 rounded">
                    <label for="is_active" class="ml-3">
                        <span class="text-sm font-medium text-gray-900 dark:text-white">Active</span>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Display this banner on the website</p>
                    </label>
                </div>
                @error('is_active')
                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('admin.banners.index') }}" 
                   class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                    Cancel
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-700 hover:to-orange-700 text-white text-sm font-medium rounded-lg transition-all duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Update Banner
                </button>
            </div>
        </form>
    </div>

    <!-- Delete Section -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-xl border border-red-200 dark:border-red-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-red-200 dark:border-red-700 bg-red-50 dark:bg-red-900/20">
            <h2 class="text-lg font-semibold text-red-900 dark:text-red-400">Danger Zone</h2>
            <p class="text-sm text-red-600 dark:text-red-300 mt-1">Once you delete this banner, the image will be permanently removed. This action cannot be undone.</p>
        </div>
        <div class="px-6 py-4">
            <form action="{{ route('admin.banners.destroy', $banner) }}" 
                  method="POST" 
                  onsubmit="return confirm('Are you sure you want to delete this banner? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 text-white text-sm font-medium rounded-lg transition-all duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Delete Banner
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function previewImage(event) {
    const preview = document.getElementById('image-preview');
    const img = preview.querySelector('img');
    const file = event.target.files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            img.src = e.target.result;
            preview.classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    }
}

function removePreview() {
    const preview = document.getElementById('image-preview');
    const input = document.getElementById('image');
    preview.classList.add('hidden');
    input.value = '';
}
</script>
@endsection
@extends('admin.layouts.app')

@section('title', 'Edit Banner')

@section('content')
<div class="space-y-6">
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
        <a href="{{ route('admin.banners.index') }}" class="hover:text-blue-600 dark:hover:text-blue-400">Banners</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
        <span class="text-gray-900 dark:text-white font-medium">Edit</span>
    </div>

    <!-- Header -->
    <div class="relative bg-gradient-to-r from-amber-600 to-orange-600 dark:from-amber-800 dark:to-orange-800 overflow-hidden shadow-lg rounded-xl">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="relative p-6 text-white">
            <h1 class="text-xl font-bold mb-1">Edit Banner</h1>
            <p class="text-amber-100 text-sm">Update banner details</p>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Banner Details</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Update the banner information below</p>
        </div>

        <form action="{{ route('admin.banners.update', $banner) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Current Image -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Current Image
                </label>
                <div class="relative inline-block">
                    <img src="{{ asset('storage/' . $banner->image) }}" 
                         alt="{{ $banner->title }}" 
                         class="h-48 rounded-lg border-2 border-gray-200 dark:border-gray-600 object-cover">
                    @if($banner->is_active)
                    <span class="absolute top-2 right-2 inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                        <span class="w-2 h-2 mr-1 rounded-full bg-green-500"></span>
                        Active
                    </span>
                    @else
                    <span class="absolute top-2 right-2 inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                        <span class="w-2 h-2 mr-1 rounded-full bg-gray-500"></span>
                        Inactive
                    </span>
                    @endif
                </div>
            </div>

            <!-- New Image Upload -->
            <div>
                <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Change Banner Image <span class="text-gray-400 text-xs">(Optional)</span>
                </label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-lg hover:border-amber-400 dark:hover:border-amber-500 transition-colors duration-200 bg-gray-50 dark:bg-gray-900/40">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <div class="flex text-sm text-gray-600 dark:text-gray-400">
                            <label for="image" class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-amber-600 hover:text-amber-500">
                                <span>Upload a new file</span>
                                <input id="image" name="image" type="file" class="sr-only" accept="image/*" onchange="previewImage(event)">
                            </label>
                            <p class="pl-1">or drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG, GIF, WEBP up to 2MB</p>
                    </div>
                </div>
                <div id="image-preview" class="mt-4 hidden">
                    <div class="relative inline-block">
                        <img src="" alt="Preview" class="max-h-64 rounded-lg border-2 border-gray-200 dark:border-gray-600">
                        <button type="button" onclick="removePreview()" class="absolute top-2 right-2 bg-red-600 text-white rounded-full p-1 hover:bg-red-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                @error('image')
                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Title <span class="text-gray-400 text-xs">(Optional)</span>
                </label>
                <input type="text" 
                       name="title" 
                       id="title" 
                       value="{{ old('title', $banner->title) }}"
                       class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 dark:bg-gray-700 dark:text-white transition-colors duration-200 @error('title') border-red-300 @enderror"
                       placeholder="Enter banner title">
                @error('title')
                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Subtitle -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Subtitle <span class="text-gray-400 text-xs">(Optional)</span>
                </label>
                <input type="text" 
                       name="subtitle" 
                       id="subtitle" 
                       value="{{ old('subtitle', $banner->subtitle) }}"
                       class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 dark:bg-gray-700 dark:text-white transition-colors duration-200 @error('subtitle') border-red-300 @enderror"
                       placeholder="Enter banner subtitle">
                @error('subtitle')
                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Status
                </label>
                <div class="flex items-center p-4 bg-gray-50 dark:bg-gray-900/40 rounded-lg border border-gray-200 dark:border-gray-600">
                    <input type="checkbox" 
                           name="is_active" 
                           id="is_active" 
                           value="1"
                           {{ old('is_active', $banner->is_active) ? 'checked' : '' }}
                           class="h-4 w-4 text-amber-600 focus:ring-amber-500 border-gray-300 rounded">
                    <label for="is_active" class="ml-3">
                        <span class="text-sm font-medium text-gray-900 dark:text-white">Active</span>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Display this banner on the website</p>
                    </label>
                </div>
                @error('is_active')
                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('admin.banners.index') }}" 
                   class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                    Cancel
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-700 hover:to-orange-700 text-white text-sm font-medium rounded-lg transition-all duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Update Banner
                </button>
            </div>
        </form>
    </div>

    <!-- Delete Section -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-xl border border-red-200 dark:border-red-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-red-200 dark:border-red-700 bg-red-50 dark:bg-red-900/20">
            <h2 class="text-lg font-semibold text-red-900 dark:text-red-400">Danger Zone</h2>
            <p class="text-sm text-red-600 dark:text-red-300 mt-1">Once you delete this banner, the image will be permanently removed. This action cannot be undone.</p>
        </div>
        <div class="px-6 py-4">
            <form action="{{ route('admin.banners.destroy', $banner) }}" 
                  method="POST" 
                  onsubmit="return confirm('Are you sure you want to delete this banner? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 text-white text-sm font-medium rounded-lg transition-all duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Delete Banner
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function previewImage(event) {
    const preview = document.getElementById('image-preview');
    const img = preview.querySelector('img');
    const file = event.target.files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            img.src = e.target.result;
            preview.classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    }
}

function removePreview() {
    const preview = document.getElementById('image-preview');
    const input = document.getElementById('image');
    preview.classList.add('hidden');
    input.value = '';
}
</script>
@endsection
