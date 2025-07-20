<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Add Item to Wardrobe
            </h2>
            <x-back-link />
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <x-back-link :href="route('wardrobe.index')"/>
                    
                    <form action="{{ route('wardrobe.store') }}" method="POST" enctype="multipart/form-data">

                        @csrf

                        <!-- Item Name -->
                        <div>
                            <x-input-label for="item_name" :value="__('Item Name')" />
                            <x-text-input id="item_name" class="block mt-1 w-full" type="text" name="item_name" required autofocus />
                            <x-input-error :messages="$errors->get('item_name')" class="mt-2" />
                        </div>

                        <!-- Clothing Type -->
                        <div class="mt-4">
                            <x-input-label for="clothing_type" :value="__('Clothing Type')" />
                            <select id="clothing_type" name="clothing_type" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="" disabled>Select a type</option>
                                <option value="top">Top</option>
                                <option value="outerwear">Outerwear</option>
                                <option value="bottom">Bottom</option>
                                <option value="full_body">Full Body</option>
                                <option value="shoes">Shoes</option>
                                <option value="accessory">Accessory</option>
                                <option value="hat">Hat</option>
                            </select>
                        </div>

                        <!-- Image Upload -->
                        <div class="mt-4">
                            <x-input-label for="image" :value="__('Image')" />
                            <input @change="handleFileSelect" type="file" id="image" name="image" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100"/>
                            <div x-show="previewUrl" class="mt-4">
                                <img :src="previewUrl" class="h-48 w-auto rounded-lg">
                            </div>
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>

                        <!-- Error Message -->
                        <div x-show="errorMessage" class="mt-4 text-sm text-red-600" x-text="errorMessage"></div>
                        
                        <!-- Submit Button -->
                        <div class="flex items-center justify-end mt-6">
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Add Item
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function wardrobeForm() {
            return {
                loading: false,
                buttonText: 'Save Item',
                formData: {
                    item_name: '',
                    clothing_type: '',
                    image: null
                },
                previewUrl: null,
                errorMessage: '',
                handleFileSelect(event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.formData.image = file;
                        this.previewUrl = URL.createObjectURL(file);
                    }
                },
                submitForm() {
                    this.loading = true;
                    this.buttonText = 'Saving...';
                    this.errorMessage = '';
                    
                    const fd = new FormData();
                    fd.append('item_name', this.formData.item_name);
                    fd.append('clothing_type', this.formData.clothing_type);
                    fd.append('image', this.formData.image);

                    fetch('{{ url('/api/wardrobe') }}', {
                        method: 'POST',
                        headers: {
                            'Authorization': `Bearer ${localStorage.getItem('api_token')}`,
                            'Accept': 'application/json',
                        },
                        body: fd
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            window.location.href = '{{ route('wardrobe.index') }}';
                        } else {
                            this.errorMessage = data.message || 'An error occurred.';
                        }
                    })
                    .catch(err => {
                        this.errorMessage = 'An unexpected error occurred. Please check your connection.';
                    })
                    .finally(() => {
                        this.loading = false;
                        this.buttonText = 'Save Item';
                    });
                }
            }
        }
    </script>
    @endpush
</x-app-layout> 