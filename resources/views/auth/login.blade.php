<x-guest-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0" style="background-color: #303451;">
        <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-transparent overflow-hidden sm:rounded-lg text-white">
            <!-- Logo -->
            <div class="flex flex-col items-center mb-8">
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-white" />
                </a>
                <h1 class="text-3xl font-bold mt-2">Welcome Back</h1>
                <p class="text-center text-sm text-gray-300 mt-4">
                    Log in to continue your sustainable fashion journey.
                </p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div class="mt-6">
                    <label for="email" class="block font-semibold text-sm text-white">{{ __('Email') }}</label>
                    <input id="email" class="block mt-1 w-full rounded-full border-transparent bg-white bg-opacity-20 py-3 px-4 text-white focus:border-indigo-500 focus:ring-indigo-500 placeholder-gray-400" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="you@example.com">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-6">
                    <label for="password" class="block font-semibold text-sm text-white">{{ __('Password') }}</label>
                    <input id="password" class="block mt-1 w-full rounded-full border-transparent bg-white bg-opacity-20 py-3 px-4 text-white focus:border-indigo-500 focus:ring-indigo-500 placeholder-gray-400" type="password" name="password" required autocomplete="current-password" placeholder="••••••••">
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me -->
                <div class="block mt-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 bg-white bg-opacity-20 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                        <span class="ms-2 text-sm text-gray-300">{{ __('Remember me') }}</span>
                    </label>
                </div>

                <div class="flex flex-col items-center justify-end mt-10">
                     <button type="submit" class="w-full text-center py-3 px-4 bg-white bg-opacity-90 border border-transparent rounded-full font-semibold text-md text-indigo-900 uppercase tracking-widest hover:bg-white focus:bg-white active:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        {{ __('Log in') }}
                    </button>

                    <div class="text-center w-full mt-4">
                        <a class="text-sm text-gray-300 hover:text-white rounded-md" href="{{ route('register') }}">
                            {{ __("Don't have an account?") }}
                        </a>
                        
                        @if (Route::has('password.request'))
                            <span class="mx-2 text-gray-400">|</span>
                            <a class="text-sm text-gray-300 hover:text-white rounded-md" href="{{ route('password.request') }}">
                                {{ __('Forgot your password?') }}
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout> 