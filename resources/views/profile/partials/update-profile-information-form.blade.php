<section>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        <div class="group">
            <x-input-label for="name" :value="__('Name')" 
                class="uppercase text-[10px] font-bold text-stone-400 tracking-[0.2em] transition-colors group-hover:text-stone-600" />
            
            <x-text-input id="name" name="name" type="text" 
                class="mt-2 block w-full rounded-none border-stone-200 bg-stone-50/30 text-stone-700 placeholder-stone-300 focus:border-stone-500 focus:ring-0 transition duration-300" 
                :value="old('name', $user->name)" 
                required autofocus autocomplete="name" 
                placeholder="Your full name" />
            
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div class="group">
            <x-input-label for="email" :value="__('Email')" 
                class="uppercase text-[10px] font-bold text-stone-400 tracking-[0.2em] transition-colors group-hover:text-stone-600" />
            
            <x-text-input id="email" name="email" type="email" 
                class="mt-2 block w-full rounded-none border-stone-200 bg-stone-50/30 text-stone-700 placeholder-stone-300 focus:border-stone-500 focus:ring-0 transition duration-300" 
                :value="old('email', $user->email)" 
                required autocomplete="username" 
                placeholder="email@example.com" />
            
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2">
                    <p class="text-xs text-stone-500">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-stone-600 hover:text-stone-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-stone-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-xs text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" 
                class="inline-flex items-center px-8 py-3 bg-white border border-stone-300 font-bold text-[10px] text-stone-500 uppercase tracking-[0.2em] hover:border-stone-800 hover:text-stone-800 active:bg-stone-50 focus:outline-none transition ease-in-out duration-300 rounded-none shadow-sm hover:shadow-md">
                {{ __('Save Changes') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-xs text-stone-400 italic font-serif"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>