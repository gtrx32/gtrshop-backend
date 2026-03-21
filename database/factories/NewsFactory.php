<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\News>
 */
class NewsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->sentence(6);

        $image = Http::withoutVerifying()->get('https://picsum.dev/800/450')->body();
        $filename = 'news/' . Str::random(10) . '.jpg';
        Storage::disk('public')->put($filename, $image);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'excerpt' => fake()->text(180),
            'content' => fake()->paragraphs(6, true),
            'image' => $filename,
            'active_from' => fake()->dateTimeBetween('-30 days', '+10 days'),
        ];
    }
}
