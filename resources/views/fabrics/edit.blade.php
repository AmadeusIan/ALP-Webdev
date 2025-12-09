<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Fabric Information') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg sm:rounded-2xl p-8 border border-gray-100">

                <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900">Edit Details: {{ $fabric->name }}</h3>
                    <span class="bg-yellow-100 text-yellow-800 text-xs font-bold px-2 py-1 rounded">Admin Mode</span>
                </div>

                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4">
                        <p class="font-bold text-red-700">Please fix the following errors:</p>
                        <ul class="list-disc list-inside text-sm text-red-600 mt-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('fabrics.update', $fabric) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block font-medium text-sm text-gray-700 mb-1">Fabric Name</label>
                        <input type="text" name="name" value="{{ old('name', $fabric->name) }}"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition"
                            required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block font-medium text-sm text-gray-700 mb-1">Category</label>
                            <select name="category_id"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 cursor-pointer">
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}"
                                        {{ $fabric->category_id == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block font-medium text-sm text-gray-700 mb-1">Supplier</label>
                            <select name="supplier_id"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 cursor-pointer">
                                @foreach ($suppliers as $sup)
                                    <option value="{{ $sup->id }}"
                                        {{ $fabric->supplier_id == $sup->id ? 'selected' : '' }}>
                                        {{ $sup->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block font-medium text-sm text-gray-700 mb-1">Color</label>
                            <input type="text" name="color" value="{{ old('color', $fabric->color) }}"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block font-medium text-sm text-gray-700 mb-1">Material</label>
                            <input type="text" name="material" value="{{ old('material', $fabric->material) }}"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-sm text-gray-700 mb-1">Price per Meter (Rp)</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500 font-bold">Rp</span>
                            <input type="number" name="price_per_meter"
                                value="{{ old('price_per_meter', $fabric->price_per_meter) }}"
                                class="w-full pl-10 border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 font-bold text-gray-800"
                                required>
                        </div>
                    </div>

                    <div>
                        <label class="block font-medium text-sm text-gray-700 mb-1">Description</label>
                        <textarea name="description"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500" rows="4">{{ old('description', $fabric->description) }}</textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                        <a href="{{ route('fabrics.show', $fabric) }}"
                            class="px-6 py-2.5 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition">
                            Cancel
                        </a>
                        <button type="submit"
                            class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 shadow-md transition font-bold">
                            Save Changes
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
