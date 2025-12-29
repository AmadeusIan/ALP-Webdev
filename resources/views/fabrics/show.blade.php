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
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">

                {{-- === SECTION 1: MAIN INFO (SPLIT VIEW) === --}}
                <div class="flex flex-col md:flex-row border-b border-gray-100">
                    
                    {{-- LEFT COLUMN: IMAGE --}}
                    <div class="w-full md:w-2/5 bg-gray-100 flex items-center justify-center p-8 relative overflow-hidden group min-h-[400px]">
                        <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/5 to-purple-500/5 opacity-0 group-hover:opacity-100 transition duration-500">
                        </div>
                        {{-- Placeholder Icon --}}
                        <svg class="w-24 h-24 text-gray-300 drop-shadow-sm transition-transform duration-500 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>

                    {{-- RIGHT COLUMN: SPECS & ACTIONS --}}
                    <div class="w-full md:w-3/5 p-8 md:p-10 flex flex-col">
                        
                        {{-- Header Info --}}
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-100 text-indigo-800 uppercase tracking-wide">
                                        {{ $fabric->category->name }}
                                    </span>
                                </div>
                                <h1 class="text-4xl font-serif font-bold text-gray-900 leading-tight mb-2">{{ $fabric->name }}</h1>
                                <p class="text-sm text-gray-500">Product ID: #{{ str_pad($fabric->id, 5, '0', STR_PAD_LEFT) }}</p>
                            </div>

                            <div class="text-right">
                                <p class="text-3xl font-bold text-indigo-600">Rp {{ number_format($fabric->price_per_meter, 0, ',', '.') }}</p>
                                <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">per meter / day</p>
                            </div>
                        </div>

                        <hr class="border-gray-100 mb-6">

                        {{-- Color Selection --}}
                        <div class="mb-8">
                            <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-3">
                                Selected Color: <span class="text-black ml-1">{{ $fabric->color }}</span>
                            </p>
                            
                            <div class="flex flex-wrap gap-3">
                                @foreach($variants as $variant)
                                    <a href="{{ route('fabrics.show', $variant->id) }}" 
                                    class="group relative w-12 h-12 rounded-full cursor-pointer transition-all duration-200 focus:outline-none
                                            {{ $fabric->id === $variant->id 
                                                ? 'ring-2 ring-offset-2 ring-indigo-600 scale-110 shadow-md' 
                                                : 'ring-1 ring-gray-200 hover:scale-105 hover:ring-indigo-300' }}"
                                    title="{{ $variant->color }}">
                                        
                                        {{-- Bulatan Warna --}}
                                        <span class="absolute inset-0 rounded-full" 
                                            style="background-color: {{ $variant->color }}; border: 1px solid rgba(0,0,0,0.05);">
                                        </span>

                                        {{-- Cross jika stok habis --}}
                                        @if($variant->stock_meter <= 0)
                                            <span class="absolute inset-0 flex items-center justify-center bg-white/40 rounded-full backdrop-blur-[1px]">
                                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </span>
                                        @endif
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        {{-- Quick Specs Grid --}}
                        <div class="grid grid-cols-2 gap-y-6 gap-x-4 mb-8">
                            <div>
                                <p class="text-xs text-gray-400 font-bold uppercase mb-1">Material Composition</p>
                                <p class="font-medium text-gray-900 text-lg">{{ $fabric->material }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 font-bold uppercase mb-1">Current Stock</p>
                                @if ($fabric->stock_meter > 0)
                                    <div class="flex items-center gap-2">
                                        <span class="flex h-2.5 w-2.5 relative">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-500"></span>
                                        </span>
                                        <span class="text-green-700 font-bold">
                                            {{ Auth::user()?->role === 'admin' ? $fabric->stock_meter . ' Meters' : 'Available' }}
                                        </span>
                                    </div>
                                @else
                                    <span class="text-red-500 font-bold flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Out of Stock
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="mt-auto pt-6">
                            @if (Auth::user()?->role === 'admin')
                                <div class="grid grid-cols-2 gap-3">
                                    <a href="{{ route('fabrics.edit', $fabric) }}" class="flex justify-center items-center px-4 py-3 bg-white border border-gray-300 rounded-xl text-sm font-bold text-gray-700 hover:bg-gray-50 transition">
                                        Edit Details
                                    </a>
                                    <a href="{{ route('fabrics.restock', $fabric) }}" class="flex justify-center items-center px-4 py-3 bg-indigo-50 border border-transparent rounded-xl text-sm font-bold text-indigo-700 hover:bg-indigo-100 transition">
                                        Manage Stock
                                    </a>
                                    <form action="{{ route('fabrics.destroy', $fabric) }}" method="POST" onsubmit="return confirm('Delete this fabric?')" class="col-span-2">
                                        @csrf @method('DELETE')
                                        <button type="button" onclick="if(confirm('Are you sure?')) this.closest('form').submit();" class="w-full px-4 py-3 text-red-500 hover:bg-red-50 rounded-xl text-sm font-bold transition">
                                            Delete Fabric
                                        </button>
                                    </form>
                                </div>
                            @else
                                {{-- USER BUTTON --}}
                                @if ($fabric->stock_meter > 0)
                                    <a href="{{ route('orders.create', ['fabric_id' => $fabric->id]) }}"
                                        class="group w-full flex items-center justify-center gap-2 bg-black text-white px-6 py-4 rounded-xl font-bold text-lg hover:bg-gray-800 hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                                        <span>Select {{ $fabric->color }}</span>
                                        <svg class="w-5 h-5 text-gray-400 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                        </svg>
                                    </a>
                                    <p class="text-xs text-gray-400 mt-3 text-center">
                                        Next: You will choose the venue, room, and quantity.
                                    </p>
                                @else
                                    <button disabled class="w-full bg-gray-200 text-gray-400 px-6 py-4 rounded-xl font-bold text-lg cursor-not-allowed border border-gray-200">
                                        Currently Unavailable
                                    </button>
                                @endif
                            @endif
                        </div>

                    </div>
                </div>

                {{-- === SECTION 2: DESCRIPTION (FULL WIDTH) === --}}
                <div class="bg-gray-50/50 p-8 md:p-10 border-t border-gray-100">
                    <div class="max-w-3xl">
                        <h3 class="font-serif text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Product Description
                        </h3>
                        
                        <div class="prose prose-indigo text-gray-600 leading-relaxed">
                            @if($fabric->description)
                                <p>{{ $fabric->description }}</p>
                            @else
                                <div class="flex flex-col items-start gap-2 text-gray-400 italic bg-white p-6 rounded-lg border border-gray-200 border-dashed">
                                    <svg class="w-8 h-8 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    <span>No detailed description provided for this fabric yet.</span>
                                </div>
                            @endif
                        </div>

                        {{-- Additional Details / Supplier Info (Admin Only) --}}
                        @if(Auth::user()?->role === 'admin' && $fabric->supplier)
                            <div class="mt-8 pt-6 border-t border-gray-200">
                                <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-2">Internal Info</h4>
                                <div class="inline-flex items-center gap-2 px-3 py-1 rounded bg-yellow-50 text-yellow-800 text-sm font-medium border border-yellow-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    Supplier: {{ $fabric->supplier->name }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>