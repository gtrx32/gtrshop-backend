<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NewsResource;
use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        $perPage = request('per_page', 10);

        $news = News::query()
            ->where('active_from', '<=', now())
            ->orderByDesc('active_from')
            ->paginate($perPage);

        return response()->json([
            'data' => NewsResource::collection($news),
            'meta' => [
                'current_page' => $news->currentPage(),
                'last_page' => $news->lastPage(),
                'per_page' => $news->perPage(),
                'total' => $news->total(),
                'next_page_url' => $news->nextPageUrl(),
                'prev_page_url' => $news->previousPageUrl(),
            ],
        ]);
    }

    public function show(News $news)
    {
        if ($news->active_from > now()) {
            return response()->json(['message' => 'This news item is not available yet'], 403);
        }

        return response()->json(new NewsResource($news));
    }
}
