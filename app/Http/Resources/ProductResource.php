<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => (int) $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'price' => round($this->price, 2),
            'stock' => (int) $this->stock,
            'image' => $this->image ? Storage::disk('public')->url($this->image) : null,
            'reviews_count' => $this->when(isset($this->reviews_count), fn() => (int) $this->reviews_count),
            'rating' => $this->when(isset($this->rating), round($this->rating, 1)),
            'reviews' => ReviewResource::collection($this->whenLoaded('reviews')),
        ];
    }
}
