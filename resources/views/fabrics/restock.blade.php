<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Manage Stock: {{ $fabric->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <div class="mb-6 bg-blue-50 p-4 rounded border border-blue-200">
                    <p class="text-sm text-blue-800">Current Stock: <span
                            class="font-bold text-lg">{{ $fabric->stock_meter }} Meters</span></p>
                </div>

                <form method="POST" action="{{ route('fabrics.updateStock', $fabric->id) }}">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Change Type</label>
                        <select name="change_type" class="w-full border rounded px-3 py-2">
                            <option value="restock">Restock (Barang Masuk +)</option>
                            <option value="damage">Rented (Barang Keluar -)</option>
                            <option value="adjustment">Stock Opname / Koreksi</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Amount (Meters)</label>
                        <input type="number" name="change_amount" class="w-full border rounded px-3 py-2"
                            placeholder="Example: 50" required min="1">
                        <p class="text-xs text-gray-500 mt-1">Masukkan angka positif saja. Sistem akan otomatis
                            mengurangi jika tipe Sold/Damage.</p>
                    </div>

                    @error('change_amount')
                        <p class="text-red-600 text-sm mt-1 font-bold">
                            ⚠️ {{ $message }}
                        </p>
                    @enderror

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Note (Optional)</label>
                        <textarea name="note" class="w-full border rounded px-3 py-2" rows="2"
                            placeholder="Ex: Pembelian dari Supplier A"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('fabrics.index') }}" class="text-gray-600 hover:underline">Cancel</a>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Update Stock
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
