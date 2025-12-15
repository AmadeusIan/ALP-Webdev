<x-app-layout>
    <div class="max-w-4xl mx-auto py-10">

        <h1 class="text-2xl font-bold mb-6">
            Order #{{ $order->order_number }}
        </h1>

        <p class="mb-4">
            Status:
            <span class="font-semibold">{{ strtoupper($order->status) }}</span>
        </p>

        <div class="space-y-6">

            @foreach ($order->items as $item)
                <div class="border p-4 rounded">

                    <h2 class="font-semibold">
                        {{ $item->fabric->name }}
                    </h2>

                    <p class="text-sm text-gray-500">
                        Quantity: {{ $item->quantity }} meter
                    </p>

                    {{-- ================= REVIEW LOGIC ================= --}}
                    @if ($order->status === 'approved')

                        @if ($item->reviewItem)
                            <p class="mt-2 text-green-600">
                                ✔ You already reviewed this item
                            </p>
                        @else
                            {{-- REVIEW FORM --}}
                            <form
                                method="POST"
                                action="{{ route('review.store') }}"
                                class="mt-4 space-y-2"
                            >
                                @csrf

                                <input type="hidden" name="order_item_id" value="{{ $item->id }}">

                                <select name="rating" class="border rounded px-2 py-1" required>
                                    <option value="">Rating</option>
                                    @for ($i = 1; $i <= 5; $i++)
                                        <option value="{{ $i }}">{{ $i }} ⭐</option>
                                    @endfor
                                </select>

                                <textarea
                                    name="comment"
                                    class="border rounded w-full p-2"
                                    placeholder="Your review (optional)"
                                ></textarea>

                                <button
                                    type="submit"
                                    class="bg-black text-white px-4 py-2 rounded"
                                >
                                    Submit Review
                                </button>
                            </form>
                        @endif

                    @else
                        <p class="mt-2 text-gray-400 italic">
                            Review available after order is approved
                        </p>
                    @endif
                </div>
            @endforeach

        </div>
    </div>
</x-app-layout>
