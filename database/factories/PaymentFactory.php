<?php

namespace Database\Factories;

use App\Enums\PaymentStatus;
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
            'amount' => 0,
            'status' => PaymentStatus::Pending,
            'paid_at' => null,
            'transaction_code' => null,
        ];
    }
}
