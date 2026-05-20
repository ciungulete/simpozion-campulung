<?php

namespace Database\Factories;

use App\Models\Plansa;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Plansa>
 */
class PlansaFactory extends Factory
{
    protected $model = Plansa::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid' => (string) Str::uuid(),
            'title' => fake()->sentence(3),
            'file_path' => 'planse/'.fake()->uuid().'.pdf',
        ];
    }
}
