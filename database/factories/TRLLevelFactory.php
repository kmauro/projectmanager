<?php

namespace Database\Factories;

use App\Domains\Projects\Models\TRLLevel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TRLLevel>
 */
class TRLLevelFactory extends Factory
{
    protected $model = TRLLevel::class;

    public function definition(): array
    {
        return [
            'trl_level' => 'TRL '.fake()->unique()->numberBetween(1, 9),
        ];
    }
}
