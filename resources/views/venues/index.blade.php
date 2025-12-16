<x-app-layout>
    <div class="pt-32 pb-20 bg-stone-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="text-center mb-12">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-[0.3em] mb-2">Our Locations</p>
                <h2 class="font-serif text-4xl font-bold text-gray-900">Exclusive Venues</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($venues as $venue)
                    <div
                        class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition duration-500 border border-stone-100">
                        <div class="h-64 overflow-hidden relative">
                            <img src="{{ $venue->image ?? 'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?q=80&w=800' }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                            <div class="absolute inset-0 bg-black/20 group-hover:bg-black/40 transition"></div>
                        </div>

                        <div class="p-8">
                            <h3 class="font-serif text-2xl font-bold text-gray-900 mb-2">{{ $venue->name }}</h3>
                            <p class="text-gray-500 text-sm mb-6 flex items-start gap-2">
                                <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                {{ $venue->address }}
                            </p>

                            <a href="{{ route('orders.create', ['venue_id' => $venue->id]) }}"
                                class="block w-full text-center bg-black text-white py-4 rounded-xl font-bold uppercase tracking-widest text-xs hover:bg-stone-800 transition">
                                Book This Venue
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
</x-app-layout>
