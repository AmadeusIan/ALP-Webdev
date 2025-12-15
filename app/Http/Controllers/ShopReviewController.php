<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ReviewShop;
use App\Models\Order;

class ShopReviewController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ambil semua review shop terbaru
        $reviews = ReviewShop::with('user')->latest()->get();

        // Cek apakah user sudah beli produk sebelumnya
        $hasPurchased = Order::where('user_id', $user->id)
            ->where('status', 'approved')
            ->exists();

        // Cek apakah user sudah review toko
        $userReview = ReviewShop::where('user_id', $user->id)->first();

        // Hitung rata-rata rating
        $averageRating = $reviews->count() ? round($reviews->avg('rating'), 1) : 0;
        $totalReviews = $reviews->count();

        return view('shop.reviews', compact('reviews', 'hasPurchased', 'userReview', 'averageRating', 'totalReviews'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();

        // Pastikan user sudah beli produk
        $hasPurchased = Order::where('user_id', $user->id)
            ->where('status', 'approved')
            ->exists();

        if (!$hasPurchased) {
            return back()->with('error', 'You need to purchase a product before reviewing the shop.');
        }

        // Cek kalau user sudah review
        if (ReviewShop::where('user_id', $user->id)->exists()) {
            return back()->with('error', 'You have already reviewed the shop.');
        }

        // Simpan review
        ReviewShop::create([
            'user_id' => $user->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Thank you for reviewing the shop!');
    }
}
