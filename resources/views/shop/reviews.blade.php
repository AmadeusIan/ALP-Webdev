<x-app-layout>
    <div class="w-full min-h-screen bg-white py-20 px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h1 class="text-5xl font-serif font-bold text-gray-900 mb-4 uppercase tracking-wide">Client Stories</h1>
            <div class="w-12 h-0.5 bg-black mx-auto mt-2"></div>
            <p class="text-gray-500 italic font-serif mt-4">What our clients say about Kana Covers</p>
        </div>

        {{-- AVERAGE RATING --}}
        <div class="text-center mb-12">
            <p class="text-xl font-bold text-gray-900">
                ⭐ Average Rating: {{ $averageRating }}/5
            </p>
            <p class="text-gray-500 text-sm">({{ $totalReviews }} reviews)</p>
        </div>

        {{-- FORM REVIEW UNTUK USER YANG BERHAK --}}
        @if($hasPurchased && !$userReview)
            <div class="max-w-3xl mx-auto mb-12 p-6 bg-gray-900 border border-gray-800 rounded-xl shadow-md text-white">
                <h2 class="text-lg font-bold mb-4">Submit Your Review</h2>
                <form action="{{ route('review.shop.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block font-semibold mb-1">Rating</label>
                        <select name="rating" required class="border border-gray-700 rounded px-2 py-1 w-32 bg-gray-800 text-white">
                            <option value="">Select rating</option>
                            @for($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}">{{ $i }} ⭐</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label class="block font-semibold mb-1">Comment (optional)</label>
                        <textarea name="comment" class="border border-gray-700 rounded w-full p-2 bg-gray-800 text-white" placeholder="Your thoughts about our shop"></textarea>
                    </div>
                    <button type="submit" class="bg-black text-white px-6 py-2 rounded-lg font-bold hover:bg-gray-800 transition">
                        Submit Review
                    </button>
                </form>
            </div>
        @elseif($userReview)
            <p class="text-yellow-400 text-center mb-12 font-bold">
                You already reviewed the shop ⭐ {{ $userReview->rating }}/5
            </p>
        @else
            <p class="text-gray-500 text-center mb-12">Purchase a product first to review the shop.</p>
        @endif

        {{-- LIST REVIEW --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
            @forelse($reviews as $review)
                <div class="bg-gray-900 p-6 rounded-xl border border-gray-800 shadow-lg flex flex-col text-white hover:scale-105 transition-transform duration-300">
                    <div class="flex items-center mb-2">
                        <p class="font-bold mr-2">{{ $review->user->name ?? 'Happy Client' }}</p>
                        <p class="text-yellow-400 font-semibold">⭐ {{ $review->rating }}/5</p>
                    </div>
                    @if($review->comment)
                        <p class="italic mb-4 flex-1">"{{ $review->comment }}"</p>
                    @endif
                    <p class="text-xs text-gray-400 mt-auto">{{ $review->created_at->format('d M Y') }}</p>
                </div>
            @empty
                <div class="col-span-full text-center py-20">
                    <p class="text-gray-500 italic font-serif text-xl">No reviews yet. Be the first to share your experience!</p>
                </div>
            @endforelse
        </div>

        {{-- BUTTON VIEW ALL PRODUCTS --}}
        <div class="text-center mt-16">
            <a href="{{ route('fabrics.index') }}"
               class="inline-block border-b-2 border-black pb-1 text-sm font-bold uppercase tracking-widest hover:text-gray-600 hover:border-gray-600 transition">
                View All Products
            </a>
        </div>
    </div>
</x-app-layout>
