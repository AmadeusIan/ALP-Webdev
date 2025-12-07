<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">My Orders</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if (session('success'))
                    <div class="bg-green-100 text-green-700 p-4 rounded mb-4">{{ session('success') }}</div>
                @endif

                <table class="min-w-full bg-white border">
                    <thead>
                        <tr class="bg-gray-100 text-left">
                            <th class="py-2 px-4">Order ID</th>
                            <th class="py-2 px-4">Fabric</th>
                            <th class="py-2 px-4">Dates</th>
                            <th class="py-2 px-4">Total</th>
                            <th class="py-2 px-4">Status</th>

                            @if (Auth::user()->role === 'admin')
                                <th class="py-2 px-4">Action</th>
                                @if (Auth::user()->role === 'admin')
                                    <th class="py-2 px-4">Action</th>
                                @endif
                            @endif
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($orders as $order)
                            <tr class="border-b hover:bg-gray-50 transition">

                                <td class="py-3 px-4 text-sm font-mono font-bold text-gray-700">
                                    {{ $order->order_number }}
                                </td>

                                <td class="py-3 px-4">
                                    <ul class="list-disc list-inside text-sm text-gray-600">
                                        @foreach ($order->items as $item)
                                            <li>
                                                <span class="font-semibold">{{ $item->fabric->name }}</span>
                                                ({{ $item->quantity }}m)
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>

                                <td class="py-3 px-4 text-sm">
                                    <div class="font-medium text-gray-800">
                                        {{ \Carbon\Carbon::parse($order->start_date)->format('d M Y') }}
                                        <span class="text-gray-400 mx-1">to</span>
                                        {{ \Carbon\Carbon::parse($order->end_date)->format('d M Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        Duration: {{ $order->total_days }} days
                                    </div>
                                </td>

                                <td class="py-3 px-4 font-bold text-indigo-600">
                                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                </td>

                                <td class="py-3 px-4">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                            'approved' => 'bg-green-100 text-green-800 border-green-200',
                                            'rejected' => 'bg-red-100 text-red-800 border-red-200',
                                            'returned' => 'bg-gray-100 text-gray-800 border-gray-200',
                                        ];
                                        $color = $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span
                                        class="{{ $color }} border px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wide">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                @if (Auth::user()->role === 'admin')
                                    <td class="py-3 px-4">
                                        @if ($order->status === 'pending')
                                            <div class="flex items-center gap-2">

                                                <form action="{{ route('orders.approve', $order) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <button type="submit"
                                                        class="bg-green-600 text-white px-3 py-1.5 rounded text-xs font-bold hover:bg-green-700 transition shadow-sm">
                                                        ✓ Accept
                                                    </button>
                                                </form>

                                                <form action="{{ route('orders.reject', $order) }}" method="POST"
                                                    onsubmit="return confirm('Are you sure you want to reject this order?')">
                                                    @csrf @method('PATCH')
                                                    <button type="submit"
                                                        class="bg-red-600 text-white px-3 py-1.5 rounded text-xs font-bold hover:bg-red-700 transition shadow-sm">
                                                        ✕ Reject
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400 italic">No actions available</span>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-8 text-gray-500">
                                    You have no orders yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</x-app-layout>
