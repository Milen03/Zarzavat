<?php


namespace Database\Factories;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            // Ако category_id не е nullable, замени null с \App\Models\Category::factory()
            'category_id' => Category::factory(),
            'name' => $this->faker->unique()->words(2, true),
            'description' => $this->faker->sentence(10),
            'price' => $this->faker->randomFloat(2, 1, 100),
            'stock' => $this->faker->numberBetween(0, 100),
            'image' => 'products/placeholder.jpg',
        ];
    }
}