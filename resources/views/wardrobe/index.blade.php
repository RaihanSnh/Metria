<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                My Digital Wardrobe
            </h2>
            <a href="{{ route('wardrobe.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                Add New Item
            </a>
        </div>
    </x-slot>

    <div class="py-12" x-data="wardrobe()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Loading state -->
            <div x-show="loading" class="text-center">
                <p>Loading your wardrobe...</p>
            </div>

            <!-- Empty state -->
            <div x-show="!loading && totalItems === 0" class="text-center bg-white p-8 rounded-lg shadow-sm">
                <h3 class="text-lg font-medium">Your wardrobe is empty!</h3>
                <p class="text-gray-500 mt-2">Start adding your clothes to build your digital wardrobe.</p>
                <a href="{{ route('wardrobe.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                    Add Your First Item
                </a>
            </div>

            <!-- Wardrobe Grid -->
            <div x-show="!loading && totalItems > 0" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
                <template x-for="item in items" :key="item.id">
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden group">
                        <img :src="item.item_image_url" :alt="item.item_name" class="w-full h-48 object-cover group-hover:opacity-80 transition-opacity">
                        <div class="p-4">
                            <h4 class="font-semibold text-sm truncate" x-text="item.item_name"></h4>
                            <p class="text-xs text-gray-500 capitalize" x-text="item.clothing_type"></p>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function wardrobe() {
            return {
                loading: true,
                items: [],
                totalItems: 0,
                init() {
                    fetch('{{ url('/api/wardrobe') }}', {
                        headers: {
                            'Authorization': `Bearer ${localStorage.getItem('api_token')}`,
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.items = data.data.items;
                            this.totalItems = data.data.total_items;
                        }
                        this.loading = false;
                    })
                    .catch(() => {
                        this.loading = false;
                        // Handle error display
                    });
                }
            }
        }
    </script>
    @endpush
</x-app-layout> 