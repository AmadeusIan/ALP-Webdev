<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Booking Form</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <h3 class="text-lg font-bold mb-4">Rent: {{ $fabric->name }}</h3>
                <p class="text-gray-500 mb-6">Price: Rp {{ number_format($fabric->price_per_meter) }} / meter / day</p>

                <form method="POST" action="{{ route('orders.store') }}">
                    @csrf
                    <input type="hidden" name="fabric_id" value="{{ $fabric->id }}">

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700">Start Date</label>
                            <input type="date" name="start_date" class="w-full border rounded px-3 py-2" required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700">Return Date</label>
                            <input type="date" name="end_date" class="w-full border rounded px-3 py-2" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700">Quantity (Meters)</label>
                        <input type="number" name="quantity" class="w-full border rounded px-3 py-2" min="1" max="{{ $fabric->stock_meter }}" required>
                        <p class="text-xs text-gray-500 mt-1">Available Stock: {{ $fabric->stock_meter }} m</p>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700">Notes (Event Type etc)</label>
                        <textarea name="note" class="w-full border rounded px-3 py-2" rows="2"></textarea>
                    </div>

                    <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded hover:bg-indigo-700 font-bold">
                        Confirm Booking
                    </button>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>