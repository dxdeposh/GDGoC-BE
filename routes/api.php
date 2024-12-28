<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\TransactionController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\WishlistController;
use App\Http\Controllers\API\ReviewController;

// Rute Public
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Rute yang memerlukan autentikasi
Route::middleware('auth:sanctum')->group(function () {
    // Rute Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // User CRUD (Admin only)
    Route::apiResource('users', UserController::class)->except(['create', 'edit']);

    // Produk CRUD
    Route::apiResource('products', ProductController::class)->except(['create', 'edit']);

    // Order CRUD
    Route::apiResource('orders', OrderController::class)->except(['create', 'edit']);

    // Update Status Order
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus']);

    // Transaction CRUD
    Route::apiResource('transactions', TransactionController::class)->except(['create', 'edit']);

    // Update Status Transaction
    Route::patch('/transactions/{transaction}/status', [TransactionController::class, 'updateStatus']);

    // Category CRUD (Admin only)
    Route::apiResource('categories', CategoryController::class)->except(['create', 'edit']);

    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index']);
    Route::post('/wishlist', [WishlistController::class, 'store']);
    Route::delete('/wishlist/{wishlist}', [WishlistController::class, 'destroy']);

    // Review
    Route::get('/products/{product}/reviews', [ReviewController::class, 'index']);
    Route::post('/products/{product}/reviews', [ReviewController::class, 'store']);
    Route::put('/reviews/{review}', [ReviewController::class, 'update']);
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy']);

    // Tambahkan rute untuk entitas lainnya sesuai kebutuhan
});
