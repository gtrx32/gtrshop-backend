<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $products = Product::all();

        for ($i = 0; $i < 20; $i++) {
            $user = $users->random();

            $order = Order::factory()->create(['user_id' => $user->id]);

            $productsForOrder = $products->shuffle()->take(rand(1, 3));
            $totalPrice = 0;
            $totalQuantity = 0;

            foreach ($productsForOrder as $product) {
                $quantity = rand(1, 3);

                $order->orderItems()->create([
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $product->price,
                ]);

                $totalPrice += $product->price * $quantity;
                $totalQuantity += $quantity;
            }

            $order->update([
                'total_price' => $totalPrice,
                'total_quantity' => $totalQuantity,
            ]);

            $order->payment()->create(['amount' => $totalPrice]);
            $order->delivery()->create([]);
        }
    }
}
