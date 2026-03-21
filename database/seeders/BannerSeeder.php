<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Banner;
use App\Models\Product;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();

        if ($products->isNotEmpty()) {
            $products
                ->shuffle()
                ->take(min(3, $products->count()))
                ->each(function (Product $product) {
                    Banner::factory()->create([
                        'product_id' => $product->id,
                        'url' => null,
                    ]);
                });
        }

        $urls = [
            '/catalog/',
            '/news/',
        ];

        foreach ($urls as $url) {
            Banner::factory()->create([
                'url' => $url,
                'product_id' => null,
            ]);
        }
    }
}
