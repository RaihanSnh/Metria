<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Product') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="productForm()">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Product Name -->
                        <div>
                            <x-input-label for="name" :value="__('Product Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Description -->
                        <div class="mt-4">
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" rows="4" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                             <!-- Price -->
                            <div>
                                <x-input-label for="price" :value="__('Price')" />
                                <x-text-input id="price" class="block mt-1 w-full" type="number" name="price" :value="old('price')" required />
                                <x-input-error :messages="$errors->get('price')" class="mt-2" />
                            </div>
                            <!-- Initial Stock -->
                             <div>
                                <x-input-label for="stock" :value="__('Initial Stock')" />
                                <x-text-input id="stock" class="block mt-1 w-full" type="number" name="stock" :value="old('stock')" required />
                                <x-input-error :messages="$errors->get('stock')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Condition -->
                        <div class="mt-4">
                            <x-input-label :value="__('Condition')" />
                            <div class="flex items-center space-x-4 mt-2">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="condition" value="boutique" class="text-indigo-600" checked>
                                    <span class="ml-2">Boutique (New)</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="condition" value="archive" class="text-indigo-600">
                                    <span class="ml-2">Archive (Pre-loved/etc)</span>
                                </label>
                            </div>
                        </div>

                        <!-- Clothing Type -->
                        <div class="mt-4">
                            <x-input-label for="clothing_type" :value="__('Clothing Type')" />
                            <select name="clothing_type" id="clothing_type" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                @foreach($clothingTypes as $type)
                                    <option value="{{ $type->value }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Materials & Genres -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                            <!-- Materials -->
                            <div>
                                <x-input-label for="materials" :value="__('Materials')" />
                                <div class="grid grid-cols-2 gap-2 mt-2">
                                    @foreach($materials as $material)
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="materials[]" value="{{ $material->id }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm text-gray-600">{{ $material->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            <!-- Genres -->
                            <div>
                                <x-input-label for="genres" :value="__('Genres')" />
                                <div class="grid grid-cols-2 gap-2 mt-2">
                                    @foreach($genres as $genre)
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="genres[]" value="{{ $genre->id }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm text-gray-600">{{ $genre->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Dynamic Size Chart -->
                        <div class="mt-6">
                            <h3 class="text-lg font-medium text-gray-900">Size Chart</h3>
                            <div class="mt-2 space-y-4">
                                <template x-for="(size, index) in sizes" :key="index">
                                    <div class="p-4 border rounded-md flex items-end space-x-4">
                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 flex-grow">
                                            <div>
                                                <label class="text-sm">Size Name (e.g., S, M, All Size)</label>
                                                <input type="text" :name="`sizes[${index}][name]`" class="block mt-1 w-full text-sm" placeholder="S">
                                            </div>
                                            <div>
                                                <label class="text-sm">Bust (cm)</label>
                                                <input type="number" :name="`sizes[${index}][bust]`" class="block mt-1 w-full text-sm" placeholder="90">
                                            </div>
                                            <div>
                                                <label class="text-sm">Waist (cm)</label>
                                                <input type="number" :name="`sizes[${index}][waist]`" class="block mt-1 w-full text-sm" placeholder="70">
                                            </div>
                                            <div>
                                                <label class="text-sm">Hip (cm)</label>
                                                <input type="number" :name="`sizes[${index}][hip]`" class="block mt-1 w-full text-sm" placeholder="95">
                                            </div>
                                        </div>
                                        <button type="button" @click="removeSize(index)" class="text-red-500 hover:text-red-700">&times;</button>
                                    </div>
                                </template>
                            </div>
                            <button type="button" @click="addSize()" class="mt-2 text-sm text-indigo-600 hover:text-indigo-800">
                                + Add Size
                            </button>
                        </div>


                        <!-- Image -->
                        <div class="mt-4">
                            <x-input-label for="image" :value="__('Product Image')" />
                            <x-text-input id="image" class="block mt-1 w-full" type="file" name="image" required/>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button class="ml-4">
                                {{ __('Add Product') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        function productForm() {
            return {
                sizes: [{ name: '', bust: '', waist: '', hip: '' }],
                addSize() {
                    this.sizes.push({ name: '', bust: '', waist: '', hip: '' });
                },
                removeSize(index) {
                    this.sizes.splice(index, 1);
                }
            }
        }
    </script>
</x-app-layout> 