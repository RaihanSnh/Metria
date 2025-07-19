<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $product->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                        <!-- Product Image -->
                        <div>
                            <img src="{{ asset('storage/' . $product->image_url) }}" alt="{{ $product->name }}" class="w-full h-auto object-cover rounded-lg shadow-md">
                        </div>

                        <!-- Product Details & Purchase Options -->
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $product->name }}</h1>
                            <p class="text-gray-600 mb-6">
                                {{ $product->description }}
                            </p>

                            <!-- Size Recommendation -->
                            @if($recommendedSize)
                                <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6" role="alert">
                                    <p class="font-bold">Our Recommendation</p>
                                    <p>Based on your measurements, we recommend size: <strong>{{ $recommendedSize }}</strong>.</p>
                                </div>
                            @endif

                             <!-- Purchase Options -->
                            <div class="mt-6 border-t pt-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Purchase Options</h3>
                                <div class="space-y-4">
                                    
                                    <!-- Boutique Option -->
                                    @if(isset($productVariations['boutique']))
                                        @php $boutiqueProduct = $productVariations['boutique']; @endphp
                                        <div class="p-4 border rounded-lg flex justify-between items-center">
                                            <div>
                                                <p class="font-semibold text-lg">Boutique</p>
                                                <p class="text-gray-800 font-bold">Rp {{ number_format($boutiqueProduct->price, 2, ',', '.') }}</p>
                                            </div>
                                            <form action="{{ route('orders.store') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $boutiqueProduct->id }}">
                                                <input type="hidden" name="quantity" value="1">
                                                <x-primary-button>Buy Now</x-primary-button>
                                            </form>
                                        </div>
                                    @endif

                                    <!-- Archive Option -->
                                     @if(isset($productVariations['archive']))
                                        @php $archiveProduct = $productVariations['archive']; @endphp
                                        <div class="p-4 border rounded-lg flex justify-between items-center">
                                            <div>
                                                <p class="font-semibold text-lg">Archive</p>
                                                <p class="text-gray-800 font-bold">Rp {{ number_format($archiveProduct->price, 2, ',', '.') }}</p>
                                            </div>
                                            <form action="{{ route('orders.store') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $archiveProduct->id }}">
                                                <input type="hidden" name="quantity" value="1">
                                                <x-primary-button>Buy Now</x-primary-button>
                                            </form>
                                        </div>
                                    @else
                                        <!-- Optional: Show disabled state if you want -->
                                        <div class="p-4 border rounded-lg flex justify-between items-center bg-gray-50 opacity-50">
                                            <div>
                                                <p class="font-semibold text-lg">Archive</p>
                                                <p class="text-gray-500">Currently unavailable</p>
                                            </div>
                                            <x-primary-button disabled>Buy Now</x-primary-button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 