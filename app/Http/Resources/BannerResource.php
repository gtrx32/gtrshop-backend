<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class BannerResource extends JsonResource
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
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'image' => $this->image ? Storage::disk('public')->url($this->image) : null,
            'url' => $this->url,
            'product' => new ProductResource($this->whenLoaded('product')),
            'target_url' => $this->target_url,
            'sort' => (int) $this->sort,
            'clicks' => (int) $this->clicks,
            'text_color' => $this->text_color,
        ];
    }
}
