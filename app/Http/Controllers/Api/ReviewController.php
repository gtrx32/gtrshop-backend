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
    public function index(Request $request)
    {
        $user = $request->user();

        $reviews = $user->reviews()->with('product')->latest()->get();

        return response()->json(ReviewResource::collection($reviews));
    }

    public function show(Review $review)
    {
        $user = auth()->user();

        if ($review->user_id !== $user->id) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return response()->json(new ReviewResource($review));
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

    public function update(ReviewRequest $request, Review $review)
    {
        $user = $request->user();

        if ($review->user_id !== $user->id) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $data = $request->validated();
        $data = array_filter($data, fn($value) => $value !== null && $value !== '');

        $review->update($data);

        return response()->json(new ReviewResource($review));
    }

    public function destroy(Review $review)
    {
        $user = auth()->user();

        if ($review->user_id !== $user->id) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $review->delete();

        return response()->json(['message' => 'Review deleted successfully']);
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
