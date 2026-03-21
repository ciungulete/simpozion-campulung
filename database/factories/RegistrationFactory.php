<?php

namespace Database\Factories;

use App\Enums\PaymentStatus;
use App\Models\Registration;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Registration>
 */
class RegistrationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'uuid' => Str::uuid(),
            'payment_status' => PaymentStatus::Pending,
            'total_amount' => 0,
        ];
    }

    public function paid(): static
    {
        return $this->state(fn () => [
            'payment_status' => fake()->randomElement([
                PaymentStatus::Revolut,
                PaymentStatus::Bcr,
                PaymentStatus::Cash,
            ]),
        ]);
    }
}
