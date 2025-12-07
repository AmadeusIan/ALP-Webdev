<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Fabric: {{ $fabric->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <form method="POST" action="{{ route('fabrics.update', $fabric) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Fabric Name</label>
                        <input type="text" name="name" value="{{ $fabric->name }}" class="w-full border rounded px-3 py-2" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Category</label>
                        <select name="category_id" class="w-full border rounded px-3 py-2">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ $fabric->category_id == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Color</label>
                            <input type="text" name="color" value="{{ $fabric->color }}" class="w-full border rounded px-3 py-2">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Material</label>
                            <input type="text" name="material" value="{{ $fabric->material }}" class="w-full border rounded px-3 py-2">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Price per Meter</label>
                        <input type="number" name="price_per_meter" value="{{ $fabric->price_per_meter }}" class="w-full border rounded px-3 py-2" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                        <textarea name="description" class="w-full border rounded px-3 py-2" rows="3">{{ $fabric->description }}</textarea>
                    </div>

                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('fabrics.index') }}" class="text-gray-600 hover:underline">Cancel</a>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Save Changes
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>