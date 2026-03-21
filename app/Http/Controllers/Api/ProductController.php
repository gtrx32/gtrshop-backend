<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $query = Product::query()
            ->withCount('reviews')
            ->withAvg(['reviews as rating'], 'rating');

        if ($name = request('name')) {
            $query->where('name', 'like', "%{$name}%");
        }

        if (($priceMin = request('price_min')) !== null) {
            $query->where('price', '>=', $priceMin);
        }

        if (($priceMax = request('price_max')) !== null) {
            $query->where('price', '<=', $priceMax);
        }

        if (request()->boolean('in_stock')) {
            $query->where('stock', '>', 0);
        }

        if (($ratingMin = request('rating_min')) !== null) {
            $query->having('rating', '>=', $ratingMin);
        }

        if (($ratingMax = request('rating_max')) !== null) {
            $query->having('rating', '<=', $ratingMax);
        }

        $sort = request('sort', 'id');
        $order = request('order', 'desc');
        $perPage = request('per_page', 10);

        $products = $query
            ->orderBy($sort, $order)
            ->paginate($perPage);

        return response()->json([
            'data' => ProductResource::collection($products),
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
                'next_page_url' => $products->nextPageUrl(),
                'prev_page_url' => $products->previousPageUrl(),
            ]
        ]);
    }

    public function show(Product $product)
    {
        $product->load(['reviews.user'])
            ->loadCount('reviews')
            ->loadAvg(['reviews as rating'], 'rating');

        return response()->json(new ProductResource($product));
    }
}
