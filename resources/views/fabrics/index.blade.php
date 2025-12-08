<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Fabric Catalog') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex justify-between mb-4">
                <form method="GET" action="{{ route('fabrics.index') }}" class="flex gap-2">
                    <input type="text" name="search" placeholder="Search fabric..." class="border rounded px-2 py-1" value="{{ request('search') }}">
                    <select name="category_id" class="border rounded px-2 pr-9 py-1">
                        <option value=""> Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="bg-gray-500 text-white px-4 py-1 rounded">Filter</button>
                </form>

                @if (Auth::user()?->role == 'admin')
                <a href="{{ route('fabrics.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    + Add Fabric
                </a>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($fabrics as $fabric)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                    <div class="h-40 bg-gray-200 mb-4 rounded"></div> <h3 class="font-bold text-lg">{{ $fabric->name }}</h3>
                    <p class="text-gray-500 text-sm">{{ $fabric->category->name ?? 'Uncategorized' }}</p>
                    <p class="text-sm">Color: {{ $fabric->color }} | {{ $fabric->material }}</p>
                    @if (Auth::user()?->role == 'admin')
                    <div class="mt-4 flex justify-between items-center">
                        <span class="text-blue-600 font-bold">Rp {{ number_format($fabric->price_per_meter) }}/m</span>
                        <span class="text-xs bg-gray-100 px-2 py-1 rounded">Stock: {{ $fabric->stock_meter }}m</span>
                    </div>
                    @endif

                    <div class="mt-4 flex gap-2">
                        <a href="{{ route('fabrics.show', $fabric) }}" class="flex-1 text-center border border-blue-600 text-blue-600 py-1 rounded hover:bg-blue-50">Detail</a>
                        @if (Auth::user()?->role == 'admin')
                        <a href="{{ route('fabrics.restock', $fabric) }}" class="text-center bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 text-sm">Stock</a>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            @if($fabrics->isEmpty())
                <p class="text-center text-gray-500 mt-10">No fabrics found.</p>
            @endif

        </div>
    </div>
</x-app-layout>