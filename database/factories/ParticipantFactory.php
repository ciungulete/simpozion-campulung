<?php

namespace Database\Factories;

use App\Enums\Degree;
use App\Enums\Prefix;
use App\Models\Participant;
use App\Models\Registration;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Participant>
 */
class ParticipantFactory extends Factory
{
    public function definition(): array
    {
        return [
            'registration_id' => Registration::factory(),
            'prefix' => fake()->randomElement(Prefix::cases()),
            'full_name' => fake()->name(),
            'degree' => fake()->randomElement(Degree::cases()),
            'dignity' => fake()->randomElement(['Venerable Maestru', 'Prim Supraveghetor', 'Secund Supraveghetor', 'Orator', 'Secretar']),
            'lodge_name' => 'Loja '.fake()->word(),
            'lodge_number' => fake()->numberBetween(1, 200),
            'orient' => fake()->city(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'friday_dinner_count' => fake()->numberBetween(0, 3),
            'symposium_lunch_count' => fake()->numberBetween(0, 3),
            'companion_lunch_count' => fake()->numberBetween(0, 3),
            'ritual_participation' => fake()->boolean(),
            'ball_count' => fake()->numberBetween(0, 3),
            'observations' => fake()->optional()->sentence(),
        ];
    }
}
