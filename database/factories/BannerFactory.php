<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Banner>
 */
class BannerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $image = Http::withoutVerifying()->get('https://picsum.dev/1280/720')->body();
        $filename = 'banners/' . Str::random(10) . '.jpg';
        Storage::disk('public')->put($filename, $image);

        return [
            'title' => fake()->sentence(4),
            'subtitle' => fake()->optional()->sentence(10),
            'image' => $filename,
            'url' => null,
            'product_id' => null,
            'sort' => fake()->numberBetween(0, 100),
            'clicks' => fake()->numberBetween(0, 5000),
        ];
    }
}
