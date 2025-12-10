<x-guest-layout>
    <div class="text-center mb-10">
        <h2 class="font-serif text-3xl font-bold text-gray-900 uppercase tracking-[0.2em]">Kana Covers</h2>
        <p class="text-[10px] font-bold text-stone-400 uppercase tracking-widest mt-2">Member Access</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="group">
            <x-input-label for="email" :value="__('Email Address')" />
            
            <x-text-input id="email" class="block mt-2 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="name@example.com" />
            
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-6 group">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-2 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" 
                            placeholder="••••••••" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-6">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <input id="remember_me" type="checkbox" class="rounded-none border-stone-300 text-black shadow-sm focus:ring-black transition duration-150 ease-in-out" name="remember">
                <span class="ms-2 text-[10px] font-bold text-stone-500 uppercase tracking-wider group-hover:text-black transition">{{ __('Remember me') }}</span>
            </label>

            <a class="text-[10px] font-bold text-stone-400 uppercase tracking-wider hover:text-black border-b border-transparent hover:border-black transition pb-px" href="{{ route('register') }}">
                {{ __('Create Account') }}
            </a>
        </div>

        <div class="mt-8">
            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-4 bg-black border border-transparent font-bold text-xs text-white uppercase tracking-[0.2em] hover:bg-stone-800 active:bg-stone-900 focus:outline-none transition ease-in-out duration-150 rounded-none shadow-lg">
                {{ __('Log in') }}
            </button>
        </div>
    </form>
</x-guest-layout>