<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kana Covers - Premium Fabric Rental</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=cinzel:400,700|lato:400,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased font-sans text-gray-800 bg-white selection:bg-black selection:text-white">

    <nav class="fixed w-full z-50 bg-white/95 backdrop-blur-md border-b border-gray-100 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex-shrink-0 flex items-center gap-2">
                    <span class="font-serif text-2xl font-bold tracking-[0.2em] text-gray-900 uppercase">
                        Kana Covers
                    </span>
                </div>

                <div class="hidden md:flex space-x-10 items-center">
                    <a href="#about"
                        class="text-xs font-bold text-gray-500 hover:text-black transition uppercase tracking-widest">Our
                        Story</a>
                    <a href="#portfolio"
                        class="text-xs font-bold text-gray-500 hover:text-black transition uppercase tracking-widest">Collections</a>
                    <a href="#team"
                        class="text-xs font-bold text-gray-500 hover:text-black transition uppercase tracking-widest">Reviews</a>
                </div>

                <div class="hidden md:flex items-center space-x-6">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ route('cart.index') }}"
                                class="group flex items-center gap-1 text-xs font-bold text-gray-900 hover:text-gray-600 uppercase tracking-wider">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                                <span class="group-hover:underline decoration-1 underline-offset-4">Cart</span>
                            </a>

                            <div class="h-4 w-px bg-gray-300"></div>

                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" @click.outside="open = false"
                                    class="flex items-center gap-1 text-xs font-bold text-gray-900 hover:text-gray-600 uppercase tracking-wider focus:outline-none">
                                    <span>{{ Auth::user()->name }}</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>

                                <div x-show="open" x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="transform opacity-0 scale-95"
                                    x-transition:enter-end="transform opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="transform opacity-100 scale-100"
                                    x-transition:leave-end="transform opacity-0 scale-95"
                                    class="absolute right-0 mt-4 w-48 bg-white border border-gray-100 shadow-xl py-2 z-50"
                                    style="display: none;">

                                    <a href="{{ url('/dashboard') }}"
                                        class="block px-4 py-2 text-xs font-bold text-gray-700 uppercase tracking-wider hover:bg-gray-50 hover:text-black transition">
                                        Dashboard
                                    </a>
                                    <a href="{{ route('profile.edit') }}"
                                        class="block px-4 py-2 text-xs font-bold text-gray-700 uppercase tracking-wider hover:bg-gray-50 hover:text-black transition">
                                        Profile Settings
                                    </a>

                                    <div class="border-t border-gray-100 my-1"></div>

                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault(); this.closest('form').submit();"
                                            class="block px-4 py-2 text-xs font-bold text-gray-700 uppercase tracking-wider hover:bg-gray-50 hover:text-black transition">
                                            Log Out
                                        </a>
                                    </form>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}"
                                class="text-xs font-bold text-gray-900 hover:text-gray-600 uppercase tracking-wider">Log
                                in</a>
                            <a href="{{ route('register') }}"
                                class="px-6 py-2.5 bg-black text-white text-xs font-bold uppercase tracking-widest hover:bg-gray-800 transition">
                                Join Us
                            </a>
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <header class="relative h-screen flex items-center justify-center bg-black overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1520004434532-668416a08753?q=80&w=2070&auto=format&fit=crop"
                alt="Luxury Fabric Background" class="w-full h-full object-cover opacity-60">
            <div class="absolute inset-0 bg-gradient-to-t from-black via-black/40 to-transparent"></div>
        </div>

        <div class="relative z-10 text-center px-4 max-w-5xl mx-auto">
            <p class="text-gray-300 text-sm md:text-base font-serif italic mb-6 tracking-widest animate-fade-in-up">
                Premium Fabric Rental Service
            </p>
            <h1
                class="text-5xl md:text-7xl lg:text-8xl font-serif font-bold text-white mb-8 tracking-tighter uppercase leading-none animate-fade-in-up delay-100">
                Kana Covers
            </h1>
            <p
                class="text-gray-400 mb-12 text-lg max-w-2xl mx-auto font-light leading-relaxed animate-fade-in-up delay-200">
                Elevate your event with our curated collection of luxury linens and drapes.
                <br class="hidden md:block">Designed for elegance, available for rent.
            </p>
            <a href="#portfolio"
                class="inline-block px-10 py-4 border border-white text-white text-xs font-bold uppercase tracking-[0.25em] hover:bg-white hover:text-black transition duration-500 animate-fade-in-up delay-300">
                Explore Collection
            </a>
        </div>
    </header>

    <section class="py-32 bg-white" id="portfolio">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20">
                <h2 class="text-4xl font-serif font-bold text-gray-900 mb-4 uppercase tracking-wide">Our Curated
                    Collection</h2>
                <div class="w-12 h-0.5 bg-black mx-auto mt-6"></div>
                <p class="text-gray-500 font-serif italic mt-4">Timeless textures for memorable moments.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-12">
                @forelse($fabrics as $fabric)
                    <div class="group relative cursor-pointer">
                        <div class="aspect-w-3 aspect-h-4 w-full overflow-hidden bg-gray-100">
                            <img src="{{ $fabric->image ? asset($fabric->image) : 'https://images.unsplash.com/photo-1620799140408-ed5341cd2431?q=80&w=1000&auto=format&fit=crop' }}"
                                alt="{{ $fabric->name }}"
                                class="h-[500px] w-full object-cover object-center group-hover:scale-105 transition-transform duration-700 ease-out">

                            <div
                                class="absolute inset-0 bg-black/20 group-hover:bg-black/40 transition-colors duration-300 flex items-center justify-center opacity-0 group-hover:opacity-100">
                                <a href="{{ route('fabrics.show', $fabric) }}"
                                    class="bg-white text-black px-8 py-3 text-xs font-bold uppercase tracking-widest hover:bg-gray-100 transition transform translate-y-4 group-hover:translate-y-0 duration-300">
                                    Rent Now
                                </a>
                            </div>
                        </div>

                        <div class="mt-6 text-center">
                            <h3 class="text-lg font-bold text-gray-900 uppercase tracking-widest">{{ $fabric->name }}
                            </h3>
                            <p class="mt-1 text-sm text-gray-500 font-serif italic">
                                {{ $fabric->category->name ?? 'Premium Series' }}</p>
                            <p class="mt-2 text-sm font-bold text-gray-900">Rp
                                {{ number_format($fabric->price_per_meter) }} / m</p>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-24">
                        <p class="text-gray-400 font-serif italic text-xl">Collection coming soon.</p>
                    </div>
                @endforelse
            </div>

            <div class="text-center mt-24">
                <a href="{{ route('fabrics.index') }}"
                    class="inline-block border-b-2 border-black pb-1 text-sm font-bold uppercase tracking-widest hover:text-gray-600 hover:border-gray-600 transition">
                    View All Products
                </a>
            </div>
        </div>
    </section>

    <section class="py-32 bg-gray-50" id="about">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div class="order-2 lg:order-1">
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-[0.2em] mb-4 block">Since
                        2009</span>
                    <h2 class="text-4xl lg:text-5xl font-serif font-bold text-gray-900 mb-8 leading-tight">
                        Weaving Faith <br> Into Fashion.
                    </h2>
                    <div class="space-y-6 text-gray-600 text-lg font-light leading-relaxed">
                        <p>
                            Kana Covers began as a humble vision to serve the Lord through the art of textiles. What
                            started as a small team has grown into a trusted partner for event planners across
                            Indonesia.
                        </p>
                        <p>
                            We believe that every fabric tells a story. From intimate gatherings to grand celebrations,
                            our collection is curated to bring warmth, elegance, and beauty to your special moments.
                        </p>
                    </div>
                    <div class="mt-10">
                        <img src="{{ asset('assets/img/signature.png') }}" alt="" class="h-12 opacity-50"
                            onerror="this.style.display='none'">
                    </div>
                </div>

                <div class="order-1 lg:order-2 grid grid-cols-2 gap-4">
                    <img src="https://images.unsplash.com/photo-1558597380-4df200877992?q=80&w=800&auto=format&fit=crop"
                        class="w-full h-80 object-cover mt-12">
                    <img src="https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?q=80&w=800&auto=format&fit=crop"
                        class="w-full h-80 object-cover">
                </div>
            </div>
        </div>
    </section>

    <section class="py-32 bg-white" id="team">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20">
                <h2 class="text-4xl font-serif font-bold text-gray-900 mb-4 uppercase tracking-wide">Client Stories</h2>
                <div class="w-12 h-0.5 bg-black mx-auto mt-6"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                @forelse ($reviews as $index => $review)
                    @if ($index < 3)
                        <div class="text-center px-4">
                            <div class="mb-6 flex justify-center text-yellow-500">
                                @for ($i = 0; $i < 5; $i++)
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                @endfor
                            </div>
                            <blockquote class="text-xl font-serif italic text-gray-800 mb-6 leading-relaxed">
                                "{{ $review->review }}"
                            </blockquote>
                            <div class="font-bold text-xs uppercase tracking-widest text-gray-500">
                                — {{ $review->user->name ?? 'Happy Client' }}
                            </div>
                        </div>
                    @endif
                @empty
                    <div class="col-span-full text-center">
                        <p class="text-gray-400 font-serif italic">No reviews yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="py-32 bg-gray-50" id="location">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-serif font-bold text-gray-900 mb-4 uppercase tracking-wide">
                    Visit Our Showroom
                </h2>
                <div class="w-12 h-0.5 bg-black mx-auto mt-6"></div>
                <p class="text-gray-500 font-serif italic mt-4">
                    Experience the texture and quality in person.
                </p>
            </div>

            <div class="relative w-full h-[500px] shadow-2xl overflow-hidden group">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3957.691977063668!2d112.6302634758509!3d-7.275841771497255!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7fde455555555%3A0xd7e2611ae591f046!2sUniversitas%20Ciputra%20Surabaya!5e0!3m2!1sen!2sid!4v1709623835499!5m2!1sen!2sid" 
                    width="100%" 
                    height="100%" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade"
                    class="w-full h-full filter grayscale contrast-125 group-hover:filter-none transition-all duration-700 ease-in-out">
                </iframe>

                <div class="absolute bottom-0 left-0 bg-white/95 backdrop-blur-sm p-8 max-w-sm m-6 shadow-lg border-l-4 border-black pointer-events-none md:block hidden">
                    <h3 class="font-bold text-gray-900 uppercase tracking-widest text-sm mb-2">Kana Covers Studio</h3>
                    <p class="text-gray-600 font-serif italic text-sm leading-relaxed">
                        CitraLand Utama Road<br>
                        Surabaya, East Java<br>
                        Indonesia
                    </p>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-black text-white py-16 border-t border-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 items-center text-center md:text-left">
                <div>
                    <span class="font-serif text-2xl font-bold tracking-[0.2em] uppercase block mb-4">Kana
                        Covers</span>
                    <p class="text-gray-500 text-sm leading-relaxed max-w-xs mx-auto md:mx-0">
                        Providing premium fabric rentals for weddings and events across Indonesia since 2009.
                    </p>
                </div>

                <div
                    class="flex flex-col space-y-4 md:space-y-0 md:flex-row md:justify-center md:space-x-8 text-sm font-bold tracking-widest uppercase text-gray-400">
                    <a href="#" class="hover:text-white transition">Collection</a>
                    <a href="#" class="hover:text-white transition">About</a>
                    <a href="#" class="hover:text-white transition">Contact</a>
                </div>

                <div class="text-center md:text-right">
                    <p class="text-gray-600 text-xs mt-4">© {{ date('Y') }} Kana Covers. All Rights Reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <style>
        .animate-fade-in-up {
            animation: fadeInUp 1s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
            transform: translateY(40px);
        }

        .delay-100 {
            animation-delay: 0.1s;
        }

        .delay-200 {
            animation-delay: 0.2s;
        }

        .delay-300 {
            animation-delay: 0.4s;
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</body>

</html>
