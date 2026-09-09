<?php

namespace Database\Factories;

use App\Domains\Projects\Models\Applicant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Applicant>
 */
class ApplicantFactory extends Factory
{
    protected $model = Applicant::class;

    public function definition(): array
    {
        return [
            'IP_address' => fake()->ipv4(),
            'user' => fake()->userName(),
            'full_name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'cuil' => fake()->numerify('20#########'),
            'institution_id' => null,
            'company_name' => fake()->optional()->company(),
            'company_CUIT' => fake()->optional()->numerify('30#########'),
        ];
    }
}
