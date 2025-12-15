<x-app-layout>
    <div class="max-w-2xl mx-auto py-10">
        <h1 class="text-2xl font-bold mb-6">Review Our Shop</h1>

        <form action="{{ route('review.shop.store') }}" method="POST" class="space-y-4">
            @csrf

            <label class="block font-semibold">Rating</label>
            <select name="rating" required class="border rounded px-2 py-1 w-32">
                <option value="">Select rating</option>
                @for($i = 1; $i <= 5; $i++)
                    <option value="{{ $i }}">{{ $i }} ⭐</option>
                @endfor
            </select>

            <label class="block font-semibold">Comment (optional)</label>
            <textarea name="comment" class="border rounded w-full p-2" placeholder="Your thoughts about our shop"></textarea>

            <button type="submit" class="bg-black text-white px-4 py-2 rounded">
                Submit Review
            </button>
        </form>
    </div>
</x-app-layout>
