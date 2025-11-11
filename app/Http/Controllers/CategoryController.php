<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware as ControllerMiddleware;
use Illuminate\Support\Str;

class CategoryController extends Controller implements HasMiddleware
{
    /**
     * Middleware untuk mengatur akses berdasarkan permission.
     */
    public static function middleware(): array
    {
        return [
            // Lihat daftar kategori
            (new ControllerMiddleware('permission:categories.index'))->only(['index', 'show']),
            
            // Buat kategori baru
            (new ControllerMiddleware('permission:categories.create'))->only(['create', 'store']),
            
            // Edit kategori
            (new ControllerMiddleware('permission:categories.update'))->only(['edit', 'update']),
            
            // Hapus kategori
            (new ControllerMiddleware('permission:categories.delete'))->only(['destroy']),
        ];
    }

    /**
     * Tampilkan daftar kategori.
     */
    public function index()
    {
        $categories = Category::withCount('products')
            ->latest()
            ->paginate(10);

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Tampilkan form untuk membuat kategori baru.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Simpan kategori baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
        ]);

        // Generate slug otomatis jika tidak diisi
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Pastikan slug unique
        $slug = $validated['slug'];
        $count = 1;
        while (Category::where('slug', $slug)->exists()) {
            $slug = $validated['slug'] . '-' . $count;
            $count++;
        }
        $validated['slug'] = $slug;

        Category::create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail kategori beserta produk-produknya.
     */
    public function show(Category $category)
    {
        // Load products dengan pagination
        $products = $category->products()
            ->with('images')
            ->latest()
            ->paginate(12);
        
        return view('admin.categories.show', compact('category', 'products'));
    }


    /**
     * Tampilkan form edit kategori.
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Perbarui data kategori.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'slug' => 'nullable|string|max:255|unique:categories,slug,' . $category->id,
        ]);

        // Generate slug otomatis jika tidak diisi
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Pastikan slug unique (kecuali untuk kategori ini sendiri)
        $slug = $validated['slug'];
        $count = 1;
        while (Category::where('slug', $slug)->where('id', '!=', $category->id)->exists()) {
            $slug = $validated['slug'] . '-' . $count;
            $count++;
        }
        $validated['slug'] = $slug;

        $category->update($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Hapus kategori dari database.
     */
    public function destroy(Category $category)
    {
        // Cek apakah kategori memiliki produk
        if ($category->products()->count() > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Kategori tidak dapat dihapus karena masih memiliki produk.');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}
