<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ReviewIndexRequest;
use App\Http\Requests\Api\ReviewStoreRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Product;
use App\Models\Review;

class ReviewController extends Controller
{
    public function index(ReviewIndexRequest $request)
    {
        $data = $request->validated();

        $sort = $data['sort'] ?? 'date';
        $order = $data['order'] ?? 'desc';
        $perPage = $data['per_page'] ?? 10;

        $query = Review::query()
            ->with('user')
            ->withCount([
                'marks as likes' => fn($q) => $q->where('type', 'like'),
                'marks as dislikes' => fn($q) => $q->where('type', 'dislike'),
            ]);

        if (isset($data['product'])) {
            $product = Product::query()
                ->where('slug', $data['product'])
                ->first();

            $query->where('product_id', $product->id);
        }

        if ($sort === 'rating') {
            $query->orderBy('rating', $order);
        } else {
            $query->orderBy('created_at', $order);
        }

        $reviews = $query->paginate($perPage);

        return response()->json([
            'data' => ReviewResource::collection($reviews),
            'meta' => [
                'current_page' => $reviews->currentPage(),
                'last_page' => $reviews->lastPage(),
                'per_page' => $reviews->perPage(),
                'total' => $reviews->total(),
                'next_page_url' => $reviews->nextPageUrl(),
                'prev_page_url' => $reviews->previousPageUrl(),
            ],
        ]);
    }

    public function store(ReviewStoreRequest $request)
    {
        $user = $request->user();
        $data = $request->validated();
        $product = Product::query()
            ->where('slug', $data['product'])
            ->first();

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

        $review->load('user')
            ->loadCount([
                'marks as likes' => fn($q) => $q->where('type', 'like'),
                'marks as dislikes' => fn($q) => $q->where('type', 'dislike'),
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
