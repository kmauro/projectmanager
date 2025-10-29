<?php

namespace Database\Factories;


use App\Models\Subcategory;
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
    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'subcategory_id' => fake()->randomElement(Subcategory::all()->pluck('id')),
            'price' => fake()->randomFloat(2, 1, 100),
            'cost' => fake()->randomFloat(2, 0.5, 80),
            'stock' => fake()->numberBetween(0, 100),
            'min_stock' => fake()->numberBetween(0, 20),
        ];
    }
}
