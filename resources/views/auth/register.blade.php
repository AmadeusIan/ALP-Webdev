<x-guest-layout>
    <div class="text-center mb-10">
        <h2 class="font-serif text-3xl font-bold text-gray-900 uppercase tracking-[0.2em]">Kana Covers</h2>
        <p class="text-[10px] font-bold text-stone-400 uppercase tracking-widest mt-2">New Membership</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="group">
            <x-input-label for="name" :value="__('Full Name')" />
            <x-text-input id="name" class="block mt-2 w-full" type="text" name="name" :value="old('name')" required
                autofocus autocomplete="name" placeholder="John Doe" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-6 group">
            <x-input-label for="email" :value="__('Email Address')" />
            <x-text-input id="email" class="block mt-2 w-full" type="email" name="email" :value="old('email')"
                required autocomplete="username" placeholder="name@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>


        <div class="mt-6 group">
            <x-input-label for="phone" :value="__('Phone Number (WA)')" />

            <x-text-input id="phone"
                class="block mt-2 w-full border border-gray-300 rounded-md shadow-sm focus:border-black focus:ring-black"
                type="text" name="phone" :value="old('phone')" required placeholder="0812xxxxxxx" />

            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <div class="mt-6 group">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-2 w-full" type="password" name="password" required
                autocomplete="new-password" placeholder="Min. 8 characters" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-6 group">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-2 w-full" type="password"
                name="password_confirmation" required autocomplete="new-password" placeholder="Repeat password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-8">
            <a class="text-[10px] font-bold text-stone-400 uppercase tracking-wider hover:text-black border-b border-transparent hover:border-black transition pb-px"
                href="{{ route('login') }}">
                {{ __('Already have an account?') }}
            </a>
        </div>

        <div class="mt-6">
            <button type="submit"
                class="w-full inline-flex justify-center items-center px-4 py-4 bg-black border border-transparent font-bold text-xs text-white uppercase tracking-[0.2em] hover:bg-stone-800 active:bg-stone-900 focus:outline-none transition ease-in-out duration-150 rounded-none shadow-lg">
                {{ __('Create Account') }}
            </button>
        </div>
    </form>
</x-guest-layout>
