<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                {{ __('Rental Booking') }}
            </h2>
            <a href="{{ route('dashboard') }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600 transition underline decoration-gray-300 underline-offset-4">
                &larr; Back to Catalog
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.04)] sm:rounded-2xl border border-gray-100">
                
                <div class="bg-white p-8 border-b border-gray-100">
                    <span class="text-xs font-bold tracking-widest text-indigo-500 uppercase mb-2 block">Selected Item</span>
                    <h3 class="text-3xl font-black text-gray-900 mb-2">{{ $fabric->name }}</h3>
                    <div class="flex items-baseline gap-2">
                        <span class="text-gray-900 font-bold text-2xl">
                            Rp {{ number_format($fabric->price_per_meter, 0, ',', '.') }}
                        </span>
                        <span class="text-gray-400 text-sm font-medium">/ meter / hari</span>
                    </div>
                </div>

                <div class="p-8 bg-white">
                    <form method="POST" action="{{ route('orders.store') }}">
                        @csrf
                        <input type="hidden" name="fabric_id" value="{{ $fabric->id }}">

                        <div class="grid grid-cols-2 gap-6 mb-8">
                            <div class="group">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Start Date</label>
                                <input type="date" name="start_date" 
                                    class="w-full bg-white border border-gray-200 text-gray-900 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent block p-3 shadow-sm transition group-hover:border-gray-300" 
                                    required>
                            </div>
                            <div class="group">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Return Date</label>
                                <input type="date" name="end_date" 
                                    class="w-full bg-white border border-gray-200 text-gray-900 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent block p-3 shadow-sm transition group-hover:border-gray-300" 
                                    required>
                            </div>
                        </div>

                        <div class="mb-8">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Quantity Needed</label>
                            <div class="relative">
                                <input type="number" name="quantity" 
                                    class="w-full bg-white border border-gray-200 text-gray-900 text-lg font-medium rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent block p-3 pr-12 shadow-sm" 
                                    min="1" max="{{ $fabric->stock_meter }}" placeholder="0" required>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                    <span class="text-gray-400 font-bold text-sm">meter</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 mt-2">
                                <div class="w-2 h-2 rounded-full bg-green-500"></div>
                                <p class="text-xs text-gray-500 font-medium">
                                    Stock Available: {{ $fabric->stock_meter }} meters
                                </p>
                            </div>
                        </div>

                        <div class="mb-10">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Notes (Optional)</label>
                            <textarea name="note" rows="3" 
                                class="w-full bg-white border border-gray-200 text-gray-900 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent block p-3 shadow-sm resize-none" 
                                placeholder="Any specific requirements..."></textarea>
                        </div>

                        <button type="submit" class="w-full bg-gray-900 hover:bg-black text-white font-bold py-4 px-4 rounded-xl shadow-lg hover:shadow-xl transform transition hover:-translate-y-0.5 duration-200">
                            Confirm Booking
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>