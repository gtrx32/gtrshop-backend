<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Review;
use App\Models\ReviewMark;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $products = Product::all();

        $pairs = [];
        foreach ($users as $user) {
            foreach ($products as $product) {
                $pairs[] = [$user->id, $product->id];
            }
        }

        $pairs = collect($pairs)->shuffle()->take(50);

        foreach ($pairs as [$userId, $productId]) {
            $review = Review::factory()->create([
                'user_id' => $userId,
                'product_id' => $productId,
            ]);

            $otherUsers = User::where('id', '!=', $userId)
                ->inRandomOrder()
                ->take(rand(0, 20))
                ->get();

            $marks = $otherUsers->map(fn($other) => new ReviewMark([
                'user_id' => $other->id,
                'type' => rand(0, 1) ? 'like' : 'dislike',
            ]));

            $review->marks()->saveMany($marks);
        }
    }
}
