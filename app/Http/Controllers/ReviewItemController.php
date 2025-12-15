<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\ReviewItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewItemController extends Controller
{
        // CREATE REVIEW

    public function store(Request $request)
    {
        $request->validate([
            'order_item_id' => 'required|exists:order_items,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string'
        ]);

        $item = OrderItem::with('order', 'review')->findOrFail($request->order_item_id);

        // Validate ownership
        if ($item->order->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Validate order approved
        if ($item->order->status !== 'approved') {
            return response()->json(['error' => 'Order not approved'], 403);
        }

        // Prevent duplicate review
        if ($item->reviewItem) {
            return response()->json(['error' => 'Already reviewed'], 400);
        }

        $review = ReviewItem::create([
            'order_item_id' => $item->id,
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);

        return response()->json($review, 201);
    }

    
    // UPDATE REVIEW
    public function update(Request $request, $id)
    {
        $review = ReviewItem::with('orderItem.order')->findOrFail($id);

        if ($review->orderItem->order->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'rating' => 'integer|min:1|max:5',
            'comment' => 'nullable|string'
        ]);

        $review->update($request->only('rating', 'comment'));

        return response()->json($review);
    }

    // GET ALL REVIEWS FOR A PRODUCT
    public function reviewsForProduct($fabricId)
    {
        $reviews = ReviewItem::whereHas('orderItem', function ($q) use ($fabricId) {
            $q->where('fabric_id', $fabricId);
        })
        ->with('orderItem.order.user')
        ->latest()
        ->get();

        return response()->json($reviews);
    }
}
