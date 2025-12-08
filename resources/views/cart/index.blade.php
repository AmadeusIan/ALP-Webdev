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
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            @endif

            @if (session('cart'))
                <form action="{{ route('orders.storeCart') }}" method="POST" id="checkoutForm">
                    @csrf

                    <div class="flex flex-col lg:flex-row gap-8">

                        <div class="lg:w-2/3">
                            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                                <div class="p-6 border-b border-gray-100">
                                    <h3 class="text-lg font-bold text-gray-900">Cart Items
                                        ({{ count(session('cart')) }})</h3>
                                </div>

                                <div class="divide-y divide-gray-100">
                                    @php $baseTotal = 0; @endphp
                                    @foreach (session('cart') as $id => $details)
                                        @php
                                            $lineTotal = $details['price'] * $details['quantity'];
                                            $baseTotal += $lineTotal;
                                        @endphp

                                        <div class="p-6 flex flex-col sm:flex-row items-center gap-6">
                                            <div
                                                class="w-24 h-24 flex-shrink-0 bg-indigo-50 rounded-lg flex items-center justify-center text-indigo-400">
                                                <svg class="w-10 h-10" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4">
                                                    </path>
                                                </svg>
                                            </div>

                                            <div class="flex-1 w-full text-center sm:text-left">
                                                <h4 class="text-lg font-bold text-gray-900">{{ $details['name'] }}</h4>
                                                <p class="text-sm text-gray-500">Rp
                                                    {{ number_format($details['price'], 0, ',', '.') }} / m</p>
                                            </div>

                                            <div class="flex flex-col items-center gap-2">
                                                <input type="number" value="{{ $details['quantity'] }}"
                                                    class="w-24 border-gray-300 rounded-lg text-center update-cart"
                                                    min="1" max="{{ $details['max_stock'] }}"
                                                    data-id="{{ $id }}">
                                            </div>

                                            <div class="text-right min-w-[120px] hidden sm:block">
                                                <p class="text-lg font-bold text-gray-900">Rp
                                                    {{ number_format($lineTotal, 0, ',', '.') }}</p>
                                                <button type="button"
                                                    onclick="document.getElementById('delete-form-{{ $id }}').submit()"
                                                    class="text-xs text-red-500 hover:underline mt-1">Remove
                                                    Item</button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="lg:w-1/3">
                            <div class="bg-white rounded-xl shadow-lg border border-indigo-50 p-6 sticky top-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-6">Rental Duration</h3>

                                <div class="space-y-4 mb-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                                        <input type="date" name="start_date" id="start_date"
                                            class="w-full border-gray-300 rounded-lg shadow-sm" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Return Date</label>
                                        <input type="date" name="end_date" id="end_date"
                                            class="w-full border-gray-300 rounded-lg shadow-sm" required>
                                    </div>
                                    <div>
                                        <textarea name="note" class="w-full border-gray-300 rounded-lg shadow-sm text-sm" rows="2"
                                            placeholder="Note..."></textarea>
                                    </div>
                                </div>

                                <div class="border-t border-gray-100 my-4"></div>

                                <div class="flex justify-between items-end mb-6">
                                    <span class="text-gray-900 font-bold text-lg">Total</span>
                                    <span id="display_total" class="text-2xl font-black text-indigo-600">Rp
                                        {{ number_format($baseTotal, 0, ',', '.') }}</span>
                                </div>

                                <div class="flex justify-between mb-4 text-sm text-gray-500">
                                    <span>Duration</span>
                                    <span id="display_days" class="font-bold text-indigo-600">1 Day</span>
                                </div>

                                <button type="submit"
                                    class="w-full bg-indigo-600 text-white py-4 rounded-xl font-bold hover:bg-indigo-700 shadow-lg">Checkout
                                    Now</button>
                            </div>
                        </div>

                    </div>
                </form>

                @foreach (session('cart') as $id => $details)
                    <form id="delete-form-{{ $id }}" action="{{ route('cart.remove') }}" method="POST"
                        style="display: none;">
                        @csrf @method('DELETE')
                        <input type="hidden" name="id" value="{{ $id }}">
                    </form>
                @endforeach
            @else
                <div class="text-center py-20">
                    <p class="text-gray-500 mb-6">Your cart is empty.</p>
                    <a href="{{ route('fabrics.index') }}" class="px-8 py-3 bg-indigo-600 text-white rounded-lg">Browse
                        Fabrics</a>
                </div>
            @endif

        </div>
    </div>

    <div id="cart-data" data-base-total="{{ $baseTotal ?? 0 }}" data-update-url="{{ route('cart.update') }}"
        data-csrf="{{ csrf_token() }}">
    </div>

</x-app-layout>
