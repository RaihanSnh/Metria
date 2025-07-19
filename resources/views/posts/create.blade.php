<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Post') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Post Image/Video -->
                        <div>
                            <x-input-label for="media" :value="__('Upload Photo/Video')" />
                            <input type="file" name="media" id="media" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100" required>
                            <x-input-error :messages="$errors->get('media')" class="mt-2" />
                        </div>

                        <!-- Caption -->
                        <div class="mt-4">
                            <x-input-label for="caption" :value="__('Caption')" />
                            <textarea id="caption" name="caption" rows="4" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('caption') }}</textarea>
                            <x-input-error :messages="$errors->get('caption')" class="mt-2" />
                        </div>

                        <!-- Tag Products Dropdown -->
                        <div class="mt-6">
                            <x-input-label for="products" :value="__('Tag Products')" />
                            <select name="products[]" id="products" multiple class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->condition }})</option>
                                @endforeach
                            </select>
                            <p class="text-sm text-gray-500 mt-1">Hold down the Ctrl (windows) or Command (Mac) button to select multiple options.</p>
                            <x-input-error :messages="$errors->get('products')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button class="ml-4">
                                {{ __('Post') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 