<?php

namespace Database\Factories;

use App\Enums\Payment\PaymentStatusEnum;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'unique_id' => uniqid(),
            'user_id' => User::first(),
            'status' => PaymentStatusEnum::PENDING->value,
            'amount' => fake()->randomFloat(),
            'currency' => 'dollar',
        ];
    }
}
