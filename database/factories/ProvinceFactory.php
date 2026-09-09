<?php

namespace Database\Factories;

use App\Domains\Projects\Models\Province;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Province>
 */
class ProvinceFactory extends Factory
{
    protected $model = Province::class;

    public function definition(): array
    {
        return [
            'province' => fake()->unique()->state(),
        ];
    }
}
