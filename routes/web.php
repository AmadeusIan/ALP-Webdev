<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\FabricController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\InventoryLogController;

Route::get('/', [FabricController::class, 'homepage'])->name('welcome.homepage');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::get('/fabrics', [FabricController::class, 'index'])->name('fabrics.index');

Route::middleware(['auth'])->group(function () {
    Route::get('/calendar', [CalendarController::class, 'myCalendar'])->name('calendar.my');
    Route::get('/calendar/events', [CalendarController::class, 'myEvents'])->name('calendar.events.my');

    // Route GET → tampilkan form
    Route::get('/meeting-request', [MeetingController::class, 'create'])->name('meeting-request.create');

    // Route POST → proses form submit
    Route::post('/meeting-request', [MeetingController::class, 'store'])->name('meeting-request.store');


    //order

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');


    //cart

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('add-to-cart/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::delete('remove-from-cart', [CartController::class, 'remove'])->name('cart.remove');
    Route::patch('update-cart', [CartController::class, 'updateCart'])->name('cart.update');

    Route::post('/checkout', [OrderController::class, 'storeCart'])->name('orders.storeCart');


    //profile 
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Halaman Admin
Route::middleware(['auth', 'is_admin'])->group(function () {
    Route::get('/fabrics/create', [FabricController::class, 'create'])->name('fabrics.create');
    Route::post('/fabrics', [FabricController::class, 'store'])->name('fabrics.store');

    Route::get('/fabrics/{fabric}/edit', [FabricController::class, 'edit'])->name('fabrics.edit');
    Route::put('/fabrics/{fabric}', [FabricController::class, 'update'])->name('fabrics.update');

    // Delete
    Route::delete('/fabrics/{fabric}', [FabricController::class, 'destroy'])->name('fabrics.destroy');

    // Fitur Restock
    Route::get('/fabrics/{fabric}/restock', [FabricController::class, 'editStock'])->name('fabrics.restock');
    Route::post('/fabrics/{fabric}/stock', [FabricController::class, 'updateStock'])->name('fabrics.updateStock');




    Route::get('/inventory-logs', [InventoryLogController::class, 'index'])->name('inventory_logs.index');


    Route::get('/fabrics/{fabric}/book', [OrderController::class, 'create'])->name('orders.create');


    Route::patch('/orders/{order}/approve', [OrderController::class, 'approve'])->name('orders.approve');
    Route::patch('/orders/{order}/reject', [OrderController::class, 'reject'])->name('orders.reject');

    //schedule routes

    Route::get('/users/{meeting}/calendar', [CalendarController::class, 'adminUser'])->name('calendar.user');
    Route::get('/users/{meeting}/calendar/events', [CalendarController::class, 'userEvents'])->name('calendar.events.user');

    Route::get('/meetings', [MeetingController::class, 'adminIndex'])->name('admin.meetings.index');
    Route::post('/meetings/{meeting}/approve', [MeetingController::class, 'accept'])->name('admin.meetings.approve');
    Route::post('/meetings/{meeting}/reject', [MeetingController::class, 'reject'])->name('admin.meetings.reject');
});

Route::get('/fabrics/{fabric}', [FabricController::class, 'show'])->name('fabrics.show');

require __DIR__ . '/auth.php';
