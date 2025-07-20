<x-guest-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0" style="background-color: #303451;">
        <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-transparent overflow-hidden sm:rounded-lg text-white">
            <!-- Logo -->
            <div class="flex flex-col items-center mb-8">
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-white" />
                </a>
                <h1 class="text-3xl font-bold mt-2">Metria</h1>
                <p class="text-center text-sm text-gray-300 mt-4">
                    To ensure you get the best fit, we ask for your height and weight. This helps us recommend the right clothing size for your body. You can always edit this information later on your profile page.
                </p>
            </div>

            <form method="POST" action="{{ route('register.measurements.store') }}">
                @csrf

                <!-- Body Height -->
                <div class="mt-6">
                    <label for="height_cm" class="block font-semibold text-sm text-white">{{ __('Body Height') }}</label>
                    <input id="height_cm" class="block mt-1 w-full rounded-full border-gray-300 bg-white bg-opacity-20 py-3 px-4 text-white focus:border-indigo-500 focus:ring-indigo-500" type="number" name="height_cm" value="{{ old('height_cm') }}" required placeholder="in cm">
                    <x-input-error :messages="$errors->get('height_cm')" class="mt-2" />
                </div>

                <!-- Body Weight -->
                <div class="mt-6">
                    <label for="weight_kg" class="block font-semibold text-sm text-white">{{ __('Body Weight') }}</label>
                    <input id="weight_kg" class="block mt-1 w-full rounded-full border-transparent bg-white bg-opacity-20 py-3 px-4 text-white focus:border-indigo-500 focus:ring-indigo-500 placeholder-gray-400" type="number" step="0.1" name="weight_kg" value="{{ old('weight_kg') }}" required placeholder="in kg">
                    <x-input-error :messages="$errors->get('weight_kg')" class="mt-2" />
                </div>

                <!-- Bust, Waist, Hip -->
                <div class="mt-6">
                    <label class="block font-semibold text-sm text-white mb-1">{{ __('Additional Measurements (Optional)') }}</label>
                    <div class="grid grid-cols-3 gap-2">
                        <div>
                            <input id="bust_circumference_cm" class="block mt-1 w-full rounded-full border-transparent bg-white bg-opacity-20 py-3 px-4 text-white focus:border-indigo-500 focus:ring-indigo-500 placeholder-gray-400" type="number" name="bust_circumference_cm" value="{{ old('bust_circumference_cm') }}" placeholder="Bust">
                             <x-input-error :messages="$errors->get('bust_circumference_cm')" class="mt-2" />
                        </div>
                        <div>
                             <input id="waist_circumference_cm" class="block mt-1 w-full rounded-full border-transparent bg-white bg-opacity-20 py-3 px-4 text-white focus:border-indigo-500 focus:ring-indigo-500 placeholder-gray-400" type="number" name="waist_circumference_cm" value="{{ old('waist_circumference_cm') }}" placeholder="Waist">
                             <x-input-error :messages="$errors->get('waist_circumference_cm')" class="mt-2" />
                        </div>
                        <div>
                             <input id="hip_circumference_cm" class="block mt-1 w-full rounded-full border-transparent bg-white bg-opacity-20 py-3 px-4 text-white focus:border-indigo-500 focus:ring-indigo-500 placeholder-gray-400" type="number" name="hip_circumference_cm" value="{{ old('hip_circumference_cm') }}" placeholder="Hips">
                             <x-input-error :messages="$errors->get('hip_circumference_cm')" class="mt-2" />
                        </div>
                    </div>
                </div>


                <div class="flex flex-col items-center justify-end mt-10">
                    <button type="submit" class="w-full text-center py-3 px-4 bg-white bg-opacity-90 border border-transparent rounded-full font-semibold text-md text-indigo-900 uppercase tracking-widest hover:bg-white focus:bg-white active:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Done
                    </button>
                    
                    <a class="text-sm text-gray-300 hover:text-white rounded-md mt-4" href="{{ route('feed') }}">
                        {{ __('Skip for now') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout> 