<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $products = Product::all();

        foreach ($users as $user) {
            $cart = Cart::factory()->create([
                'user_id' => $user->id,
            ]);

            $productsForCart = $products->shuffle()->take(rand(1,3));

            foreach ($productsForCart as $product) {
                $quantity = rand(1,5);
                $cart->addProduct($product, $quantity);
            }
        }
    }
}
