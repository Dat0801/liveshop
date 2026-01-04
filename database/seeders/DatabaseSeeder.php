<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;
use Database\Seeders\RolePermissionSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RolePermissionSeeder::class);

        $categories = [
            'electronics' => ['name' => 'Electronics', 'slug' => 'electronics', 'description' => 'Latest electronic devices', 'is_active' => true],
            'clothing' => ['name' => 'Clothing', 'slug' => 'clothing', 'description' => 'Fashion and apparel', 'is_active' => true],
            'home-garden' => ['name' => 'Home & Garden', 'slug' => 'home-garden', 'description' => 'Home essentials', 'is_active' => true],
            'sports' => ['name' => 'Sports', 'slug' => 'sports', 'description' => 'Sports gear', 'is_active' => true],
        ];

        $categoryIds = [];
        foreach ($categories as $slug => $cat) {
            $category = Category::firstOrCreate(['slug' => $slug], $cat);
            $categoryIds[$slug] = $category->id;
        }

        // Create Sample Products
        $products = [
            [
                'category_slug' => 'electronics', 'name' => 'Wireless Headphones', 'slug' => 'wireless-headphones',
                'description' => 'Premium wireless headphones with noise cancellation', 'short_description' => 'Premium wireless headphones',
                'base_price' => 149.99, 'discount_price' => 129.99, 'sku' => 'ELEC-001', 'stock_quantity' => 50,
                'is_active' => true, 'is_featured' => true,
            ],
            [
                'category_slug' => 'electronics', 'name' => 'Smart Watch', 'slug' => 'smart-watch',
                'description' => 'Advanced smart watch with fitness tracking', 'short_description' => 'Advanced smart watch',
                'base_price' => 299.99, 'sku' => 'ELEC-002', 'stock_quantity' => 30,
                'is_active' => true, 'is_featured' => true,
            ],
            [
                'category_slug' => 'clothing', 'name' => 'Cotton T-Shirt', 'slug' => 'cotton-t-shirt',
                'description' => '100% premium cotton t-shirt', 'short_description' => 'Premium cotton t-shirt',
                'base_price' => 24.99, 'discount_price' => 19.99, 'sku' => 'CLOTH-001', 'stock_quantity' => 200,
                'is_active' => true, 'is_featured' => false,
            ],
            [
                'category_slug' => 'clothing', 'name' => 'Slim Fit Jeans', 'slug' => 'slim-fit-jeans',
                'description' => 'Modern slim fit jeans', 'short_description' => 'Slim fit jeans',
                'base_price' => 79.99, 'sku' => 'CLOTH-002', 'stock_quantity' => 150,
                'is_active' => true, 'is_featured' => true,
            ],
            [
                'category_slug' => 'home-garden', 'name' => 'Coffee Mug Set', 'slug' => 'coffee-mug-set',
                'description' => 'Set of 4 ceramic coffee mugs', 'short_description' => 'Ceramic coffee mugs',
                'base_price' => 34.99, 'discount_price' => 29.99, 'sku' => 'HOME-001', 'stock_quantity' => 75,
                'is_active' => true, 'is_featured' => false,
            ],
            [
                'category_slug' => 'sports', 'name' => 'Yoga Mat', 'slug' => 'yoga-mat',
                'description' => 'Extra thick yoga mat', 'short_description' => 'Premium yoga mat',
                'base_price' => 39.99, 'discount_price' => 34.99, 'sku' => 'SPORT-001', 'stock_quantity' => 60,
                'is_active' => true, 'is_featured' => true,
            ],
        ];

        $productIds = [];
        foreach ($products as $prod) {
            $categorySlug = $prod['category_slug'];
            unset($prod['category_slug']);
            
            $prod['category_id'] = $categoryIds[$categorySlug];
            
            $product = Product::updateOrCreate(['slug' => $prod['slug']], $prod);
            $productIds[$prod['slug']] = $product->id;
        }

        // Create Variants for T-Shirt (Product ID: 3)
        // We look up ID for 'cotton-t-shirt' and 'slim-fit-jeans'
        $tShirtId = $productIds['cotton-t-shirt'];
        $jeansId = $productIds['slim-fit-jeans'];

        $variants = [
            ['product_id' => $tShirtId, 'type' => 'size', 'value' => 'S', 'price_adjustment' => 0, 'stock_quantity' => 50],
            ['product_id' => $tShirtId, 'type' => 'size', 'value' => 'M', 'price_adjustment' => 0, 'stock_quantity' => 60],
            ['product_id' => $tShirtId, 'type' => 'size', 'value' => 'L', 'price_adjustment' => 0, 'stock_quantity' => 50],
            ['product_id' => $tShirtId, 'type' => 'size', 'value' => 'XL', 'price_adjustment' => 2, 'stock_quantity' => 40],
            ['product_id' => $tShirtId, 'type' => 'color', 'value' => 'Black', 'price_adjustment' => 0, 'stock_quantity' => 70],
            ['product_id' => $tShirtId, 'type' => 'color', 'value' => 'White', 'price_adjustment' => 0, 'stock_quantity' => 70],
            ['product_id' => $tShirtId, 'type' => 'color', 'value' => 'Navy', 'price_adjustment' => 0, 'stock_quantity' => 60],
            // Variants for Jeans (Product ID: 4)
            ['product_id' => $jeansId, 'type' => 'size', 'value' => '30', 'price_adjustment' => 0, 'stock_quantity' => 30],
            ['product_id' => $jeansId, 'type' => 'size', 'value' => '32', 'price_adjustment' => 0, 'stock_quantity' => 40],
            ['product_id' => $jeansId, 'type' => 'size', 'value' => '34', 'price_adjustment' => 0, 'stock_quantity' => 40],
            ['product_id' => $jeansId, 'type' => 'color', 'value' => 'Blue', 'price_adjustment' => 0, 'stock_quantity' => 80],
            ['product_id' => $jeansId, 'type' => 'color', 'value' => 'Black', 'price_adjustment' => 5, 'stock_quantity' => 70],
        ];

        foreach ($variants as $var) {
            ProductVariant::firstOrCreate([
                'product_id' => $var['product_id'],
                'type' => $var['type'],
                'value' => $var['value'],
            ], $var);
        }

        $this->command->info('Database seeded successfully!');
    }
}

