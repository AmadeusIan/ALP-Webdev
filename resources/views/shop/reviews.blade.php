<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Client Stories
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="text-center mb-10">
                <h1 class="text-3xl font-bold text-gray-900">What our clients say</h1>
                <p class="text-gray-500 mt-2">Kana Covers reviews from verified buyers</p>
            </div>

            {{-- AVERAGE RATING --}}
            <div class="mb-8 bg-white p-6 rounded-xl shadow-sm border border-gray-100 text-center">
                <p class="text-xl font-bold text-gray-900">
                    ⭐ Average Rating: {{ $averageRating }}/5
                </p>
                <p class="text-gray-500 text-sm">({{ $totalReviews }} reviews)</p>
            </div>

            {{-- FORM REVIEW UNTUK USER YANG BERHAK --}}
            @if($hasPurchased && !$userReview)
                <div class="max-w-3xl mx-auto mb-10 p-6 bg-white border border-gray-100 rounded-xl shadow-sm">
                    <h2 class="text-lg font-bold mb-4 text-gray-900">Submit Your Review</h2>
                    <form action="{{ route('review.shop.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block font-semibold mb-1 text-gray-700">Rating</label>
                            <select name="rating" required class="border border-gray-300 rounded px-2 py-2 w-40 bg-white text-gray-900 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Select rating</option>
                                @for($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}">{{ $i }} ⭐</option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label class="block font-semibold mb-1 text-gray-700">Comment (optional)</label>
                            <textarea name="comment" class="border border-gray-300 rounded w-full p-3 bg-white text-gray-900 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Your thoughts about our shop"></textarea>
                        </div>
                        <button type="submit" class="bg-gray-900 text-white px-6 py-2 rounded-lg font-bold hover:bg-gray-800 transition">
                            Submit Review
                        </button>
                    </form>
                </div>
            @elseif($userReview)
                <p class="text-yellow-600 text-center mb-10 font-semibold">
                    You already reviewed the shop ⭐ {{ $userReview->rating }}/5
                </p>
            @else
                <p class="text-gray-500 text-center mb-10">Purchase a product first to review the shop.</p>
            @endif

            {{-- LIST REVIEW --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($reviews as $review)
                    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm flex flex-col hover:shadow-md transition">
                        <div class="flex items-center mb-2">
                            <p class="font-bold mr-2 text-gray-900">{{ $review->user->name ?? 'Happy Client' }}</p>
                            <p class="text-yellow-600 font-semibold">⭐ {{ $review->rating }}/5</p>
                        </div>
                        @if($review->comment)
                            <p class="italic mb-4 flex-1 text-gray-700">"{{ $review->comment }}"</p>
                        @endif
                        <p class="text-xs text-gray-400 mt-auto">{{ $review->created_at->format('d M Y') }}</p>
                    </div>
                @empty
                    <div class="col-span-full text-center py-20 bg-white rounded-xl border border-dashed border-gray-300">
                        <p class="text-gray-500 italic font-serif text-xl">No reviews yet. Be the first to share your experience!</p>
                    </div>
                @endforelse
            </div>

            {{-- BUTTON VIEW ALL PRODUCTS --}}
            <div class="text-center mt-12">
                <a href="{{ route('fabrics.index') }}"
                   class="inline-block border-b-2 border-black pb-1 text-sm font-bold uppercase tracking-widest hover:text-gray-600 hover:border-gray-600 transition">
                    View All Products
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
