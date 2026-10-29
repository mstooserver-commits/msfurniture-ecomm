<?php

use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\Api\CartApiController;
use App\Http\Controllers\Api\OrderApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/products', [ProductApiController::class, 'index']);
    Route::get('/products/{product:slug}', [ProductApiController::class, 'show']);
    Route::get('/categories', [ProductApiController::class, 'categories']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/cart', [CartApiController::class, 'show']);
        Route::post('/cart/items', [CartApiController::class, 'store']);
        Route::patch('/cart/items/{item}', [CartApiController::class, 'update']);
        Route::delete('/cart/items/{item}', [CartApiController::class, 'destroy']);

        Route::get('/orders', [OrderApiController::class, 'index']);
        Route::get('/orders/{order}', [OrderApiController::class, 'show']);
        Route::post('/orders', [OrderApiController::class, 'store']);
    });
});
