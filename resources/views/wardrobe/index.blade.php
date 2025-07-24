<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                My Digital Wardrobe
            </h2>
            <a href="{{ route('wardrobe.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add New Item
            </a>
        </div>
    </x-slot>

    <div class="py-12"
         x-data='{
            loading: false,
            items: @json($wardrobeItems->items()),
            filteredItems: @json($wardrobeItems->items()),
            totalItems: {{ $wardrobeItems->total() }},
            selectedCategory: "all",
            filterItems() {
                this.filteredItems = this.selectedCategory === "all"
                    ? this.items
                    : this.items.filter(item => item.clothing_type === this.selectedCategory);
            }
         }'
         x-init="filterItems()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Loading state -->
            <div x-show="loading" class="text-center py-12">
                <div class="inline-flex items-center px-4 py-2 font-semibold leading-6 text-sm shadow rounded-md text-gray-500 bg-white">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Loading your wardrobe...
                </div>
            </div>

            <!-- Filter and Stats -->
            <div x-show="!loading && totalItems > 0" class="mb-6 bg-white rounded-lg shadow-sm p-4">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center space-x-4 mb-4 sm:mb-0">
                        <div class="text-sm text-gray-500">
                            <span class="font-medium text-gray-900" x-text="totalItems"></span> items in your wardrobe
                        </div>
                        <div class="text-sm text-gray-500" x-show="selectedCategory !== 'all'">
                            <span class="capitalize" x-text="selectedCategory.replace('_', ' ')"></span> items
                        </div>
                    </div>
                    
                    <!-- Category Filter -->
                    <div class="flex items-center space-x-2">
                        <label class="text-sm font-medium text-gray-700">Filter by:</label>
                        <select x-model="selectedCategory" @change="filterItems" class="rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="all">All Categories</option>
                            <option value="top">Tops</option>
                            <option value="outerwear">Outerwear</option>
                            <option value="bottom">Bottoms</option>
                            <option value="full_body">Full Body</option>
                            <option value="shoes">Shoes</option>
                            <option value="accessory">Accessories</option>
                            <option value="hat">Hats</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Empty state -->
            <div x-show="!loading && totalItems === 0" class="text-center bg-white p-12 rounded-lg shadow-sm">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-gray-100">
                    <svg class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2h4a1 1 0 110 2h-1v12a2 2 0 01-2 2H5a2 2 0 01-2-2V6H2a1 1 0 110-2h4zM8 6v10h8V6H8z" />
                    </svg>
                </div>
                <h3 class="mt-4 text-lg font-medium text-gray-900">Your digital wardrobe is empty!</h3>
                <p class="mt-2 text-gray-500 max-w-sm mx-auto">Start building your sustainable fashion journey by digitizing the clothes you already own.</p>
                <div class="mt-6">
                    <a href="{{ route('wardrobe.create') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add Your First Item
                    </a>
                </div>
            </div>

            <!-- Wardrobe Grid -->
            <div x-show="!loading && filteredItems.length > 0" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                <template x-for="item in filteredItems" :key="item.id">
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden group hover:shadow-md transition-shadow duration-200">
                        <div class="relative aspect-square">
                            <img :src="item.item_image_url ? (item.item_image_url.startsWith('http') ? item.item_image_url : '/storage/' + item.item_image_url) : '/images/placeholder.jpg'" 
                                 :alt="item.item_name" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200"
                                 x-on:error="$event.target.src='/images/placeholder.jpg'">
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-200 flex items-center justify-center">
                                <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex space-x-2">
                                    <a :href="'/wardrobe/' + item.id" class="bg-white text-gray-900 p-2 rounded-full hover:bg-gray-100 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <a :href="'/wardrobe/' + item.id + '/edit'" class="bg-white text-gray-900 p-2 rounded-full hover:bg-gray-100 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                            <div class="absolute top-2 left-2">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-900 bg-opacity-80 text-white capitalize" x-text="item.clothing_type ? item.clothing_type.replace('_', ' ') : 'Unknown'"></span>
                            </div>
                        </div>
                        <div class="p-3">
                            <h4 class="font-medium text-sm text-gray-900 truncate" x-text="item.item_name || 'Unnamed Item'"></h4>
                            <div class="mt-1 flex items-center justify-between">
                                <p class="text-xs text-gray-500" x-text="item.brand || 'No brand'"></p>
                                <p class="text-xs text-gray-500" x-text="item.color || ''"></p>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- No filtered results -->
            <div x-show="!loading && totalItems > 0 && filteredItems.length === 0" class="text-center py-12">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-gray-100">
                    <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <h3 class="mt-4 text-lg font-medium text-gray-900">No items found</h3>
                <p class="mt-2 text-gray-500">Try adjusting your filter or add more items to your wardrobe.</p>
            </div>

            <!-- Pagination -->
            @if(isset($wardrobeItems) && $wardrobeItems->hasPages())
                <div class="mt-8">
                    {{ $wardrobeItems->links() }}
                </div>
            @endif

        </div>
    </div>
</x-app-layout>