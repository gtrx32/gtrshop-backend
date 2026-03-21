<?php

namespace Database\Factories;

use App\Enums\DeliveryStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Delivery>
 */
class DeliveryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status' => DeliveryStatus::Pending,
            'tracking_code' => null,
            'shipped_at' => null,
            'delivered_at' => null,
        ];
    }
}
