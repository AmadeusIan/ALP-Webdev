<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight">
            {{ __('My Orders') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="mb-6 bg-white border border-green-200 text-green-700 px-6 py-4 rounded-xl shadow-[0_2px_10px_rgb(0,0,0,0.03)] flex items-center gap-3">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-[0_4px_20px_rgb(0,0,0,0.03)] sm:rounded-2xl border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-white">
                            <tr>
                                <th scope="col" class="px-6 py-5 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Order ID</th>
                                <th scope="col" class="px-6 py-5 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Item Detail</th>
                                <th scope="col" class="px-6 py-5 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Dates</th>
                                <th scope="col" class="px-6 py-5 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Total</th>
                                <th scope="col" class="px-6 py-5 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Status</th>
                                
                                @if (Auth::user()->role === 'admin')
                                    <th scope="col" class="px-6 py-5 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-50">
                            @forelse($orders as $order)
                                <tr class="hover:bg-gray-50 transition duration-150 group">
                                    <td class="px-6 py-5 whitespace-nowrap align-top">
                                        <span class="font-mono text-sm font-bold text-gray-900 group-hover:text-indigo-600 transition">
                                            #{{ $order->order_number ?? $order->id }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-5 align-top">
                                        <ul class="space-y-2">
                                            @foreach ($order->items as $item)
                                                <li class="flex flex-col">
                                                    <span class="font-bold text-gray-900 text-sm">{{ $item->fabric->name }}</span>
                                                    <span class="text-xs text-gray-500 font-medium bg-gray-50 inline-block px-2 py-0.5 rounded-md w-fit mt-1">
                                                        Qty: {{ $item->quantity }}m
                                                    </span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>

                                    <td class="px-6 py-5 whitespace-nowrap align-top">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-semibold text-gray-900">
                                                {{ \Carbon\Carbon::parse($order->start_date)->format('d M') }} 
                                                <span class="text-gray-300 mx-1">—</span> 
                                                {{ \Carbon\Carbon::parse($order->end_date)->format('d M Y') }}
                                            </span>
                                            <span class="text-xs text-gray-400 mt-1 font-medium">{{ $order->total_days }} days rental</span>
                                        </div>
                                    </td>

                                    <td class="px-6 py-5 whitespace-nowrap align-top">
                                        <span class="text-sm font-bold text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full">
                                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-5 whitespace-nowrap align-top">
                                        @php
                                            $statusStyles = [
                                                'pending'  => 'text-yellow-600 bg-yellow-50 border-yellow-100',
                                                'approved' => 'text-green-600 bg-green-50 border-green-100',
                                                'rejected' => 'text-red-600 bg-red-50 border-red-100',
                                                'returned' => 'text-blue-600 bg-blue-50 border-blue-100',
                                            ];
                                            $style = $statusStyles[$order->status] ?? 'text-gray-600 bg-gray-50 border-gray-100';
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $style }} uppercase tracking-wide">
                                            <span class="w-1.5 h-1.5 rounded-full bg-current mr-2 opacity-50"></span>
                                            {{ $order->status }}
                                        </span>
                                    </td>

                                    @if (Auth::user()?->role === 'admin')
                                        <td class="px-6 py-5 whitespace-nowrap text-sm font-medium align-top">
                                            @if ($order->status === 'pending')
                                                <div class="flex items-center gap-3">
                                                    <form action="{{ route('orders.approve', $order) }}" method="POST">
                                                        @csrf @method('PATCH')
                                                        <button type="submit" class="text-green-600 hover:text-green-800 font-bold text-xs bg-white border border-green-200 hover:border-green-400 px-3 py-1.5 rounded-lg transition">
                                                            Approve
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('orders.reject', $order) }}" method="POST" onsubmit="return confirm('Reject this order?')">
                                                        @csrf @method('PATCH')
                                                        <button type="submit" class="text-red-500 hover:text-red-700 font-bold text-xs hover:bg-red-50 px-3 py-1.5 rounded-lg transition">
                                                            Reject
                                                        </button>
                                                    </form>
                                                </div>
                                            @else
                                                <span class="text-gray-300 text-xs italic">Completed</span>
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                            </div>
                                            <p class="text-gray-500 font-medium">No orders found.</p>
                                            <p class="text-gray-400 text-sm mt-1">Ready to start renting?</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>