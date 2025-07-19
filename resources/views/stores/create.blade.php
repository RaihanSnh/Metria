<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Become a Seller') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Create Your Store</h3>
                    <form action="{{ route('stores.store') }}" method="POST">
                        @csrf
                        <div class="space-y-6">
                            <div>
                                <x-input-label for="store_name" :value="__('Store Name')" />
                                <x-text-input id="store_name" class="block mt-1 w-full" type="text" name="store_name" :value="old('store_name')" required autofocus />
                                <x-input-error :messages="$errors->get('store_name')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="description" :value="__('Description')" />
                                <textarea id="description" name="description" rows="4" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description') }}</textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="city" :value="__('City')" />
                                    <x-text-input id="city" class="block mt-1 w-full" type="text" name="city" :value="old('city')" required />
                                    <x-input-error :messages="$errors->get('city')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="province" :value="__('Province')" />
                                    <x-text-input id="province" class="block mt-1 w-full" type="text" name="province" :value="old('province')" required />
                                    <x-input-error :messages="$errors->get('province')" class="mt-2" />
                                </div>
                            </div>

                            <div>
                                <x-primary-button>
                                    {{ __('Create Store') }}
                                </x-primary-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 