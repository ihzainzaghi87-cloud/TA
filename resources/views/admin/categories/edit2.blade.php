@extends('admin.layouts.app')

@section('title', 'Edit Category')

@section('content')
<div class="max-w-xl mx-auto space-y-6">
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl border border-gray-200 dark:border-gray-700 p-6">
        <h2 class="text-lg font-bold mb-4 text-gray-900 dark:text-white">Edit Category</h2>

        <form action="{{ route('admin.categories.update', $category) }}" method="POST">
            @csrf @method('PUT')
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Category Name</label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}" class="w-full mt-1 px-3 py-2 border rounded-lg dark:bg-gray-900 dark:border-gray-700 dark:text-white focus:ring focus:ring-blue-500">
                @error('name') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.categories.index') }}" class="px-4 py-2 text-sm text-gray-600 dark:text-gray-300 border rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">Cancel</a>
                <button type="submit" class="px-4 py-2 text-sm text-white bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg hover:from-blue-700 hover:to-indigo-700">Update</button>
            </div>
        </form>
    </div>
</div>
@endsection
