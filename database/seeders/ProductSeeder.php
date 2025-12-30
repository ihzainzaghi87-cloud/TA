<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Variation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            // T-Shirts
            [
                'category_slug' => 't-shirt',
                'name' => 'Paranoia Classic Tee Black',
                'description' => 'Kaos klasik dengan desain minimalis logo The Paranoia. Terbuat dari bahan cotton combed 30s yang nyaman dan breathable. Cocok untuk gaya kasual sehari-hari.',
                'weight' => 200,
                'price' => 199000,
                'point_price' => 0,
                'is_active' => true,
                'is_reward' => false,
                'variations' => [
                    ['color' => 'Black', 'size' => 'S', 'stock' => 50],
                    ['color' => 'Black', 'size' => 'M', 'stock' => 75],
                    ['color' => 'Black', 'size' => 'L', 'stock' => 60],
                    ['color' => 'Black', 'size' => 'XL', 'stock' => 40],
                ],
            ],
            [
                'category_slug' => 't-shirt',
                'name' => 'Paranoia Classic Tee White',
                'description' => 'Kaos klasik dengan desain minimalis logo The Paranoia. Terbuat dari bahan cotton combed 30s yang nyaman dan breathable. Cocok untuk gaya kasual sehari-hari.',
                'weight' => 200,
                'price' => 199000,
                'point_price' => 0,
                'is_active' => true,
                'is_reward' => false,
                'variations' => [
                    ['color' => 'White', 'size' => 'S', 'stock' => 45],
                    ['color' => 'White', 'size' => 'M', 'stock' => 80],
                    ['color' => 'White', 'size' => 'L', 'stock' => 55],
                    ['color' => 'White', 'size' => 'XL', 'stock' => 35],
                ],
            ],
            [
                'category_slug' => 't-shirt',
                'name' => 'Oversized Graphic Tee - Urban Vision',
                'description' => 'Kaos oversized dengan graphic print urban vision. Material premium cotton 24s dengan cutting relaxed fit. Perfect untuk street style look.',
                'weight' => 250,
                'price' => 249000,
                'point_price' => 0,
                'is_active' => true,
                'is_reward' => false,
                'variations' => [
                    ['color' => 'Black', 'size' => 'M', 'stock' => 30],
                    ['color' => 'Black', 'size' => 'L', 'stock' => 45],
                    ['color' => 'Black', 'size' => 'XL', 'stock' => 35],
                    ['color' => 'Cream', 'size' => 'M', 'stock' => 25],
                    ['color' => 'Cream', 'size' => 'L', 'stock' => 40],
                    ['color' => 'Cream', 'size' => 'XL', 'stock' => 30],
                ],
            ],
            // Hoodies
            [
                'category_slug' => 'hoodie',
                'name' => 'Essential Hoodie Black',
                'description' => 'Hoodie essential dengan bahan fleece premium. Dilengkapi kangaroo pocket dan adjustable drawstring hood. Cocok untuk cuaca dingin dan hangout.',
                'weight' => 500,
                'price' => 399000,
                'point_price' => 0,
                'is_active' => true,
                'is_reward' => false,
                'variations' => [
                    ['color' => 'Black', 'size' => 'S', 'stock' => 25],
                    ['color' => 'Black', 'size' => 'M', 'stock' => 40],
                    ['color' => 'Black', 'size' => 'L', 'stock' => 35],
                    ['color' => 'Black', 'size' => 'XL', 'stock' => 20],
                ],
            ],
            [
                'category_slug' => 'hoodie',
                'name' => 'Essential Hoodie Grey',
                'description' => 'Hoodie essential dengan bahan fleece premium. Dilengkapi kangaroo pocket dan adjustable drawstring hood. Cocok untuk cuaca dingin dan hangout.',
                'weight' => 500,
                'price' => 399000,
                'point_price' => 0,
                'is_active' => true,
                'is_reward' => false,
                'variations' => [
                    ['color' => 'Grey', 'size' => 'S', 'stock' => 20],
                    ['color' => 'Grey', 'size' => 'M', 'stock' => 35],
                    ['color' => 'Grey', 'size' => 'L', 'stock' => 30],
                    ['color' => 'Grey', 'size' => 'XL', 'stock' => 15],
                ],
            ],
            [
                'category_slug' => 'hoodie',
                'name' => 'Paranoia Zip Hoodie',
                'description' => 'Zip hoodie dengan full zipper dan dual side pockets. Material heavyweight fleece 320gsm. Embroidered logo di dada kiri.',
                'weight' => 550,
                'price' => 449000,
                'point_price' => 0,
                'is_active' => true,
                'is_reward' => false,
                'variations' => [
                    ['color' => 'Black', 'size' => 'M', 'stock' => 25],
                    ['color' => 'Black', 'size' => 'L', 'stock' => 30],
                    ['color' => 'Black', 'size' => 'XL', 'stock' => 20],
                    ['color' => 'Navy', 'size' => 'M', 'stock' => 20],
                    ['color' => 'Navy', 'size' => 'L', 'stock' => 25],
                    ['color' => 'Navy', 'size' => 'XL', 'stock' => 15],
                ],
            ],
            // Jackets
            [
                'category_slug' => 'jacket',
                'name' => 'Coach Jacket - Street Edition',
                'description' => 'Coach jacket klasik dengan bahan parasut anti air. Snap button closure dengan lining mesh breathable. Ideal untuk layering.',
                'weight' => 400,
                'price' => 349000,
                'point_price' => 0,
                'is_active' => true,
                'is_reward' => false,
                'variations' => [
                    ['color' => 'Black', 'size' => 'M', 'stock' => 20],
                    ['color' => 'Black', 'size' => 'L', 'stock' => 25],
                    ['color' => 'Black', 'size' => 'XL', 'stock' => 15],
                ],
            ],
            [
                'category_slug' => 'jacket',
                'name' => 'Denim Trucker Jacket',
                'description' => 'Jaket denim trucker dengan wash vintage. Button closure dengan dual chest pockets. Material denim 12oz yang sturdy dan durable.',
                'weight' => 600,
                'price' => 549000,
                'point_price' => 0,
                'is_active' => true,
                'is_reward' => false,
                'variations' => [
                    ['color' => 'Blue Wash', 'size' => 'M', 'stock' => 15],
                    ['color' => 'Blue Wash', 'size' => 'L', 'stock' => 20],
                    ['color' => 'Blue Wash', 'size' => 'XL', 'stock' => 10],
                    ['color' => 'Black', 'size' => 'M', 'stock' => 15],
                    ['color' => 'Black', 'size' => 'L', 'stock' => 20],
                    ['color' => 'Black', 'size' => 'XL', 'stock' => 10],
                ],
            ],
            // Pants
            [
                'category_slug' => 'pants',
                'name' => 'Cargo Pants - Utility Series',
                'description' => 'Cargo pants dengan multiple utility pockets. Material ripstop cotton yang kuat dan tahan lama. Dilengkapi adjustable waist dan ankle drawstring.',
                'weight' => 450,
                'price' => 379000,
                'point_price' => 0,
                'is_active' => true,
                'is_reward' => false,
                'variations' => [
                    ['color' => 'Black', 'size' => '30', 'stock' => 25],
                    ['color' => 'Black', 'size' => '32', 'stock' => 35],
                    ['color' => 'Black', 'size' => '34', 'stock' => 30],
                    ['color' => 'Khaki', 'size' => '30', 'stock' => 20],
                    ['color' => 'Khaki', 'size' => '32', 'stock' => 30],
                    ['color' => 'Khaki', 'size' => '34', 'stock' => 25],
                ],
            ],
            [
                'category_slug' => 'pants',
                'name' => 'Relaxed Chino Pants',
                'description' => 'Celana chino dengan cutting relaxed fit. Bahan cotton twill yang nyaman dan breathable. Cocok untuk casual maupun semi-formal.',
                'weight' => 400,
                'price' => 329000,
                'point_price' => 0,
                'is_active' => true,
                'is_reward' => false,
                'variations' => [
                    ['color' => 'Cream', 'size' => '30', 'stock' => 20],
                    ['color' => 'Cream', 'size' => '32', 'stock' => 30],
                    ['color' => 'Cream', 'size' => '34', 'stock' => 25],
                    ['color' => 'Black', 'size' => '30', 'stock' => 25],
                    ['color' => 'Black', 'size' => '32', 'stock' => 35],
                    ['color' => 'Black', 'size' => '34', 'stock' => 30],
                ],
            ],
            // Shorts
            [
                'category_slug' => 'shorts',
                'name' => 'Board Shorts',
                'description' => 'Celana pendek casual dengan mesh lining. Quick dry material cocok untuk aktivitas outdoor. Dilengkapi elastic waistband dan drawstring.',
                'weight' => 200,
                'price' => 229000,
                'point_price' => 0,
                'is_active' => true,
                'is_reward' => false,
                'variations' => [
                    ['color' => 'Black', 'size' => 'M', 'stock' => 30],
                    ['color' => 'Black', 'size' => 'L', 'stock' => 40],
                    ['color' => 'Black', 'size' => 'XL', 'stock' => 25],
                    ['color' => 'Navy', 'size' => 'M', 'stock' => 25],
                    ['color' => 'Navy', 'size' => 'L', 'stock' => 35],
                    ['color' => 'Navy', 'size' => 'XL', 'stock' => 20],
                ],
            ],
            // Caps
            [
                'category_slug' => 'cap',
                'name' => 'Paranoia Dad Cap',
                'description' => 'Dad cap dengan embroidered logo. Material cotton twill dengan adjustable metal buckle. One size fits all.',
                'weight' => 100,
                'price' => 149000,
                'point_price' => 0,
                'is_active' => true,
                'is_reward' => false,
                'variations' => [
                    ['color' => 'Black', 'size' => 'One Size', 'stock' => 50],
                    ['color' => 'White', 'size' => 'One Size', 'stock' => 40],
                    ['color' => 'Navy', 'size' => 'One Size', 'stock' => 35],
                ],
            ],
            [
                'category_slug' => 'cap',
                'name' => 'Snapback Cap - Urban',
                'description' => 'Snapback cap dengan flat brim dan embroidered logo. Adjustable snap closure. Material wool blend premium.',
                'weight' => 120,
                'price' => 179000,
                'point_price' => 0,
                'is_active' => true,
                'is_reward' => false,
                'variations' => [
                    ['color' => 'Black', 'size' => 'One Size', 'stock' => 40],
                    ['color' => 'Grey', 'size' => 'One Size', 'stock' => 30],
                ],
            ],
            // Accessories
            [
                'category_slug' => 'accessories',
                'name' => 'Paranoia Lanyard',
                'description' => 'Lanyard dengan woven logo design. Material polyester premium dengan metal clip. Panjang 45cm.',
                'weight' => 30,
                'price' => 79000,
                'point_price' => 0,
                'is_active' => true,
                'is_reward' => false,
                'variations' => [
                    ['color' => 'Black', 'size' => 'One Size', 'stock' => 100],
                    ['color' => 'White', 'size' => 'One Size', 'stock' => 80],
                ],
            ],
            [
                'category_slug' => 'accessories',
                'name' => 'Paranoia Keychain',
                'description' => 'Keychain metal dengan logo emboss. Dilengkapi karabiner dan key ring. Finishing matte black.',
                'weight' => 50,
                'price' => 59000,
                'point_price' => 0,
                'is_active' => true,
                'is_reward' => false,
                'variations' => [
                    ['color' => 'Black', 'size' => 'One Size', 'stock' => 150],
                ],
            ],
            // Bags
            [
                'category_slug' => 'bag',
                'name' => 'Tote Bag Canvas',
                'description' => 'Tote bag dari canvas heavy duty 12oz. Screen printed logo. Dilengkapi inner pocket untuk barang kecil.',
                'weight' => 300,
                'price' => 159000,
                'point_price' => 0,
                'is_active' => true,
                'is_reward' => false,
                'variations' => [
                    ['color' => 'Natural', 'size' => 'One Size', 'stock' => 60],
                    ['color' => 'Black', 'size' => 'One Size', 'stock' => 50],
                ],
            ],
            [
                'category_slug' => 'bag',
                'name' => 'Sling Bag - Daily Essential',
                'description' => 'Sling bag compact untuk daily use. Material cordura water resistant. Multiple compartments dan adjustable strap.',
                'weight' => 250,
                'price' => 289000,
                'point_price' => 0,
                'is_active' => true,
                'is_reward' => false,
                'variations' => [
                    ['color' => 'Black', 'size' => 'One Size', 'stock' => 35],
                    ['color' => 'Olive', 'size' => 'One Size', 'stock' => 25],
                ],
            ],
            // REWARD PRODUCTS
            [
                'category_slug' => 'accessories',
                'name' => 'Exclusive Sticker Pack',
                'description' => 'Pack berisi 5 sticker exclusive The Paranoia. Material vinyl waterproof. Hanya bisa ditukar dengan poin!',
                'weight' => 20,
                'price' => 0,
                'point_price' => 50,
                'is_active' => true,
                'is_reward' => true,
                'variations' => [
                    ['color' => 'Mixed', 'size' => 'One Size', 'stock' => 200],
                ],
            ],
            [
                'category_slug' => 'accessories',
                'name' => 'Limited Edition Pin',
                'description' => 'Enamel pin limited edition dengan desain exclusive. Hard enamel dengan rubber clutch. Hanya tersedia untuk member!',
                'weight' => 30,
                'price' => 0,
                'point_price' => 100,
                'is_active' => true,
                'is_reward' => true,
                'variations' => [
                    ['color' => 'Gold', 'size' => 'One Size', 'stock' => 100],
                    ['color' => 'Silver', 'size' => 'One Size', 'stock' => 100],
                ],
            ],
            [
                'category_slug' => 't-shirt',
                'name' => 'Member Exclusive Tee',
                'description' => 'Kaos exclusive khusus member dengan desain limited. Cotton combed 24s premium quality. Hanya bisa ditukar dengan poin!',
                'weight' => 200,
                'price' => 0,
                'point_price' => 500,
                'is_active' => true,
                'is_reward' => true,
                'variations' => [
                    ['color' => 'Black', 'size' => 'M', 'stock' => 30],
                    ['color' => 'Black', 'size' => 'L', 'stock' => 30],
                    ['color' => 'Black', 'size' => 'XL', 'stock' => 20],
                ],
            ],
        ];

        foreach ($products as $productData) {
            $category = Category::where('slug', $productData['category_slug'])->first();
            
            if (!$category) {
                continue;
            }

            $variations = $productData['variations'];
            unset($productData['variations'], $productData['category_slug']);

            $slug = Str::slug($productData['name']);
            
            $product = Product::updateOrCreate(
                ['slug' => $slug],
                array_merge($productData, [
                    'category_id' => $category->id,
                    'slug' => $slug,
                ])
            );

            // Create variations
            foreach ($variations as $variation) {
                Variation::updateOrCreate(
                    [
                        'product_id' => $product->id,
                        'color' => $variation['color'],
                        'size' => $variation['size'],
                    ],
                    ['stock' => $variation['stock']]
                );
            }
        }
    }
}
