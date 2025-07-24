@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
@endphp

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
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Outfit Display -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Outfit Items</h3>
                            
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                                @forelse($outfitItems as $item)
                                    <div class="relative rounded-lg overflow-hidden shadow-sm">
                                        <img 
                                            src="{{ 
                                                isset($item['image_url']) 
                                                    ? (Str::startsWith($item['image_url'], ['http://', 'https://']) 
                                                        ? $item['image_url'] 
                                                        : Storage::url($item['image_url']))
                                                    : asset('images/placeholder.jpg') 
                                            }}" 
                                            alt="{{ $item['item_name'] ?? 'Item' }}" 
                                            class="w-full h-32 object-cover"
                                            onerror="this.src='{{ asset('images/placeholder.jpg') }}'">
                                        <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-60 text-white p-1 text-xs truncate">
                                            {{ $item['item_name'] ?? 'Unnamed Item' }}
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-span-full text-center text-gray-500 py-8">
                                        <p>This outfit doesn't have any items yet.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                        
                        <!-- Outfit Details -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Details</h3>
                            <dl class="space-y-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Created</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $outfit->created_at->format('F j, Y') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $outfit->updated_at->format('F j, Y') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Total Items</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ count($outfitItems) }}</dd>
                                </div>
                            </dl>
                            
                            <div class="mt-6 flex space-x-3">
                                <a href="{{ route('outfits.edit', $outfit) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-25 transition ease-in-out duration-150">
                                    Edit Outfit
                                </a>
                                
                                <form action="{{ route('outfits.destroy', $outfit) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this outfit?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-25 transition ease-in-out duration-150">
                                        Delete
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
