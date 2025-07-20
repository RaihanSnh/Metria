<x-guest-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0" style="background-color: #303451;">
        <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-transparent overflow-hidden sm:rounded-lg text-white">
            <!-- Logo -->
            <div class="flex flex-col items-center mb-8">
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-white" />
                </a>
                <h1 class="text-3xl font-bold mt-2">Create Account</h1>
                <p class="text-center text-sm text-gray-300 mt-4">
                    Join Metria and start your sustainable fashion journey.
                </p>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Username -->
                <div>
                    <label for="username" class="block font-semibold text-sm text-white">{{ __('Username') }}</label>
                    <input id="username" class="block mt-1 w-full rounded-full border-transparent bg-white bg-opacity-20 py-3 px-4 text-white focus:border-indigo-500 focus:ring-indigo-500 placeholder-gray-400" type="text" name="username" :value="old('username')" required autofocus autocomplete="username" placeholder="e.g., fashionista88">
                    <x-input-error :messages="$errors->get('username')" class="mt-2" />
                </div>
                
                <!-- Full Name -->
                <div class="mt-4">
                    <label for="full_name" class="block font-semibold text-sm text-white">{{ __('Full Name') }}</label>
                    <input id="full_name" class="block mt-1 w-full rounded-full border-transparent bg-white bg-opacity-20 py-3 px-4 text-white focus:border-indigo-500 focus:ring-indigo-500 placeholder-gray-400" type="text" name="full_name" :value="old('full_name')" required autocomplete="name" placeholder="Your full name">
                    <x-input-error :messages="$errors->get('full_name')" class="mt-2" />
                </div>

                <!-- Email Address -->
                <div class="mt-4">
                    <label for="email" class="block font-semibold text-sm text-white">{{ __('Email') }}</label>
                    <input id="email" class="block mt-1 w-full rounded-full border-transparent bg-white bg-opacity-20 py-3 px-4 text-white focus:border-indigo-500 focus:ring-indigo-500 placeholder-gray-400" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="you@example.com">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <label for="password" class="block font-semibold text-sm text-white">{{ __('Password') }}</label>
                    <input id="password" class="block mt-1 w-full rounded-full border-transparent bg-white bg-opacity-20 py-3 px-4 text-white focus:border-indigo-500 focus:ring-indigo-500 placeholder-gray-400" type="password" name="password" required autocomplete="new-password" placeholder="••••••••">
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div class="mt-4">
                    <label for="password_confirmation" class="block font-semibold text-sm text-white">{{ __('Confirm Password') }}</label>
                    <input id="password_confirmation" class="block mt-1 w-full rounded-full border-transparent bg-white bg-opacity-20 py-3 px-4 text-white focus:border-indigo-500 focus:ring-indigo-500 placeholder-gray-400" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••">
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="flex flex-col items-center justify-end mt-10">
                    <button type="submit" class="w-full text-center py-3 px-4 bg-white bg-opacity-90 border border-transparent rounded-full font-semibold text-md text-indigo-900 uppercase tracking-widest hover:bg-white focus:bg-white active:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        {{ __('Next') }}
                    </button>
                    
                    <a class="text-sm text-gray-300 hover:text-white rounded-md mt-4" href="{{ route('login') }}">
                        {{ __('Already registered?') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout> 