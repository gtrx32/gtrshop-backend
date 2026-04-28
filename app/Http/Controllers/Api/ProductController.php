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

        $sort = request('sort', 'id');
        $order = request('order', 'desc');
        $perPage = request('per_page', 10);

        $priceRange = Product::query()
            ->selectRaw('MIN(price) as min_price, MAX(price) as max_price')
            ->first();

        $products = $query
            ->orderBy($sort, $order)
            ->paginate($perPage);

        return response()->json([
            'data' => ProductResource::collection($products),
            'filters' => [
                'price' => [
                    'min' => $priceRange?->min_price !== null ? round((float) $priceRange->min_price, 2) : null,
                    'max' => $priceRange?->max_price !== null ? round((float) $priceRange->max_price, 2) : null,
                ],
            ],
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
                'next_page_url' => $products->nextPageUrl(),
                'prev_page_url' => $products->previousPageUrl(),
            ],
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
