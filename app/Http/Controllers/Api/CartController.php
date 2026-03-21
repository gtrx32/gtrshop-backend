<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CartItemRequest;
use App\Http\Resources\CartResource;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function show(Request $request)
    {
        $cart = $request->user()
            ->cart()
            ->with(['cartItems.product'])
            ->first();

        if (!$cart) {
            return response()->json(new CartResource($request->user()->cart()->create()));
        }

        return response()->json(new CartResource($cart));
    }

    public function add(CartItemRequest $request)
    {
        $data = $request->validated();

        $cart = $request->user()->cart()->first();
        if (!$cart) {
            return response()->json(['message' => 'Cart not found'], 404);
        }

        $product = Product::findOrFail($data['product_id']);
        $quantity = $data['quantity'] ?? 1;

        $cart->addProduct($product, $quantity);

        return response()->json(new CartResource($cart));
    }

    public function remove(CartItemRequest $request)
    {
        $data = $request->validated();

        $cart = $request->user()->cart()->first();
        if (!$cart) {
            return response()->json(['message' => 'Cart not found'], 404);
        }

        $product = Product::findOrFail($data['product_id']);
        $quantity = $request->quantity ?? null;

        $cart->removeProduct($product, $quantity);

        return response()->json(new CartResource($cart));
    }
}
