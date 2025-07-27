@php
    use Illuminate\Support\Facades\Storage;
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $wardrobe->item_name }}
            </h2>
            <div class="flex space-x-3">
                <a href="{{ route('wardrobe.edit', $wardrobe) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-25 transition ease-in-out duration-150">
                    Edit Item
                </a>
                <a href="{{ route('wardrobe.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 disabled:opacity-25 transition ease-in-out duration-150">
                    Back to Wardrobe
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
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

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Item Image -->
                        <div>
                            <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden">
                                <img src="{{ $wardrobe->item_image_url ? Storage::url($wardrobe->item_image_url) : asset('images/placeholder.jpg') }}" 
                                     alt="{{ $wardrobe->item_name }}" 
                                     class="w-full h-full object-cover"
                                     onerror="this.src='{{ asset('images/placeholder.jpg') }}'">
                            </div>
                        </div>

                        <!-- Item Details -->
                        <div>
                            <div class="space-y-6">
                                <div>
                                    <h1 class="text-3xl font-bold text-gray-900">{{ $wardrobe->item_name }}</h1>
                                    <p class="mt-2 text-lg text-gray-600 capitalize">{{ str_replace('_', ' ', is_object($wardrobe->clothing_type) ? $wardrobe->clothing_type->value : $wardrobe->clothing_type) }}</p>
                                </div>

                                <div class="space-y-4">
                                    @if($wardrobe->brand)
                                        <div class="flex items-center">
                                            <span class="text-sm font-medium text-gray-500 w-24">Brand:</span>
                                            <span class="text-sm text-gray-900">{{ $wardrobe->brand }}</span>
                                        </div>
                                    @endif

                                    @if($wardrobe->color)
                                        <div class="flex items-center">
                                            <span class="text-sm font-medium text-gray-500 w-24">Color:</span>
                                            <span class="text-sm text-gray-900">{{ $wardrobe->color }}</span>
                                        </div>
                                    @endif

                                    @if($wardrobe->size)
                                        <div class="flex items-center">
                                            <span class="text-sm font-medium text-gray-500 w-24">Size:</span>
                                            <span class="text-sm text-gray-900">{{ $wardrobe->size }}</span>
                                        </div>
                                    @endif

                                    @if($wardrobe->material)
                                        <div class="flex items-center">
                                            <span class="text-sm font-medium text-gray-500 w-24">Material:</span>
                                            <span class="text-sm text-gray-900">{{ $wardrobe->material }}</span>
                                        </div>
                                    @endif

                                    @if($wardrobe->purchase_date)
                                        <div class="flex items-center">
                                            <span class="text-sm font-medium text-gray-500 w-24">Purchased:</span>
                                            <span class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($wardrobe->purchase_date)->format('M d, Y') }}</span>
                                        </div>
                                    @endif

                                    @if($wardrobe->price)
                                        <div class="flex items-center">
                                            <span class="text-sm font-medium text-gray-500 w-24">Price:</span>
                                            <span class="text-sm text-gray-900">IDR {{ number_format($wardrobe->price, 0, ',', '.') }}</span>
                                        </div>
                                    @endif

                                    <div class="flex items-center">
                                        <span class="text-sm font-medium text-gray-500 w-24">Added:</span>
                                        <span class="text-sm text-gray-900">{{ $wardrobe->created_at->format('M d, Y') }}</span>
                                    </div>
                                </div>

                                @if($wardrobe->notes)
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-500 mb-2">Notes:</h3>
                                        <p class="text-sm text-gray-900 bg-gray-50 p-3 rounded-lg">{{ $wardrobe->notes }}</p>
                                    </div>
                                @endif

                                <!-- Action Buttons -->
                                <div class="flex space-x-4 pt-6 border-t border-gray-200">
                                    <a href="{{ route('wardrobe.edit', $wardrobe) }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Edit Item
                                    </a>
                                    
                                    <form action="{{ route('wardrobe.destroy', $wardrobe) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this item?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                            Delete Item
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
