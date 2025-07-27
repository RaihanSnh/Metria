<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $outfit->name }}
            </h2>
            <a href="{{ route('outfits.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 disabled:opacity-25 transition ease-in-out duration-150">
                Back to Outfits
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Outfit Preview -->
                        <div class="lg:col-span-2">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Outfit Items</h3>
                            
                            @if($outfitItems->isEmpty())
                                <div class="text-center text-gray-500 py-16 border border-dashed border-gray-300 rounded-lg">
                                    <p>No items in this outfit yet.</p>
                                </div>
                            @else
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                    @foreach($outfitItems as $item)
                                        <div class="relative rounded-lg overflow-hidden shadow-sm bg-gray-50">
                                            <div class="w-full h-48 bg-cover bg-center" 
                                                 style="background-image: url('{{ $item['image_url'] ?? 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgZmlsbD0iI2RkZCIvPjx0ZXh0IHg9IjEwMCIgeT0iMTAwIiBmb250LWZhbWlseT0iQXJpYWwiIGZvbnQtc2l6ZT0iMTQiIGZpbGw9IiM5OTkiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj5ObyBJbWFnZTwvdGV4dD48L3N2Zz4=' }}')"></div>
                                            <div class="p-3">
                                                <h4 class="font-medium text-sm text-gray-900 truncate">{{ $item['item_name'] ?? 'Unnamed Item' }}</h4>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        
                        <!-- Outfit Details -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Details</h3>
                            <dl class="space-y-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Name</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $outfit->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Created</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $outfit->created_at->format('F j, Y') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Items</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ count($outfitItems) }}</dd>
                                </div>
                            </dl>
                            
                            <div class="mt-8 flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                                <a href="{{ route('outfits.edit', $outfit) }}" class="inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-25 transition ease-in-out duration-150">
                                    Edit Outfit
                                </a>
                                
                                <form action="{{ route('outfits.destroy', $outfit) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this outfit?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex justify-center items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-25 transition ease-in-out duration-150">
                                        Delete Outfit
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
