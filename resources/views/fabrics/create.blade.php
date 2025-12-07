<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Add New Fabric</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form method="POST" action="{{ route('fabrics.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Fabric Name</label>
                        <input type="text" name="name" class="border-gray-300 rounded-md shadow-sm w-full mt-1" required>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Category</label>
                            <select name="category_id" class="border-gray-300 rounded-md shadow-sm w-full mt-1" required>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block font-medium text-sm text-gray-700">Supplier</label>
                            <select name="supplier_id" class="border-gray-300 rounded-md shadow-sm w-full mt-1" required>
                                @foreach($suppliers as $sup)
                                    <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Color</label>
                            <input type="text" name="color" class="border-gray-300 rounded-md shadow-sm w-full mt-1">
                        </div>
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Material</label>
                            <input type="text" name="material" class="border-gray-300 rounded-md shadow-sm w-full mt-1">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Price / meter (Rp)</label>
                            <input type="number" name="price_per_meter" class="border-gray-300 rounded-md shadow-sm w-full mt-1" required>
                        </div>
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Initial Stock (m)</label>
                            <input type="number" name="stock_meter" class="border-gray-300 rounded-md shadow-sm w-full mt-1" required>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block font-medium text-sm text-gray-700">Description</label>
                        <textarea name="description" class="border-gray-300 rounded-md shadow-sm w-full mt-1" rows="3"></textarea>
                    </div>

                    <div class="flex justify-end gap-2">
                        <a href="{{ route('fabrics.index') }}" class="px-4 py-2 bg-gray-300 rounded-md text-gray-700 hover:bg-gray-400">Cancel</a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Save Fabric</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>