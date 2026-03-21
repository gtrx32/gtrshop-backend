<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (int) $this->id,
            'total_quantity' => (int) $this->total_quantity,
            'total_price' => round($this->total_price, 2),
            'products' => $this->whenLoaded('cartItems', function () {
                return $this->cartItems->map(fn($item) => [
                    'quantity' => (int) $item->quantity,
                    'price' => round($item->product->price, 2),
                    'total' => round($item->quantity * $item->product->price, 2),
                    'product' => new ProductResource($item->product),
                ])->values();
            }),
        ];
    }
}
