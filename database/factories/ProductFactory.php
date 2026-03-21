<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);

        $image = Http::withoutVerifying()->get('https://picsum.dev/400/400')->body();
        $filename = 'products/' . Str::random(10) . '.jpg';
        Storage::disk('public')->put($filename, $image);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->text(),
            'price' => fake()->randomFloat(2, 1, 100000),
            'stock' => fake()->numberBetween(0, 100),
            'image' => $filename,
        ];
    }
}
