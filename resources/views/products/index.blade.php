<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('All Products') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @forelse ($products as $product)
                            <div class="border rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition-shadow duration-200 ease-in-out">
                                <a href="{{ route('products.show', $product) }}">
                                    @if($product->image_url)
                                        <img src="{{ asset('storage/' . $product->image_url) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover">
                                    @else
                                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                            <span class="text-gray-500">No Image</span>
                                        </div>
                                    @endif
                                    <div class="p-4">
                                        <h3 class="font-semibold text-lg truncate">{{ $product->name }}</h3>
                                        <p class="text-gray-600 mt-2">Rp {{ number_format($product->price, 2, ',', '.') }}</p>
                                    </div>
                                </a>
                            </div>
                        @empty
                            <p class="col-span-full text-center text-gray-500">No products available at the moment.</p>
                        @endforelse
                    </div>

                    <div class="mt-8">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 