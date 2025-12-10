<x-app-layout>
    @push('styles')
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=cinzel:400,700|lato:400,700" rel="stylesheet" />
    @endpush

    <div class="min-h-screen bg-stone-50 pb-20">
        
        <div class="bg-black text-white pt-32 pb-24 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
            
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none"></div>
            
            <div class="max-w-7xl mx-auto relative z-10">
                <div class="flex flex-col md:flex-row justify-between items-end gap-6">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-[0.3em] mb-2">
                            Customer Portal
                        </p>
                        <h2 class="font-serif text-4xl md:text-5xl font-bold text-white uppercase tracking-widest leading-none">
                            Order History
                        </h2>
                    </div>
                    
                    <div class="flex gap-8 text-right">
                        <div>
                            <span class="block text-2xl font-serif text-white">{{ $orders->count() }}</span>
                            <span class="text-[10px] font-bold text-white uppercase tracking-widest">Total Orders</span>
                        </div>
                        <div>
                            <span class="block text-2xl font-serif text-white">{{ $orders->where('status', 'pending')->count() }}</span>
                            <span class="text-[10px] font-bold text-white uppercase tracking-widest">Pending</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-12 relative z-20">
            
            @if (session('success'))
                <div class="mb-6 p-4 bg-white border-l-4 border-black shadow-lg flex justify-between items-center animate-fade-in-up">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="text-xs font-bold uppercase tracking-widest text-gray-900">{{ session('success') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-gray-400 hover:text-black">&times;</button>
                </div>
            @endif

            <div class="bg-white shadow-[0_20px_50px_rgba(0,0,0,0.05)] border border-stone-100">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-stone-50 border-b border-stone-200">
                            <tr>
                                <th class="py-5 px-6 font-sans text-[10px] font-bold text-gray-500 uppercase tracking-[0.2em]">Order Ref</th>
                                <th class="py-5 px-6 font-sans text-[10px] font-bold text-gray-500 uppercase tracking-[0.2em]">Items Collection</th>
                                <th class="py-5 px-6 font-sans text-[10px] font-bold text-gray-500 uppercase tracking-[0.2em]">Duration</th>
                                <th class="py-5 px-6 font-sans text-[10px] font-bold text-gray-500 uppercase tracking-[0.2em]">Total Bill</th>
                                <th class="py-5 px-6 font-sans text-[10px] font-bold text-gray-500 uppercase tracking-[0.2em]">Status</th>
                                
                                @if (Auth::user()->role === 'admin')
                                    <th class="py-5 px-6 font-sans text-[10px] font-bold text-gray-500 uppercase tracking-[0.2em] text-right">Control</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100">
                            @forelse($orders as $order)
                                <tr class="group hover:bg-stone-50/50 transition duration-300">
                                    
                                    <td class="py-6 px-6 align-top">
                                        <span class="font-serif text-lg font-bold text-gray-900">
                                            #{{ $order->order_number ?? str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
                                        </span>
                                        <div class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-1">
                                            {{ $order->created_at->format('d M Y') }}
                                        </div>
                                    </td>

                                    <td class="py-6 px-6 align-top">
                                        <ul class="space-y-3">
                                            @foreach ($order->items as $item)
                                                <li class="flex items-start gap-3">
                                                    <div class="w-8 h-8 bg-stone-200 rounded-none shrink-0 overflow-hidden">
                                                        @if($item->fabric->image)
                                                            <img src="{{ asset($item->fabric->image) }}" class="w-full h-full object-cover">
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <span class="block font-serif text-sm text-gray-900 leading-none mb-1">
                                                            {{ $item->fabric->name }}
                                                        </span>
                                                        <span class="block font-sans text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                                                            {{ $item->quantity }} Meters
                                                        </span>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>

                                    <td class="py-6 px-6 align-top">
                                        <div class="flex flex-col">
                                            <span class="font-sans text-xs font-bold text-gray-900 uppercase tracking-wider mb-1">
                                                {{ \Carbon\Carbon::parse($order->start_date)->format('M d') }} 
                                                <span class="text-stone-300 mx-1">&rarr;</span> 
                                                {{ \Carbon\Carbon::parse($order->end_date)->format('M d') }}
                                            </span>
                                            <span class="font-sans text-[10px] text-gray-400 italic">
                                                {{ $order->total_days }} Days
                                            </span>
                                        </div>
                                    </td>

                                    <td class="py-6 px-6 align-top">
                                        <span class="font-serif text-base font-bold text-gray-900">
                                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                        </span>
                                    </td>

                                    <td class="py-6 px-6 align-top">
                                        @php
                                            $statusStyles = [
                                                'pending'  => 'bg-stone-100 text-stone-600 border-stone-200',
                                                'approved' => 'bg-black text-white border-black',
                                                'rejected' => 'bg-white text-red-900 border-red-200 line-through decoration-red-900/30',
                                                'returned' => 'bg-green-50 text-green-800 border-green-100',
                                            ];
                                            $style = $statusStyles[$order->status] ?? 'bg-gray-50 text-gray-400';
                                        @endphp
                                        <span class="inline-block px-3 py-1 text-[10px] font-bold uppercase tracking-[0.15em] border {{ $style }}">
                                            {{ $order->status }}
                                        </span>
                                    </td>

                                    @if (Auth::user()->role === 'admin')
                                        <td class="py-6 px-6 align-top text-right">
                                            @if ($order->status === 'pending')
                                                <div class="flex justify-end gap-2">
                                                    <form action="{{ route('orders.approve', $order) }}" method="POST">
                                                        @csrf @method('PATCH')
                                                        <button type="submit" class="w-8 h-8 flex items-center justify-center border border-black text-black hover:bg-black hover:text-white transition duration-300" title="Approve">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('orders.reject', $order) }}" method="POST" onsubmit="return confirm('Reject this order?')">
                                                        @csrf @method('PATCH')
                                                        <button type="submit" class="w-8 h-8 flex items-center justify-center border border-stone-200 text-stone-400 hover:border-red-500 hover:text-red-500 transition duration-300" title="Reject">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            @else
                                                <span class="text-stone-300 text-lg">&bull;</span>
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-32 text-center">
                                        <div class="flex flex-col items-center justify-center opacity-50">
                                            <div class="w-16 h-16 border border-stone-300 rounded-full flex items-center justify-center mb-6">
                                                <svg class="w-6 h-6 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                            </div>
                                            <h3 class="font-serif text-xl text-gray-900 mb-2">No Orders Found</h3>
                                            <a href="{{ route('fabrics.index') }}" class="mt-4 text-xs font-bold uppercase tracking-widest text-black border-b border-black pb-1 hover:opacity-70 transition">
                                                Start Shopping
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="text-center mt-12">
                <p class="text-xs text-stone-400 font-sans">
                    Showing all transaction history for {{ Auth::user()->name }}
                </p>
            </div>
            
        </div>
    </div>

    <style>
        .font-serif { font-family: 'Cinzel', serif; }
        .font-sans { font-family: 'Lato', sans-serif; }
        .animate-fade-in-up { animation: fadeInUp 0.5s ease-out forwards; }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</x-app-layout>