<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Shopping Cart') }}
        </h2>
    </x-slot>

    @vite(['resources/js/cart.js']) 

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 shadow-sm rounded-r">
                    <p class="text-sm text-green-700 font-bold flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        {{ session('success') }}
                    </p>
                </div>
            @endif

            @if (session('cart'))
                <form action="{{ route('orders.storeCart') }}" method="POST" id="checkoutForm">
                    @csrf
                    
                    <div class="flex flex-col lg:flex-row gap-8">
                        
                        <div class="lg:w-2/3">
                            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                                <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                                    <h3 class="text-lg font-bold text-gray-900">Cart Items</h3>
                                    <span class="bg-indigo-100 text-indigo-800 text-xs font-bold px-2 py-1 rounded-full">{{ count(session('cart')) }} Items</span>
                                </div>

                                <div class="divide-y divide-gray-100">
                                    @php $baseTotal = 0; @endphp
                                    @foreach (session('cart') as $id => $details)
                                        @php
                                            $lineTotal = $details['price'] * $details['quantity'];
                                            $baseTotal += $lineTotal;
                                        @endphp

                                        <div class="p-6 flex flex-col sm:flex-row items-center gap-6 group hover:bg-gray-50 transition">
                                            <div class="w-20 h-20 flex-shrink-0 bg-indigo-50 rounded-lg flex items-center justify-center text-indigo-300 group-hover:bg-white group-hover:border group-hover:border-indigo-100 transition">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>

                                            <div class="flex-1 w-full text-center sm:text-left">
                                                <h4 class="text-lg font-bold text-gray-900">{{ $details['name'] }}</h4>
                                                <p class="text-sm text-gray-500 font-medium">Rp {{ number_format($details['price'], 0, ',', '.') }} / m</p>
                                            </div>

                                            <div class="flex flex-col items-center">
                                                <label class="text-[10px] uppercase font-bold text-gray-400 mb-1">Meters</label>
                                                <input type="number" value="{{ $details['quantity'] }}"
                                                    class="w-20 border-gray-300 rounded-lg text-center font-bold text-gray-800 focus:ring-indigo-500 focus:border-indigo-500 update-cart"
                                                    min="1" max="{{ $details['max_stock'] }}"
                                                    data-id="{{ $id }}">
                                            </div>

                                            <div class="text-right min-w-[120px] hidden sm:block">
                                                <p class="text-lg font-bold text-indigo-600">Rp {{ number_format($lineTotal, 0, ',', '.') }}</p>
                                                <button type="button" onclick="document.getElementById('delete-form-{{ $id }}').submit()"
                                                    class="text-xs text-red-400 hover:text-red-600 font-bold mt-1 transition">REMOVE</button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            
                            <a href="{{ route('fabrics.index') }}" class="inline-flex items-center mt-6 text-sm font-bold text-gray-500 hover:text-indigo-600 transition">
                                &larr; Continue Shopping
                            </a>
                        </div>

                        <div class="lg:w-1/3">
                            <div class="bg-white rounded-xl shadow-lg border border-indigo-50 p-6 sticky top-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    Rental Details
                                </h3>

                                <div class="space-y-4 mb-6">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Start Date</label>
                                        <input type="date" name="start_date" id="start_date" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 font-medium" required>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Return Date</label>
                                        <input type="date" name="end_date" id="end_date" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 font-medium" required>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Notes</label>
                                        <textarea name="note" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm" rows="2" placeholder="Ex: Wedding decoration..."></textarea>
                                    </div>
                                </div>

                                <div class="border-t border-gray-100 my-4"></div>

                                <div class="space-y-2 text-sm text-gray-600 mb-6">
                                    <div class="flex justify-between">
                                        <span>Items Total</span>
                                        <span class="font-medium">Rp {{ number_format($baseTotal, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between items-center bg-indigo-50 p-2 rounded text-indigo-700 font-bold">
                                        <span>Duration</span>
                                        <span id="display_days">1 Day</span>
                                    </div>
                                </div>

                                <div class="flex justify-between items-end mb-6">
                                    <span class="text-gray-900 font-bold text-lg">Grand Total</span>
                                    <span id="display_total" class="text-2xl font-black text-indigo-600">Rp {{ number_format($baseTotal, 0, ',', '.') }}</span>
                                </div>

                                <button type="submit" class="w-full bg-black text-white py-4 rounded-xl font-bold text-lg hover:bg-gray-800 shadow-lg transition transform hover:-translate-y-1">
                                    Checkout Order
                                </button>
                            </div>
                        </div>

                    </div>
                </form>

                @foreach (session('cart') as $id => $details)
                    <form id="delete-form-{{ $id }}" action="{{ route('cart.remove') }}" method="POST" style="display: none;">
                        @csrf @method('DELETE')
                        <input type="hidden" name="id" value="{{ $id }}">
                    </form>
                @endforeach

            @else
                <div class="text-center py-24">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Cart is Empty</h3>
                    <p class="text-gray-500 mb-8">Looks like you haven't added any fabrics yet.</p>
                    <a href="{{ route('fabrics.index') }}" class="px-8 py-3 bg-black text-white rounded-lg font-bold hover:bg-gray-800 transition">Browse Fabrics</a>
                </div>
            @endif

        </div>
    </div>

    <div id="cart-data" 
         data-base-total="{{ $baseTotal ?? 0 }}" 
         data-update-url="{{ route('cart.update') }}"
         data-csrf="{{ csrf_token() }}">
    </div>

</x-app-layout>