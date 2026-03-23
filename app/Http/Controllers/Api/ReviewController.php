<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request, Product $product)
    {
        $reviews = $product->reviews()->with('user')->latest()->get();

        return response()->json(ReviewResource::collection($reviews));
    }

    public function store(ReviewRequest $request, Product $product)
    {
        $user = $request->user();
        $data = $request->validated();

        if ($product->reviews()->where('user_id', $user->id)->exists()) {
            return response()->json([
                'message' => 'You already reviewed this product'
            ], 422);
        }

        $review = $product->reviews()->create([
            'user_id' => $user->id,
            'title' => $data['title'],
            'comment' => $data['comment'],
            'rating' => $data['rating'],
        ]);

        return response()->json(new ReviewResource($review), 201);
    }

    public function like(Review $review)
    {
        $user = auth()->user();

        $mark = $review->marks()->where('user_id', $user->id)->first();

        if ($mark) {
            if ($mark->type === 'like') {
                $mark->delete();
            } else {
                $mark->update(['type' => 'like']);
            }
        } else {
            $review->marks()->create([
                'user_id' => $user->id,
                'type' => 'like',
            ]);
        }

        $review->loadCount([
            'marks as likes' => fn($q) => $q->where('type', 'like'),
            'marks as dislikes' => fn($q) => $q->where('type', 'dislike'),
        ]);

        return response()->json(new ReviewResource($review));
    }

    public function dislike(Review $review)
    {
        $user = auth()->user();

        $mark = $review->marks()->where('user_id', $user->id)->first();

        if ($mark) {
            if ($mark->type === 'dislike') {
                $mark->delete();
            } else {
                $mark->update(['type' => 'dislike']);
            }
        } else {
            $review->marks()->create([
                'user_id' => $user->id,
                'type' => 'dislike',
            ]);
        }

        $review->loadCount([
            'marks as likes' => fn($q) => $q->where('type', 'like'),
            'marks as dislikes' => fn($q) => $q->where('type', 'dislike'),
        ]);

        return response()->json(new ReviewResource($review));
    }
}
