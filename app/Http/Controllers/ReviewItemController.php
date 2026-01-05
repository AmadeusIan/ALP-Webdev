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
        'comment' => 'nullable|string',
    ]);

    $item = OrderItem::with('order', 'reviewItem')
        ->findOrFail($request->order_item_id);

    // 1. Ownership check
    abort_if($item->order->user_id !== Auth::id(), 403);

    // 2. Order must be approved
    if ($item->order->status !== 'approved') {
        return back()->with('error', 'Order belum disetujui');
    }

    // 3. Prevent double review
    if ($item->reviewItem) {
        return back()->with('error', 'Item sudah direview');
    }

    ReviewItem::create([
        'order_item_id' => $item->id,
        'rating' => $request->rating,
        'comment' => $request->comment,
        'status' => 'pending',
    ]);

    return back()->with('success', 'Review berhasil dikirim');
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
        $reviews = ReviewItem::where('status', 'approved')
            ->whereHas('orderItem', function ($q) use ($fabricId) {
                $q->where('fabric_id', $fabricId);
            })
            ->with('orderItem.order.user')
            ->latest()
            ->get();

        return response()->json($reviews);
    }

    // ADMIN: List and moderate product reviews
    public function adminIndex()
    {
        $pending = ReviewItem::with('orderItem.order.user')
            ->where('status', 'pending')
            ->latest()
            ->get();

        $approved = ReviewItem::with('orderItem.order.user')
            ->where('status', 'approved')
            ->latest()
            ->get();

        return view('admin.product_reviews', compact('pending', 'approved'));
    }

    public function approve(ReviewItem $review)
    {
        $review->update(['status' => 'approved']);
        return back()->with('success', 'Product review approved.');
    }

    public function reject(ReviewItem $review)
    {
        $review->update(['status' => 'rejected']);
        return back()->with('success', 'Product review rejected.');
    }
}
