<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $this->loadCount([
            'marks as likes' => fn($q) => $q->where('type', 'like'),
            'marks as dislikes' => fn($q) => $q->where('type', 'dislike'),
        ]);

        return [
            'id' => (int) $this->id,
            'title' => $this->title,
            'comment' => $this->comment,
            'rating' => (int) $this->rating,
            'likes' => (int) $this->likes,
            'dislikes' => (int) $this->dislikes,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'user' => new UserResource($this->whenLoaded('user')),
        ];
    }
}
