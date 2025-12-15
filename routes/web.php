<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\FabricController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\InventoryLogController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReviewItemController;
use App\Http\Controllers\ShopReviewController;

Route::get('/', [FabricController::class, 'homepage'])->name('welcome.homepage');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::get('/fabrics', [FabricController::class, 'index'])->name('fabrics.index');

Route::get('/fabrics/{id}/reviews', [ReviewItemController::class, 'reviewsForProduct'])->name('review.index');

Route::middleware(['auth'])->group(function () {

    Route::get('/calendar', [CalendarController::class, 'myCalendar'])->name('calendar.my');
    // Route::get('/calendar/events', [CalendarController::class, 'myEvents'])->name('calendar.events.my');

    // Route GET → tampilkan form
    Route::get('/meeting-request', [MeetingController::class, 'create'])->name('meeting-request.create');

    // Route POST → proses form submit
    Route::post('/meeting-request', [MeetingController::class, 'store'])->name('meeting-request.store');


    //order

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/fabrics/{fabric}/book', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');


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

    //notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::patch('/notifications/read-all', [NotificationController::class, 'markAllasRead'])->name('notifications.markAllAsRead');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');

    //reviews
    // Submit review baru
   Route::post('/review-items', [ReviewItemController::class, 'store'])->name('review.store');

    // UPDATE review (harus login)
    Route::put('/review-items/{id}', [ReviewItemController::class, 'update'])->name('review.update');

    //review Shop 
    Route::get('/shop/reviews', [ShopReviewController::class, 'index'])->name('shop.reviews');
Route::post('/shop/review', [ShopReviewController::class, 'store'])->name('review.shop.store');
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




    Route::patch('/orders/{order}/approve', [OrderController::class, 'approve'])->name('orders.approve');
    Route::patch('/orders/{order}/reject', [OrderController::class, 'reject'])->name('orders.reject');


    //schedule routes

    Route::get('/users/{meeting}/calendar', [CalendarController::class, 'adminUser'])->name('calendar.user');
    Route::get('/users/{meeting}/calendar/events', [CalendarController::class, 'userEvents'])->name('calendar.events.user');

    //calendar admin
    Route::get('/calendar/admin', [CalendarController::class, 'adminAll'])->name('calendar.admin');




    Route::get('/meetings', [MeetingController::class, 'adminIndex'])->name('admin.meetings.index');
    Route::post('/meetings/{meeting}/approve', [MeetingController::class, 'accept'])->name('admin.meetings.approve');
    Route::post('/meetings/{meeting}/reject', [MeetingController::class, 'reject'])->name('admin.meetings.reject');
});

Route::get('/fabrics/{fabric}', [FabricController::class, 'show'])->name('fabrics.show');

require __DIR__ . '/auth.php';
