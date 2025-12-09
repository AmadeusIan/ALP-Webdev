<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Fabric') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg sm:rounded-2xl p-8 border border-gray-100">

                <h3 class="text-lg font-bold text-gray-900 mb-6 pb-4 border-b border-gray-100">Fabric Information</h3>

                <form method="POST" action="{{ route('fabrics.store') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block font-medium text-sm text-gray-700 mb-1">Fabric Name</label>
                        <input type="text" name="name"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition"
                            placeholder="Ex: Premium Silk" required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block font-medium text-sm text-gray-700 mb-1">Category</label>
                            <select name="category_id"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 cursor-pointer"
                                required>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block font-medium text-sm text-gray-700 mb-1">Supplier</label>
                            <select name="supplier_id"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 cursor-pointer"
                                required>
                                @foreach ($suppliers as $sup)
                                    <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block font-medium text-sm text-gray-700 mb-1">Color</label>
                            <input type="text" name="color"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Ex: Navy Blue">
                        </div>
                        <div>
                            <label class="block font-medium text-sm text-gray-700 mb-1">Material</label>
                            <input type="text" name="material"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Ex: 100% Cotton">
                        </div>
                    </div>

                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block font-bold text-sm text-gray-700 mb-1">Price / meter (Rp)</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                                <input type="number" name="price_per_meter"
                                    class="w-full pl-10 border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 font-bold text-gray-800"
                                    placeholder="0" required>
                            </div>
                        </div>
                        <div>
                            <label class="block font-bold text-sm text-gray-700 mb-1">Initial Stock (m)</label>
                            <input type="number" name="stock_meter"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 font-bold text-gray-800"
                                placeholder="0" required>
                        </div>
                    </div>

                    <div>
                        <label class="block font-medium text-sm text-gray-700 mb-1">Description</label>
                        <textarea name="description"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500" rows="3"
                            placeholder="Add details about texture, usage, etc..."></textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                        <a href="{{ route('fabrics.index') }}"
                            class="px-6 py-2.5 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition">Cancel</a>
                        <button type="submit"
                            class="px-6 py-2.5 bg-black text-white rounded-lg hover:bg-gray-800 shadow-lg transition font-bold">Save
                            Fabric</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
