<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Fabric Collection') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div
                class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-8 flex flex-col md:flex-row justify-between items-center gap-4">

                <form method="GET" action="{{ route('fabrics.index') }}" class="flex w-full md:w-auto gap-2">
                    <div class="relative w-full md:w-64">
                        <input type="text" name="search" placeholder="Search fabrics..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition"
                            value="{{ request('search') }}">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>

                    <select name="category_id"
                        class="border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 py-2 pl-3 pr-10 cursor-pointer">
                        <option value="">All Categories</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}"
                                {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>

                    <button type="submit"
                        class="bg-gray-900 text-white px-5 py-2 rounded-lg hover:bg-gray-800 transition font-medium">
                        Filter
                    </button>
                </form>

                @if (Auth::user()?->role === 'admin')
                    <a href="{{ route('fabrics.create') }}"
                        class="flex items-center gap-2 bg-indigo-600 text-white px-5 py-2 rounded-lg hover:bg-indigo-700 transition shadow-md shadow-indigo-200 font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                            </path>
                        </svg>
                        Add Fabric
                    </a>
                @endif
            </div>

            @if (session('success'))
                <div
                    class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 shadow-sm rounded-r flex items-center gap-3">
                    <svg class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($fabrics as $fabric)
                    <div
                        class="group bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-all duration-300 flex flex-col h-full">

                        <div class="relative h-48 bg-gray-100 flex items-center justify-center overflow-hidden">
                            <svg class="w-16 h-16 text-gray-300 group-hover:scale-110 transition duration-500"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>

                            <span
                                class="absolute top-3 left-3 bg-white/90 backdrop-blur text-gray-600 text-xs font-bold px-3 py-1 rounded-full shadow-sm">
                                {{ $fabric->category->name ?? 'General' }}
                            </span>
                        </div>

                        <div class="p-5 flex-1 flex flex-col">
                            <h3 class="font-bold text-lg text-gray-900 mb-1 group-hover:text-indigo-600 transition">
                                {{ $fabric->name }}</h3>
                            <p class="text-xs text-gray-500 mb-4">{{ $fabric->material ?? '-' }} •
                                {{ $fabric->color ?? '-' }}</p>

                            <div class="mt-auto pt-4 border-t border-gray-50 flex justify-between items-center">
                                <div>
                                    <p class="text-xs text-gray-400 font-bold uppercase">Price</p>
                                    <p class="text-indigo-600 font-bold text-lg">Rp
                                        {{ number_format($fabric->price_per_meter, 0, ',', '.') }}</p>
                                </div>

                                @if (Auth::user()?->role === 'admin')
                                    <div class="text-right">
                                        <p class="text-xs text-gray-400 font-bold uppercase">Stock</p>
                                        <p class="text-gray-900 font-bold">{{ $fabric->stock_meter }}m</p>
                                    </div>
                                @else
                                    <div>
                                        @if ($fabric->stock_meter > 0)
                                            <span
                                                class="inline-flex items-center gap-1 bg-green-50 text-green-700 px-2 py-1 rounded text-xs font-bold border border-green-100">
                                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Available
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-1 bg-red-50 text-red-700 px-2 py-1 rounded text-xs font-bold border border-red-100">
                                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Out of Stock
                                            </span>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <div class="mt-4 flex gap-2">
                                <a href="{{ route('fabrics.show', $fabric) }}"
                                    class="flex-1 text-center bg-gray-50 text-gray-700 border border-gray-200 py-2 rounded-lg hover:bg-gray-100 hover:border-gray-300 text-sm font-semibold transition">
                                    View Details
                                </a>

                                @if (Auth::user()?->role === 'admin')
                                    <a href="{{ route('fabrics.restock', $fabric) }}"
                                        class="px-3 bg-indigo-50 text-indigo-600 border border-indigo-100 rounded-lg hover:bg-indigo-100 hover:border-indigo-200 flex items-center justify-center transition"
                                        title="Manage Stock">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div
                        class="col-span-full flex flex-col items-center justify-center py-16 bg-white rounded-xl shadow-sm border border-dashed border-gray-300">
                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                </path>
                            </svg>
                        </div>
                        <p class="text-gray-500 font-medium">No fabrics found in this category.</p>
                        <a href="{{ route('fabrics.index') }}"
                            class="mt-2 text-indigo-600 hover:underline text-sm font-bold">Clear Filters</a>
                    </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $fabrics->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
