<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Buyer\CartController;
use App\Http\Controllers\Buyer\OrderController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\Seller\ProductController;
use App\Http\Controllers\Seller\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/auth/seller-register', [AuthController::class, 'sellerRegister']);
Route::post('/auth/buyer-register', [AuthController::class, 'buyerRegister']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/profile', [AuthController::class, 'profile']);
    // endpoint seller
    Route::prefix('seller')->middleware(['seller'])->group(function () {
        Route::apiResource('/product', ProductController::class);
        Route::apiResource('/transaction', TransactionController::class)->except(['store']);
        Route::post('/file/upload', [FileController::class, 'upload']);
    });
    // endpoint buyer
    Route::prefix('buyer')->middleware(['buyer'])->group(function () {
        Route::apiResource('/cart', CartController::class);
        Route::apiResource('/order', OrderController::class)->only(['index']);
        Route::apiResource('/order', TransactionController::class)->except(['index', 'update']);
    });
});
