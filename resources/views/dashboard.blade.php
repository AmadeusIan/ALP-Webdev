<x-app-layout>
    @push('styles')
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=cinzel:400,700|lato:400,700" rel="stylesheet" />
    @endpush

    <x-slot name="header">
        <div class="flex justify-between items-end border-b border-gray-200 pb-4">
            <div>
                <p class="text-xs font-bold text-black">
                    Welcome back, {{ Auth::user()->name }}
                </p>
            </div>
            <div class="hidden sm:block w-24 h-1 bg-black mb-1"></div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">

                <div
                    class="group relative h-64 bg-black overflow-hidden border border-gray-200 md:col-span-2 cursor-pointer transition-all duration-500 hover:shadow-xl">
                    <img src="https://images.unsplash.com/photo-1558591710-4b4a1ae0f04d?q=80&w=1000&auto=format&fit=crop"
                        alt="Fabrics"
                        class="absolute inset-0 w-full h-full object-cover opacity-60 group-hover:scale-105 transition-transform duration-700 ease-out">
                    <div class="absolute inset-0 bg-gradient-to-r from-black/80 to-transparent"></div>

                    <div class="absolute inset-0 p-8 flex flex-col justify-center items-start">
                        <span class="text-xs font-bold text-white/70 uppercase tracking-[0.3em] mb-2">New
                            Arrivals</span>
                        <h3
                            class="font-serif text-3xl md:text-4xl text-white mb-6 uppercase tracking-widest leading-tight">
                            Premium <br> Textures
                        </h3>
                        <a href="{{ route('fabrics.index') }}"
                            class="inline-block border border-white px-8 py-3 text-white text-xs font-bold uppercase tracking-widest hover:bg-white hover:text-black transition duration-300">
                            Browse Catalog
                        </a>
                    </div>
                </div>

                <a href="{{ route('orders.index') }}"
                    class="group block h-64 border border-gray-200 p-8 bg-white hover:border-black transition duration-300 flex flex-col justify-between relative overflow-hidden">
                    <div>
                        <div
                            class="w-12 h-12 bg-gray-50 flex items-center justify-center mb-6 group-hover:bg-black transition duration-300">
                            <svg class="w-6 h-6 text-gray-900 group-hover:text-white transition duration-300"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                        </div>
                        <h3 class="font-serif text-xl text-gray-900 uppercase tracking-widest mb-1">My Orders</h3>
                        <p class="text-xs text-gray-500 font-sans">Check status & history</p>
                    </div>

                    <div class="flex items-center justify-between border-t border-gray-100 pt-4">
                        <span class="text-xs font-bold text-gray-900 uppercase tracking-wider">View All</span>
                        <span class="text-xl transform group-hover:translate-x-2 transition duration-300">&rarr;</span>
                    </div>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                <div class="border border-gray-200 p-8 md:p-10 bg-gray-50/50">
                    <h4 class="font-serif text-lg text-gray-900 uppercase tracking-[0.2em] mb-6">Account Status</h4>

                    <div class="space-y-6">
                        <div class="flex justify-between items-center border-b border-gray-200 pb-4">
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Member Since</span>
                            <span class="font-serif text-gray-900">{{ Auth::user()->created_at->format('M Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center border-b border-gray-200 pb-4">
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Membership</span>
                            <span class="font-serif text-gray-900">Standard</span>
                        </div>
                        <div class="pt-2">
                            <a href="{{ route('profile.edit') }}"
                                class="text-xs font-bold text-black uppercase tracking-widest border-b border-black hover:text-gray-600 hover:border-gray-600 transition">
                                Edit Profile settings
                            </a>
                        </div>
                    </div>
                </div>

                <div
                    class="border border-gray-200 p-8 md:p-10 flex flex-col justify-center items-center text-center bg-white">
                    <div class="w-16 h-16 rounded-full bg-gray-50 flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                            </path>
                        </svg>
                    </div>
                    <h4 class="font-serif text-lg text-gray-900 uppercase tracking-[0.2em] mb-3">Need Assistance?</h4>
                    <p class="text-sm text-gray-500 font-sans mb-8 max-w-xs mx-auto leading-relaxed">
                        Have questions about fabric availability or custom rental periods?
                    </p>
                    <a href="https://wa.me/6282137943030?text=Halo%20Kana%20Covers,%20saya%20butuh%20bantuan%20terkait%20Order%20saya."
                        class="inline-block bg-black text-white px-8 py-3 text-xs font-bold uppercase tracking-widest hover:bg-gray-800 transition">
                        Contact Support
                    </a>
                </div>

            </div>

        </div>
    </div>

    <style>
        @import url('https://fonts.bunny.net/css?family=cinzel:400,700|lato:400,700');

        .font-serif {
            font-family: 'Cinzel', serif;
        }

        .font-sans {
            font-family: 'Lato', sans-serif;
        }
    </style>
</x-app-layout>
