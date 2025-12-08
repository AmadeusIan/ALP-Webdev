<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\FabricController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Api\CalendarApiController;
use App\Models\MeetingRequest;


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

use App\Http\Controllers\CalendarController;

Route::get('/events', [CalendarController::class, 'apiAllEvents']);
Route::get('/events/{user}', [CalendarController::class, 'apiEventsByUser']);



Route::get('/events/{userId}', function($userId){
    $meetings = MeetingRequest::where('user_id', $userId)->get();

    return $meetings->map(function($m){
        $color = match($m->status) {
            'approved' => '#10b981', // hijau
            'pending'  => '#f59e0b', // kuning
            'rejected' => '#ef4444', // merah
            default    => '#6b7280',
        };

        return [
            'id' => $m->id,
            'title' => $m->title,
            'start' => $m->start,
            'end' => $m->end,
            'backgroundColor' => $color,
            'borderColor' => $color,
            'textColor' => '#ffffff'
        ];
    });
});




