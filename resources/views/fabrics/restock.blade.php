<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Inventory') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-lg sm:rounded-2xl p-8 border border-gray-100">

                <div class="text-center mb-8">
                    <h3 class="text-lg font-medium text-gray-500 uppercase tracking-wide mb-1">Current Stock for</h3>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $fabric->name }}</h1>

                    <div
                        class="mt-6 inline-flex items-center bg-indigo-50 px-6 py-3 rounded-full border border-indigo-100">
                        <span class="text-indigo-600 font-bold text-3xl mr-2">{{ $fabric->stock_meter }}</span>
                        <span class="text-indigo-400 font-medium text-sm uppercase">Meters Available</span>
                    </div>
                </div>

                <form method="POST" action="{{ route('fabrics.updateStock', $fabric->id) }}" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Transaction Type</label>
                        <div class="relative">
                            <select name="change_type"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-3 pl-4 pr-10 appearance-none cursor-pointer">
                                <option value="restock">➕ Restock (Barang Masuk)</option>
                                <option value="damage">➖ Damage / Rusak (Barang Keluar)</option>
                                <option value="adjustment">🔧 Stock Opname (Koreksi)</option>
                            </select>
                            <div
                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Amount (Meters)</label>
                        <input type="number" name="change_amount"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-lg font-bold py-3 px-4 @error('change_amount') border-red-500 @enderror"
                            placeholder="0" required min="0">

                        @error('change_amount')
                            <p class="text-red-600 text-sm mt-2 font-medium flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Note / Reference</label>
                        <textarea name="note"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500" rows="2"
                            placeholder="Ex: PO Number, Damage Report, etc..."></textarea>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg text-xs text-gray-500 space-y-1 border border-gray-100">
                        <p>ℹ️ <b>Restock:</b> Adds to current stock.</p>
                        <p>ℹ️ <b>Damage:</b> Subtracts from current stock.</p>
                        <p>ℹ️ <b>Opname:</b> Overwrites current stock with new value.</p>
                    </div>

                    <div class="flex items-center gap-3 pt-4">
                        <a href="{{ route('fabrics.show', $fabric) }}"
                            class="flex-1 text-center py-3 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-bold transition">
                            Cancel
                        </a>
                        <button type="submit"
                            class="flex-1 bg-black text-white py-3 rounded-lg hover:bg-gray-800 shadow-lg transition font-bold">
                            Update Stock
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
