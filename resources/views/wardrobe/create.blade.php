<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Add New Item to Digital Wardrobe') }}
            </h2>
            <x-back-link href="{{ route('wardrobe.index') }}" />
        </div>
    </x-slot>

    <div class="py-12"
         x-data="{
            imagePreview: null,
            handleFileSelect(e) {
                const file = e.target.files[0];
                this.processFile(file);
            },
            handleDrop(e) {
                const file = e.dataTransfer.files[0];
                this.processFile(file);
                const dt = new DataTransfer();
                dt.items.add(file);
                this.$refs.imageInput.files = dt.files;
            },
            processFile(file) {
                if (!file || !file.type.startsWith('image/')) {
                    alert('Please select a valid image file');
                    return;
                }
                if (file.size > 5 * 1024 * 1024) {
                    alert('File size must be less than 5MB');
                    return;
                }
                const reader = new FileReader();
                reader.onload = e => this.imagePreview = e.target.result;
                reader.readAsDataURL(file);
            },
            clearImage() {
                this.imagePreview = null;
                this.$refs.imageInput.value = '';
            }
         }"
         @dragover.prevent
         @drop.prevent
         x-init="() => console.log('Upload form ready')">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    @if ($errors->any())
                        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">Please fix the following errors:</h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <ul class="list-disc list-inside space-y-1">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('wardrobe.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <!-- Left Column: Image Upload -->
                            <div class="space-y-6">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Item Photo</h3>
                                    
                                    <!-- Image Upload -->
                                    <div class="mt-4">
                                        <x-input-label for="image" :value="__('Upload Photo *')" />
                                        <label for="image" class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors cursor-pointer" 
                                               :class="imagePreview ? 'border-indigo-300' : ''"
                                               @dragover.prevent
                                               @drop.prevent="handleDrop">
                                            <div class="space-y-2 text-center w-full" x-show="!imagePreview">
                                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                                <div class="text-sm text-gray-600">
                                                    <span class="font-medium text-indigo-600 hover:text-indigo-500">Upload a photo</span>
                                                    <p class="mt-1">or drag and drop</p>
                                                </div>
                                                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 5MB</p>
                                            </div>
                                            
                                            <div x-show="imagePreview" class="relative w-full flex justify-center">
                                                <img :src="imagePreview" alt="Preview" class="max-h-64 rounded-lg shadow-md">
                                                <button @click.stop="clearImage" type="button" class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-2 hover:bg-red-600 transition-colors shadow-lg">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                            
                                            <input 
                                                x-ref="imageInput"
                                                @change="handleFileSelect"
                                                type="file"
                                                id="image"
                                                name="image"
                                                class="sr-only"
                                                accept="image/*"
                                                required>
                                        </label>
                                        <x-input-error :messages="$errors->get('image')" class="mt-2" />
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column: Item Details -->
                            <div class="space-y-6">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Item Details</h3>
                                    
                                    <!-- Item Name -->
                                    <div>
                                        <x-input-label for="item_name" :value="__('Item Name *')" />
                                        <x-text-input id="item_name" class="block mt-1 w-full" type="text" name="item_name" 
                                                     :value="old('item_name')" required autofocus 
                                                     placeholder="e.g., Blue Denim Jacket" />
                                        <x-input-error :messages="$errors->get('item_name')" class="mt-2" />
                                    </div>

                                    <!-- Clothing Type -->
                                    <div class="mt-4">
                                        <x-input-label for="clothing_type" :value="__('Category *')" />
                                        <select id="clothing_type" name="clothing_type" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                            <option value="">Select category</option>
                                            @foreach($clothingTypes as $type)
                                                <option value="{{ $type->value }}" {{ old('clothing_type') == $type->value ? 'selected' : '' }}>
                                                    {{ ucwords(str_replace('_', ' ', $type->value)) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <x-input-error :messages="$errors->get('clothing_type')" class="mt-2" />
                                    </div>

                                    <!-- Color -->
                                    <div class="mt-4">
                                        <x-input-label for="color" :value="__('Color')" />
                                        <x-text-input id="color" class="block mt-1 w-full" type="text" name="color" 
                                                     :value="old('color')" placeholder="e.g., Navy Blue, Red" />
                                        <x-input-error :messages="$errors->get('color')" class="mt-2" />
                                    </div>

                                    <!-- Brand -->
                                    <div class="mt-4">
                                        <x-input-label for="brand" :value="__('Brand')" />
                                        <x-text-input id="brand" class="block mt-1 w-full" type="text" name="brand" 
                                                     :value="old('brand')" placeholder="e.g., Zara, H&M, Uniqlo" />
                                        <x-input-error :messages="$errors->get('brand')" class="mt-2" />
                                    </div>

                                    <!-- Size -->
                                    <div class="mt-4">
                                        <x-input-label for="size" :value="__('Size')" />
                                        <x-text-input id="size" class="block mt-1 w-full" type="text" name="size" 
                                                     :value="old('size')" placeholder="e.g., M, L, 32, 40" />
                                        <x-input-error :messages="$errors->get('size')" class="mt-2" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information Section -->
                        <div class="border-t border-gray-200 pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Additional Information</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Material -->
                                <div>
                                    <x-input-label for="material" :value="__('Material')" />
                                    <x-text-input id="material" class="block mt-1 w-full" type="text" name="material" 
                                                 :value="old('material')" placeholder="e.g., Cotton, Polyester, Wool" />
                                    <x-input-error :messages="$errors->get('material')" class="mt-2" />
                                </div>

                                <!-- Purchase Date -->
                                <div>
                                    <x-input-label for="purchase_date" :value="__('Purchase Date')" />
                                    <x-text-input id="purchase_date" class="block mt-1 w-full" type="date" name="purchase_date" 
                                                 :value="old('purchase_date')" />
                                    <x-input-error :messages="$errors->get('purchase_date')" class="mt-2" />
                                </div>

                                <!-- Price -->
                                <div>
                                    <x-input-label for="price" :value="__('Price (IDR)')" />
                                    <x-text-input id="price" class="block mt-1 w-full" type="number" name="price" 
                                                 :value="old('price')" placeholder="0" min="0" step="1000" />
                                    <x-input-error :messages="$errors->get('price')" class="mt-2" />
                                </div>
                            </div>

                            <!-- Notes -->
                            <div class="mt-6">
                                <x-input-label for="notes" :value="__('Notes')" />
                                <textarea id="notes" name="notes" rows="3" 
                                         class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm resize-none"
                                         placeholder="Any additional notes about this item...">{{ old('notes') }}</textarea>
                                <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                            <a href="{{ route('wardrobe.index') }}" 
                               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                Cancel
                            </a>
                            <x-primary-button class="ml-4">
                                {{ __('Add to Wardrobe') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>