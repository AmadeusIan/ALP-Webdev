<x-app-layout>
    @push('styles')
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=cinzel:400,700|lato:400,700" rel="stylesheet" />
    @endpush

    <div class="min-h-screen bg-stone-50 pt-32 pb-20">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
            
            <div class="text-center mb-12">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-[0.3em] mb-2">
                    Account Management
                </p>
                <h2 class="font-serif text-3xl md:text-4xl font-bold text-gray-900 uppercase tracking-widest leading-none">
                    Profile Settings
                </h2>
                <div class="w-12 h-0.5 bg-black mx-auto mt-6"></div>
            </div>

            <div class="bg-white p-8 md:p-12 border border-gray-100 shadow-[0_10px_40px_rgba(0,0,0,0.03)] relative overflow-hidden group">
                <div class="absolute top-0 left-0 w-1 h-full bg-black transform -translate-x-1 group-hover:translate-x-0 transition duration-300"></div>
                
                <div class="max-w-xl">
                    <header class="mb-6">
                        <h2 class="text-lg font-serif font-bold text-gray-900 uppercase tracking-wide">
                            {{ __('Profile Information') }}
                        </h2>
                        <p class="mt-1 text-sm text-gray-500 font-sans">
                            {{ __("Update your account's profile information and email address.") }}
                        </p>
                    </header>
                    
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="bg-white p-8 md:p-12 border border-gray-100 shadow-[0_10px_40px_rgba(0,0,0,0.03)] relative overflow-hidden group">
                <div class="absolute top-0 left-0 w-1 h-full bg-gray-300 transform -translate-x-1 group-hover:translate-x-0 transition duration-300"></div>

                <div class="max-w-xl">
                    <header class="mb-6">
                        <h2 class="text-lg font-serif font-bold text-gray-40 uppercase tracking-wide">
                            {{ __('Update Password') }}
                        </h2>
                        <p class="mt-1 text-sm text-gray-500 font-sans">
                            {{ __('Ensure your account is using a long, random password to stay secure.') }}
                        </p>
                    </header>

                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="bg-white p-8 md:p-12 border border-red-100 shadow-[0_10px_40px_rgba(0,0,0,0.03)] relative overflow-hidden group">
                <div class="absolute top-0 left-0 w-1 h-full bg-red-500 transform -translate-x-1 group-hover:translate-x-0 transition duration-300"></div>

                <div class="max-w-xl">
                    <header class="mb-6">
                        <h2 class="text-lg font-serif font-bold text-red-700 uppercase tracking-wide">
                            {{ __('Delete Account') }}
                        </h2>
                        <p class="mt-1 text-sm text-gray-500 font-sans">
                            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted.') }}
                        </p>
                    </header>

                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>

    <style>
        .font-serif { font-family: 'Cinzel', serif; }
        .font-sans { font-family: 'Lato', sans-serif; }

        input[type="text"], input[type="email"], input[type="password"] {
            border-radius: 0px !important; 
            border-color: #e5e7eb;
        }
        input[type="text"]:focus, input[type="email"]:focus, input[type="password"]:focus {
            border-color: black !important;
            box-shadow: 0 0 0 1px black !important;
        }
        
        
        button[type="submit"] {
            border-radius: 0px !important;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-weight: bold;
            font-size: 0.75rem; 
        }
    </style>
</x-app-layout>