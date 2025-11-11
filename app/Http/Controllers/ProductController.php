<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware as ControllerMiddleware;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller implements HasMiddleware
{
    /**
     * Middleware untuk mengatur akses berdasarkan permission.
     */
    public static function middleware(): array
    {
        return [
            // Lihat daftar & detail produk
            (new ControllerMiddleware('permission:products.index|products.view'))->only(['index', 'show']),
            
            // Buat products baru
            (new ControllerMiddleware('permission:products.create'))->only(['create', 'store']),
            
            // Edit produk
            (new ControllerMiddleware('permission:products.update'))->only(['edit', 'update']),
            
            // Hapus produk
            (new ControllerMiddleware('permission:products.delete'))->only(['destroy']),
            
            // Hapus gambar produk
            (new ControllerMiddleware('permission:products.destroy-image'))->only(['destroyImage']),
        ];
    }

    /**
     * Tampilkan daftar produk dengan kategori dan gambar.
     */
    public function index()
    {
        $products = Product::with(['category', 'images'])
            ->latest()
            ->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    /**
     * Tampilkan form untuk menambah produk baru.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Simpan produk baru ke database (beserta gambar dan variations).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'images.*' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'variations' => 'nullable|array',
            'variations.*.name' => 'required|string|max:255',
            'variations.*.values' => 'required|array',
            'variations.*.values.*' => 'required|string',
        ]);

        // Generate slug otomatis jika tidak diisi
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Pastikan slug unique
        $slug = $validated['slug'];
        $count = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = $validated['slug'] . '-' . $count;
            $count++;
        }
        $validated['slug'] = $slug;

        // Simpan produk
        $product = Product::create([
            'category_id' => $validated['category_id'],
            'slug' => $validated['slug'],
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],
        ]);

        // Upload gambar jika ada
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $product->images()->create([
                    'image' => $path,
                ]);
            }
        }

        // Simpan variations jika ada
        if (!empty($validated['variations'])) {
            foreach ($validated['variations'] as $variation) {
                $product->variations()->create([
                    'name' => $variation['name'],
                    'values' => $variation['values'],
                ]);
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail produk.
     */
    public function show(Product $product)
    {
        $product->load(['category', 'images', 'variations']);
        return view('admin.products.show', compact('product'));
    }

    /**
     * Tampilkan form edit produk.
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        $product->load(['images', 'variations']);
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Perbarui data produk di database.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug,' . $product->id,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'images.*' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'variations' => 'nullable|array',
            'variations.*.id' => 'nullable|exists:variations,id',
            'variations.*.name' => 'required|string|max:255',
            'variations.*.values' => 'required|array',
            'variations.*.values.*' => 'required|string',
        ]);

        // Generate slug otomatis jika tidak diisi
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Pastikan slug unique (kecuali untuk produk ini sendiri)
        $slug = $validated['slug'];
        $count = 1;
        while (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
            $slug = $validated['slug'] . '-' . $count;
            $count++;
        }
        $validated['slug'] = $slug;

        // Update data produk
        $product->update([
            'category_id' => $validated['category_id'],
            'slug' => $validated['slug'],
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],
        ]);

        // Upload gambar baru jika ada
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $product->images()->create([
                    'image' => $path,
                ]);
            }
        }

        // Update variations
        if (isset($validated['variations'])) {
            $existingVariationIds = [];
            
            foreach ($validated['variations'] as $variationData) {
                if (isset($variationData['id'])) {
                    // Update existing variation
                    $variation = $product->variations()->find($variationData['id']);
                    if ($variation) {
                        $variation->update([
                            'name' => $variationData['name'],
                            'values' => $variationData['values'],
                        ]);
                        $existingVariationIds[] = $variation->id;
                    }
                } else {
                    // Create new variation
                    $newVariation = $product->variations()->create([
                        'name' => $variationData['name'],
                        'values' => $variationData['values'],
                    ]);
                    $existingVariationIds[] = $newVariation->id;
                }
            }
            
            // Delete variations that are not in the request
            $product->variations()->whereNotIn('id', $existingVariationIds)->delete();
        }

        return redirect()->route('admin.products.show', $product)
            ->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Hapus produk dari database beserta gambar dan variations.
     */
    public function destroy(Product $product)
    {
        // Hapus semua gambar dari storage
        foreach ($product->images as $image) {
            if (Storage::disk('public')->exists($image->image)) {
                Storage::disk('public')->delete($image->image);
            }
        }

        // Hapus produk (images dan variations akan terhapus otomatis karena cascade)
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }

    /**
     * Hapus gambar produk tertentu.
     */
    public function destroyImage(ProductImage $image)
    {
        // Hapus file dari storage
        if (Storage::disk('public')->exists($image->image)) {
            Storage::disk('public')->delete($image->image);
        }

        // Hapus record dari database
        $image->delete();

        return back()->with('success', 'Gambar berhasil dihapus.');
    }
}
