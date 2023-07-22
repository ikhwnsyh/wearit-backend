<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Support\Str;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

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

    protected $model = Product::class;
    public function definition()
    {
        return [
            'product_name' => $this->faker->unique()->words(2, true),
            'slug' => $this->faker->unique()->slug,
            'description' => $this->faker->paragraph,
            'price' => $this->faker->numberBetween(10000, 100000),
            'thumbnail' => $this->faker->imageUrl(200, 200, 'products', true),
        ];
    }
}
