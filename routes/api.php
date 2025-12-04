<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\FabricController;
use App\Http\Controllers\OrderController;

// ==========================================
// PUBLIC ROUTES (Tanpa Login)
// ==========================================
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/fabrics', [FabricController::class, 'index']); // Katalog Kain

// ==========================================
// PROTECTED ROUTES (Harus Punya Token)
// ==========================================
Route::middleware('auth:sanctum')->group(function () {
    
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Transaksi Order
    Route::post('/orders', [OrderController::class, 'store']);
    
    // Kelola Stok (Admin)
    Route::post('/fabrics', [FabricController::class, 'store']); 
    Route::post('/fabrics/{id}/stock', [FabricController::class, 'updateStock']); 
});