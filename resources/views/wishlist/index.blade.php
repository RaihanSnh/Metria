<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Wishlist') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if (session('success'))
                        <div class="mb-4 font-medium text-sm text-green-600 bg-green-100 border border-green-400 rounded-md p-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($wishlistItems->isEmpty())
                        <p class="text-gray-500">Your wishlist is empty.</p>
                        <a href="{{ route('products.index') }}" class="mt-4 inline-block text-indigo-600 hover:text-indigo-900">
                            Start shopping now!
                        </a>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($wishlistItems as $product)
                                <div class="border rounded-lg overflow-hidden shadow-lg">
                                    <a href="{{ route('products.show', $product) }}">
                                        <img src="{{ Storage::url($product->image_url) }}" alt="{{ $product->name }}" class="w-full h-64 object-cover">
                                    </a>
                                    <div class="p-4">
                                        <h3 class="font-bold text-lg">{{ $product->name }}</h3>
                                        <p class="text-gray-600 text-sm mb-2">{{ $product->description }}</p>
                                        <div class="flex justify-between items-center mt-4">
                                            <span class="text-gray-800 font-bold text-xl">Rp{{ number_format($product->price, 2) }}</span>
                                            <form action="{{ route('wishlist.destroy', $product) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-semibold">
                                                    Remove
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-8">
                            {{ $wishlistItems->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 