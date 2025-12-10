<section>
    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        <div class="group">
            <x-input-label for="update_password_current_password" :value="__('Current Password')" 
                class="uppercase text-[10px] font-bold text-gray-900 tracking-[0.2em] transition-colors group-hover:text-black" />
            
            <x-text-input id="update_password_current_password" name="current_password" type="password" 
                class="mt-2 block w-full rounded-none border-stone-200 bg-stone-50/30 text-stone-700 placeholder-stone-300 focus:border-stone-500 focus:ring-0 transition duration-300" 
                autocomplete="current-password" 
                placeholder="••••••••" />
            
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div class="group">
            <x-input-label for="update_password_password" :value="__('New Password')" 
                class="uppercase text-[10px] font-bold text-stone-400 tracking-[0.2em] transition-colors group-hover:text-stone-600" />
            
            <x-text-input id="update_password_password" name="password" type="password" 
                class="mt-2 block w-full rounded-none border-stone-200 bg-stone-50/30 text-stone-700 placeholder-stone-300 focus:border-stone-500 focus:ring-0 transition duration-300" 
                autocomplete="new-password" 
                placeholder="New secure password" />
            
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div class="group">
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" 
                class="uppercase text-[10px] font-bold text-stone-400 tracking-[0.2em] transition-colors group-hover:text-stone-600" />
            
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" 
                class="mt-2 block w-full rounded-none border-stone-200 bg-stone-50/30 text-stone-700 placeholder-stone-300 focus:border-stone-500 focus:ring-0 transition duration-300" 
                autocomplete="new-password" 
                placeholder="Repeat password" />
            
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" 
                class="inline-flex items-center px-8 py-3 bg-white border border-stone-300 font-bold text-[10px] text-stone-500 uppercase tracking-[0.2em] hover:border-stone-800 hover:text-stone-800 active:bg-stone-50 focus:outline-none transition ease-in-out duration-300 rounded-none shadow-sm hover:shadow-md">
                {{ __('Update Password') }}
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-xs text-stone-400 italic font-serif"
                >{{ __('Password saved.') }}</p>
            @endif
        </div>
    </form>
</section>