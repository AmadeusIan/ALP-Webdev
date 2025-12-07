<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Fabric Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <div class="flex flex-col md:flex-row gap-8">
                    <div
                        class="w-full md:w-1/3 bg-gray-200 h-64 rounded-lg flex items-center justify-center text-gray-500">
                        No Image
                    </div>

                    <div class="w-full md:w-2/3">
                        <h1 class="text-3xl font-bold text-gray-900">{{ $fabric->name }}</h1>
                        <p class="text-sm text-gray-500 mb-4">Category: {{ $fabric->category->name }}</p>

                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div>
                                <span class="block text-gray-600 text-sm">Color</span>
                                <span class="font-semibold">{{ $fabric->color }}</span>
                            </div>
                            <div>
                                <span class="block text-gray-600 text-sm">Material</span>
                                <span class="font-semibold">{{ $fabric->material }}</span>
                            </div>
                            <div>
                                <span class="block text-gray-600 text-sm">Supplier</span>
                                <span class="font-semibold text-blue-600">{{ $fabric->supplier->name ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="block text-gray-600 text-sm">Current Stock</span>
                                <span class="font-bold text-green-600 text-lg">{{ $fabric->stock_meter }} Meters</span>
                            </div>
                        </div>

                        <div class="mb-6">
                            <span class="block text-gray-600 text-sm">Price</span>
                            <span class="text-2xl font-bold text-blue-700">Rp
                                {{ number_format($fabric->price_per_meter, 0, ',', '.') }} / meter</span>
                        </div>

                        <div class="mb-6">
                            <h3 class="font-semibold text-gray-800">Description</h3>
                            <p class="text-gray-600">{{ $fabric->description ?? 'No description available.' }}</p>
                        </div>

                        @if (Auth::user()->role === 'admin')
                            <div class="flex gap-3 border-t pt-4">
                                <a href="{{ route('fabrics.edit', $fabric) }}"
                                    class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Edit Info</a>
                                <a href="{{ route('fabrics.restock', $fabric) }}"
                                    class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Manage
                                    Stock</a>

                                <form action="{{ route('fabrics.destroy', $fabric) }}" method="POST"
                                    onsubmit="return confirm('Delete this fabric?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Delete</button>
                                </form>
                            </div>
                        @endif

                        @if (Auth::user()->role !== 'admin')
                            <div class="mt-6">
                                <a href="{{ route('orders.create', $fabric) }}"
                                    class="block w-full bg-indigo-600 text-white text-center py-3 rounded-lg font-bold hover:bg-indigo-700 shadow-lg">
                                    Rent This Fabric
                                </a>
                            </div>
                        @endif

                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
