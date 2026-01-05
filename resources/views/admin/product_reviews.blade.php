<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Product Reviews Moderation
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            @if(session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 shadow-sm rounded-r">
                    <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900">Pending Product Reviews</h3>
                </div>
                <div class="p-6">
                    @forelse($pending as $rev)
                        <div class="p-4 border border-gray-100 rounded-lg mb-4">
                            <div class="flex items-start justify-between">
                                <div class="flex-1 pr-4">
                                    <p class="font-bold text-gray-900">{{ $rev->orderItem->order->user->name ?? 'User' }}</p>
                                    <p class="text-yellow-600 font-semibold">⭐ {{ $rev->rating }}/5</p>
                                    @if($rev->comment)
                                        <p class="text-gray-700 italic mt-2">"{{ $rev->comment }}"</p>
                                    @endif
                                    <p class="text-xs text-gray-400 mt-2">{{ $rev->created_at->format('d M Y H:i') }}</p>
                                </div>
                                <div class="flex gap-2">
                                    <form method="POST" action="{{ route('admin.productReviews.approve', $rev->id) }}">
                                        @csrf
                                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Approve</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.productReviews.reject', $rev->id) }}">
                                        @csrf
                                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Reject</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500">No pending product reviews.</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900">Approved Product Reviews</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($approved as $rev)
                        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                            <div class="flex items-center mb-2">
                                <p class="font-bold mr-2 text-gray-900">{{ $rev->orderItem->order->user->name ?? 'User' }}</p>
                                <p class="text-yellow-600 font-semibold">⭐ {{ $rev->rating }}/5</p>
                            </div>
                            @if($rev->comment)
                                <p class="italic mb-4 text-gray-700">"{{ $rev->comment }}"</p>
                            @endif
                            <p class="text-xs text-gray-400">{{ $rev->created_at->format('d M Y') }}</p>
                        </div>
                    @empty
                        <p class="text-gray-500">No approved product reviews yet.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
