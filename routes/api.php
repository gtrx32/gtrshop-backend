<?php

use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\NewsController;
use Illuminate\Support\Facades\Route;

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);
Route::get('/banners', [BannerController::class, 'index']);
Route::get('/news', [NewsController::class, 'index']);
Route::get('/news/{news}', [NewsController::class, 'show']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [UserController::class, 'show']);
    Route::patch('/user', [UserController::class, 'update']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user/reviews', [ReviewController::class, 'index']);
    Route::get('/user/reviews/{review}', [ReviewController::class, 'show']);
    Route::post('/products/{product_id}/reviews', [ReviewController::class, 'store']);
    Route::put('/user/reviews/{review}', [ReviewController::class, 'update']);
    Route::delete('/user/reviews/{review}', [ReviewController::class, 'destroy']);
    Route::post('/reviews/{review}/like', [ReviewController::class, 'like']);
    Route::post('/reviews/{review}/dislike', [ReviewController::class, 'dislike']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/cart', [CartController::class, 'show']);
    Route::post('/cart/add', [CartController::class, 'add']);
    Route::post('/cart/remove', [CartController::class, 'remove']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);
    Route::post('/orders/create', [OrderController::class, 'store']);
    Route::patch('/orders/{order}/cancel', [OrderController::class, 'cancel']);
});
