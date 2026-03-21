<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'avatar' => $this->avatar ? Storage::disk('public')->url($this->avatar) : null,
            'reviews' => ReviewResource::collection($this->whenLoaded('reviews')),
            'cart' => new CartResource($this->whenLoaded('cart')),
            'orders' => ReviewResource::collection($this->whenLoaded('orders')),
        ];
    }
}
