<?php

namespace Database\Factories;

use App\Domains\Projects\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('-1 year', '+1 month');

        return [
            'SID' => fake()->unique()->numberBetween(1000, 999999),
            'title' => fake()->sentence(5),
            'time_sent' => fake()->dateTimeBetween('-2 years', '-1 year'),
            'time_completed' => null,
            'time_modified' => now(),
            'applicant_id' => ApplicantFactory::new(),
            'start_date' => $startDate,
            'end_date' => fake()->dateTimeBetween($startDate, '+2 years'),
            'objective' => fake()->paragraph(),
            'innovation_value' => fake()->paragraph(),
            'trl_level_id' => TRLLevelFactory::new(),
            'area_id' => AreaFactory::new(),
            'located_entity' => fake()->company(),
            'province_id' => ProvinceFactory::new(),
            'EBCT_amount' => (string) fake()->numberBetween(100000, 10000000),
            'countries' => 'Argentina',
        ];
    }
}
