<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Fabric Details') }}
            </h2>
            <a href="{{ route('fabrics.index') }}"
                class="text-sm text-gray-500 hover:text-indigo-600 flex items-center gap-1 transition">
                &larr; Back to Catalog
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-2xl border border-gray-100">

                <div class="flex flex-col md:flex-row">
                    <div
                        class="w-full md:w-2/5 bg-gray-100 flex items-center justify-center p-8 relative overflow-hidden group">
                        <div
                            class="absolute inset-0 bg-gradient-to-br from-indigo-500/5 to-purple-500/5 opacity-0 group-hover:opacity-100 transition duration-500">
                        </div>
                        <svg class="w-24 h-24 text-gray-300 drop-shadow-sm" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>

                    <div class="w-full md:w-3/5 p-8 md:p-12">
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <span
                                    class="inline-block bg-indigo-100 text-indigo-700 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider mb-2">
                                    {{ $fabric->category->name }}
                                </span>
                                <h1 class="text-4xl font-serif font-bold text-gray-900">{{ $fabric->name }}</h1>
                            </div>

                            <div class="text-right">
                                <p class="text-3xl font-bold text-indigo-600">Rp
                                    {{ number_format($fabric->price_per_meter, 0, ',', '.') }}</p>
                                <p class="text-xs text-gray-400 font-medium">per meter / day</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-6 mb-8 border-y border-gray-100 py-6">
                            <div>
                                <p class="text-xs text-gray-400 font-bold uppercase mb-1">Color</p>
                                <p class="font-medium text-gray-800">{{ $fabric->color }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 font-bold uppercase mb-1">Material</p>
                                <p class="font-medium text-gray-800">{{ $fabric->material }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 font-bold uppercase mb-1">Supplier</p>
                                <p class="font-medium text-gray-800">{{ $fabric->supplier->name ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 font-bold uppercase mb-1">Availability</p>
                                @if ($fabric->stock_meter > 0)
                                    <span class="text-green-600 font-bold flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        {{ Auth::user()?->role === 'admin' ? $fabric->stock_meter . ' m' : 'Available' }}
                                    </span>
                                @else
                                    <span class="text-red-500 font-bold flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        Out of Stock
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="mb-10">
                            <h3 class="text-sm font-bold text-gray-900 uppercase mb-2">Description</h3>
                            <p class="text-gray-600 leading-relaxed">
                                {{ $fabric->description ?? 'No description provided.' }}</p>
                        </div>

                        <div class="mt-auto">
                            @if (Auth::user()?->role === 'admin')
                                <div class="flex flex-wrap gap-3 p-4 bg-gray-50 rounded-xl border border-gray-100">
                                    <a href="{{ route('fabrics.edit', $fabric) }}"
                                        class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 hover:border-gray-400 font-medium shadow-sm transition">
                                        Edit Info
                                    </a>
                                    <a href="{{ route('fabrics.restock', $fabric) }}"
                                        class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 font-medium shadow-md shadow-indigo-200 transition">
                                        Manage Stock
                                    </a>

                                    <form action="{{ route('fabrics.destroy', $fabric) }}" method="POST"
                                        onsubmit="return confirm('Delete this fabric?')" class="ml-auto">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="text-red-500 hover:text-red-700 hover:bg-red-50 px-4 py-2 rounded-lg font-medium transition">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            @else
                                <form action="{{ route('cart.add', $fabric->id) }}" method="POST"
                                    class="bg-gray-50 p-6 rounded-xl border border-indigo-100">
                                    @csrf
                                    <label
                                        class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wide">Quantity
                                        Needed (Meters)</label>

                                    <div class="flex gap-3">
                                        <input type="number" name="quantity" value="1" min="1"
                                            max="{{ $fabric->stock_meter }}"
                                            class="w-24 text-center border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 font-bold text-lg">

                                        <button type="submit"
                                            class="flex-1 bg-black text-white px-6 py-3 rounded-lg font-bold hover:bg-gray-800 shadow-lg transition transform hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed"
                                            {{ $fabric->stock_meter <= 0 ? 'disabled' : '' }}>
                                            {{ $fabric->stock_meter > 0 ? '+ Add to Cart' : 'Unavailable' }}
                                        </button>
                                    </div>
                                    <p class="text-xs text-gray-400 mt-2 text-center">Secure your booking today.</p>
                                </form>
                            @endif
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
