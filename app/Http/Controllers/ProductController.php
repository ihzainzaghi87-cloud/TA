<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware as ControllerMiddleware;

class ProductController extends Controller
{
    /**
     * Middleware untuk mengatur akses berdasarkan permission.
     */
    public static function middleware(): array
    {
        return [
            // Lihat daftar product
            (new ControllerMiddleware('permission:products.index|products.view'))->only(['index', 'show']),
            
            // Buat product baru
            (new ControllerMiddleware('permission:products.create'))->only(['create', 'store']),
            
            // Edit product
            (new ControllerMiddleware('permission:products.update'))->only(['edit', 'update']),
            
            // Hapus product
            (new ControllerMiddleware('permission:products.delete'))->only(['destroy']),
        ];
    }

    /**
     * Display a listing of products.
     */
    public function index(Request $request)
    {
        $query = Product::with(['category', 'images', 'variations']);

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by status
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        // Filter by reward status (is_reward) - TAMBAHAN BARU
        if ($request->filled('is_reward')) {
            $query->where('is_reward', $request->is_reward);
        }

        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->latest()->paginate(15);
        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:products,slug',
            'description' => 'nullable|string',
            'weight' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'point_price' => 'nullable|integer|min:0|required_if:is_reward,1',
            'is_active' => 'boolean',
            'is_reward' => 'boolean',
            
            // Images validation
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            
            // Variations validation (nullable - product dapat tidak memiliki variasi)
            'variations' => 'nullable|array',
            'variations.*.color' => 'required_with:variations|string|max:255',
            'variations.*.size' => 'required_with:variations|string|max:255',
            'variations.*.stock' => 'required_with:variations|integer|min:0',
        ], [
            'images.max' => 'Maksimal 5 gambar dapat diunggah.',
            'images.*.image' => 'Setiap file harus berupa gambar.',
            'images.*.mimes' => 'Format gambar harus jpeg, png, jpg, gif, atau webp.',
            'images.*.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        // Custom validation: Pastikan tidak ada duplikat kombinasi color-size
        if (!empty($validated['variations'])) {
            $combinations = [];
            foreach ($validated['variations'] as $index => $variation) {
                $key = strtolower($variation['color']) . '-' . strtolower($variation['size']);
                if (in_array($key, $combinations)) {
                    return back()->withInput()->withErrors([
                        "variations.{$index}" => "Kombinasi warna '{$variation['color']}' dan ukuran '{$variation['size']}' sudah ada."
                    ]);
                }
                $combinations[] = $key;
            }
        }

        DB::beginTransaction();
        try {
            // Generate slug if not provided
            $slug = $validated['slug'] ?? Str::slug($validated['name']);
            
            // Check for unique slug
            $originalSlug = $slug;
            $counter = 1;
            while (Product::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            // Create product
            $product = Product::create([
                'category_id' => $validated['category_id'],
                'name' => $validated['name'],
                'slug' => $slug,
                'description' => $validated['description'] ?? null,
                'weight' => $validated['weight'],
                'price' => $validated['price'],
                'point_price' => $validated['point_price'] ?? null,
                'is_active' => $request->boolean('is_active', true),
                'is_reward' => $request->boolean('is_reward', false),
            ]);

            // Handle product images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $path = $image->storeAs('products', $filename, 'public');
                    
                    $product->images()->create([
                        'image' => $filename,
                    ]);
                }
            }

            // Handle variations (nullable - bisa tidak ada)
            if (!empty($validated['variations'])) {
                foreach ($validated['variations'] as $variation) {
                    $product->variations()->create([
                        'color' => $variation['color'],
                        'size' => $variation['size'],
                        'stock' => $variation['stock'] ?? 0,
                    ]);
                }
            }

            DB::commit();
            
            return redirect()->route('admin.products.index')
                ->with('success', 'Produk berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Delete uploaded images if transaction fails
            if (isset($product) && $product->images->count() > 0) {
                foreach ($product->images as $image) {
                    Storage::disk('public')->delete('products/' . $image->image);
                }
            }
            
            return back()->withInput()
                ->with('error', 'Gagal menambahkan produk: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        $product->load(['category', 'images', 'variations']);
        
        // Get stock information
        $totalStock = $product->variations->sum('stock');
        $availableVariations = $product->variations->where('stock', '>', 0)->count();
        
        return view('admin.products.show', compact('product', 'totalStock', 'availableVariations'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        $product->load(['images', 'variations']);
        
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'slug' => [
                'nullable',
                'string',
                Rule::unique('products', 'slug')->ignore($product->id)
            ],
            'description' => 'nullable|string',
            'weight' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'point_price' => 'nullable|integer|min:0|required_if:is_reward,1',
            'is_active' => 'boolean',
            'is_reward' => 'boolean',
            
            // Images
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'exists:product_images,id',
            
            // Variations (nullable)
            'variations' => 'nullable|array',
            'variations.*.id' => 'nullable|exists:variations,id',
            'variations.*.color' => 'required_with:variations|string|max:255',
            'variations.*.size' => 'required_with:variations|string|max:255',
            'variations.*.stock' => 'required_with:variations|integer|min:0',
            'delete_variations' => 'nullable|array',
            'delete_variations.*' => 'exists:variations,id',
        ], [
            'images.max' => 'Maksimal 5 gambar dapat diunggah.',
            'images.*.image' => 'Setiap file harus berupa gambar.',
            'images.*.mimes' => 'Format gambar harus jpeg, png, jpg, gif, atau webp.',
            'images.*.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        // Validasi duplikat kombinasi color-size
        if (!empty($validated['variations'])) {
            $combinations = [];
            foreach ($validated['variations'] as $index => $variation) {
                $key = strtolower($variation['color']) . '-' . strtolower($variation['size']);
                
                // Skip jika ini adalah update untuk variation yang sama
                if (isset($variation['id'])) {
                    $existingVar = $product->variations()->find($variation['id']);
                    if ($existingVar && 
                        strtolower($existingVar->color) === strtolower($variation['color']) && 
                        strtolower($existingVar->size) === strtolower($variation['size'])) {
                        continue;
                    }
                }
                
                if (in_array($key, $combinations)) {
                    return back()->withInput()->withErrors([
                        "variations.{$index}" => "Kombinasi warna '{$variation['color']}' dan ukuran '{$variation['size']}' sudah ada."
                    ]);
                }
                $combinations[] = $key;
            }
        }

        DB::beginTransaction();
        try {
            // Update product
            $slug = $validated['slug'] ?? Str::slug($validated['name']);
            
            $product->update([
                'category_id' => $validated['category_id'],
                'name' => $validated['name'],
                'slug' => $slug,
                'description' => $validated['description'] ?? null,
                'weight' => $validated['weight'],
                'price' => $validated['price'],
                'point_price' => $validated['point_price'] ?? null,
                'is_active' => $request->boolean('is_active'),
                'is_reward' => $request->boolean('is_reward'),
            ]);

            // Handle new images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $path = $image->storeAs('products', $filename, 'public');
                    
                    $product->images()->create([
                        'image' => $filename,
                    ]);
                }
            }

            // Handle image deletion
            if (!empty($validated['delete_images'])) {
                $imagesToDelete = $product->images()
                    ->whereIn('id', $validated['delete_images'])
                    ->get();
                
                foreach ($imagesToDelete as $image) {
                    Storage::disk('public')->delete('products/' . $image->image);
                    $image->delete();
                }
            }

            // Handle variations
            if (isset($validated['variations'])) {
                foreach ($validated['variations'] as $variationData) {
                    if (isset($variationData['id'])) {
                        // Update existing variation
                        $product->variations()
                            ->where('id', $variationData['id'])
                            ->update([
                                'color' => $variationData['color'],
                                'size' => $variationData['size'],
                                'stock' => $variationData['stock'] ?? 0,
                            ]);
                    } else {
                        // Create new variation
                        $product->variations()->create([
                            'color' => $variationData['color'],
                            'size' => $variationData['size'],
                            'stock' => $variationData['stock'] ?? 0,
                        ]);
                    }
                }
            }

            // Handle variation deletion
            if (!empty($validated['delete_variations'])) {
                $product->variations()
                    ->whereIn('id', $validated['delete_variations'])
                    ->delete();
            }

            DB::commit();
            
            return redirect()->route('admin.products.index')
                ->with('success', 'Produk berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Gagal memperbarui produk: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        DB::beginTransaction();
        try {
            // Delete associated images from storage
            foreach ($product->images as $image) {
                Storage::disk('public')->delete('products/' . $image->image);
            }

            // Delete product (cascade akan menghapus images dan variations)
            $product->delete();

            DB::commit();
            
            return redirect()->route('admin.products.index')
                ->with('success', 'Produk berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
        }
    }

    /**
     * Toggle product active status.
     */
    public function toggleActive(Product $product)
    {
        $product->update([
            'is_active' => !$product->is_active
        ]);

        $status = $product->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Produk berhasil {$status}.");
    }

    /**
     * Check stock availability for a specific variation.
     */
    public function checkStock(Request $request, Product $product)
    {
        $validated = $request->validate([
            'color' => 'required|string',
            'size' => 'required|string',
        ]);

        $variation = $product->variations()
            ->where('color', $validated['color'])
            ->where('size', $validated['size'])
            ->first();

        if (!$variation) {
            return response()->json([
                'available' => false,
                'message' => 'Variasi tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'available' => $variation->stock > 0,
            'stock' => $variation->stock,
            'variation_id' => $variation->id,
        ]);
    }

    /**
     * Get all available variations for a product (API).
     */
    public function getVariations(Product $product)
    {
        $variations = $product->variations()
            ->where('stock', '>', 0)
            ->get()
            ->groupBy('color')
            ->map(function ($items) {
                return [
                    'color' => $items->first()->color,
                    'sizes' => $items->pluck('size', 'id')->toArray(),
                    'stocks' => $items->pluck('stock', 'id')->toArray(),
                ];
            })
            ->values();

        return response()->json([
            'product' => $product->name,
            'variations' => $variations,
            'total_stock' => $product->variations->sum('stock'),
        ]);
    }

    /**
     * Update stock for a specific variation.
     */
    public function updateVariationStock(Request $request, Product $product, $variationId)
    {
        $validated = $request->validate([
            'stock' => 'required|integer|min:0',
        ]);

        $variation = $product->variations()->findOrFail($variationId);
        $variation->update(['stock' => $validated['stock']]);

        return back()->with('success', 'Stock variasi berhasil diperbarui.');
    }

    /**
     * Get products by category (API).
     */
    public function getByCategory($categoryId)
    {
        $products = Product::with(['images', 'variations'])
            ->where('category_id', $categoryId)
            ->where('is_active', true)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'price' => $product->price,
                    'description' => $product->description,
                    'weight' => $product->weight,
                    'images' => $product->images->pluck('image'),
                    'has_variations' => $product->variations->count() > 0,
                    'total_stock' => $product->variations->sum('stock'),
                    'variations_count' => $product->variations->count(),
                ];
            });

        return response()->json($products);
    }

    /**
     * Bulk update product status.
     */
    public function bulkUpdateStatus(Request $request)
    {
        $validated = $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id',
            'is_active' => 'required|boolean',
        ]);

        Product::whereIn('id', $validated['product_ids'])
            ->update(['is_active' => $validated['is_active']]);

        $status = $validated['is_active'] ? 'diaktifkan' : 'dinonaktifkan';
        $count = count($validated['product_ids']);

        return back()->with('success', "{$count} produk berhasil {$status}.");
    }

    /**
     * Display the specified product for customer view.
     */
    public function customerShow($slug)
    {
        $product = Product::with(['category', 'images', 'variations'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Get related products
        $relatedProducts = Product::with(['images'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        // Get stock information
        $totalStock = $product->variations->sum('stock');
        $availableVariations = $product->variations->where('stock', '>', 0);

        // Get unique colors and sizes
        $colors = $product->variations->pluck('color')->unique()->filter();
        $sizes = $product->variations->pluck('size')->unique()->filter();

        return view('customer.products.detail', compact(
            'product',
            'relatedProducts',
            'totalStock',
            'availableVariations',
            'colors',
            'sizes'
        ));
    }
}
