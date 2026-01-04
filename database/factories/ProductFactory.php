<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->words(3, true);
        $basePrice = $this->faker->randomFloat(2, 10, 1000);
        
        return [
            'category_id' => Category::factory(),
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->paragraph(),
            'short_description' => $this->faker->sentence(),
            'base_price' => $basePrice,
            'discount_price' => $this->faker->boolean(20) ? $basePrice * 0.9 : null,
            'sku' => $this->faker->unique()->ean13(),
            'stock_quantity' => $this->faker->numberBetween(0, 100),
            'images' => [$this->faker->imageUrl()],
            'is_active' => true,
            'is_featured' => $this->faker->boolean(10),
        ];
    }
}
