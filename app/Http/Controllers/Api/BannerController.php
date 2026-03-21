<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BannerResource;
use App\Models\Banner;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::query()
            ->with('product')
            ->orderBy('sort', 'asc')
            ->orderBy('id', 'desc')
            ->get();

        return response()->json(BannerResource::collection($banners));
    }
}
