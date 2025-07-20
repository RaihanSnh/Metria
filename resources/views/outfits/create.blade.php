<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Outfit') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="outfitConstructor()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form :action="formAction" method="POST">
                @csrf
                <input type="hidden" name="items" :value="JSON.stringify(outfitItems)">

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <!-- Outfit Name and Save Button -->
                        <div class="flex justify-between items-center mb-6">
                            <div class="w-1/2">
                                <label for="outfit_name" class="block font-medium text-sm text-gray-700">Outfit Name</label>
                                <input type="text" name="name" id="outfit_name" x-model="outfitName" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="e.g., Casual Friday" required>
                            </div>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Save Outfit
                            </button>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Left Side: Outfit Canvas -->
                            <div class="md:col-span-2 bg-gray-100 p-4 rounded-lg min-h-[60vh] border-dashed border-2 border-gray-300">
                                <h3 class="text-lg font-semibold text-center text-gray-500 mb-4">Outfit Canvas</h3>
                                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                                    <template x-for="(item, index) in outfitItems" :key="item.id">
                                        <div class="relative group">
                                            <img :src="item.image_url" :alt="item.item_name" class="w-full h-auto object-cover rounded-lg">
                                            <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                                <button @click.prevent="removeItem(index)" class="text-white text-2xl">&times;</button>
                                            </div>
                                        </div>
                                    </template>
                                    <div x-show="outfitItems.length === 0" class="col-span-full text-center text-gray-400 py-16">
                                        <p>Select items from your wardrobe to start building your outfit.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Side: Wardrobe Items -->
                            <div class="bg-gray-50 p-4 rounded-lg overflow-y-auto max-h-[60vh]">
                                <h3 class="text-lg font-semibold text-gray-700 mb-4">My Wardrobe</h3>
                                @forelse($wardrobeItems as $type => $items)
                                    <div class="mb-6">
                                        <h4 class="font-bold capitalize text-gray-600 mb-2">{{ str_replace('_', ' ', $type) }}</h4>
                                        <div class="grid grid-cols-2 gap-2">
                                            @foreach($items as $item)
                                                <div @click="addItem(@js($item))" class="cursor-pointer group relative">
                                                    <img src="{{ Storage::url($item->image_url) }}" alt="{{ $item->item_name }}" class="w-full h-auto object-cover rounded-lg">
                                                    <div class="absolute inset-0 bg-black bg-opacity-25 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                                        <span class="text-white text-sm font-bold">Add</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-gray-500">Your digital wardrobe is empty.</p>
                                    <a href="{{ route('wardrobe.create') }}" class="mt-2 inline-block text-indigo-600 hover:text-indigo-900">Add items now</a>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function outfitConstructor() {
            return {
                outfitName: '',
                outfitItems: [],
                formAction: "{{ route('outfits.store') }}",
                
                addItem(item) {
                    if (!this.outfitItems.find(i => i.id === item.id && i.type === 'wardrobe')) {
                        this.outfitItems.push({
                            id: item.id,
                            item_name: item.item_name,
                            image_url: `{{ Storage::url('') }}${item.image_url}`,
                            type: 'digital_wardrobe_item' // We specify the morph type here
                        });
                    }
                },

                removeItem(index) {
                    this.outfitItems.splice(index, 1);
                }
            }
        }
    </script>
</x-app-layout> 