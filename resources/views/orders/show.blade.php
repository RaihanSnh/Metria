<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Order Confirmation') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 bg-white border-b border-gray-200">
                    
                    @if(session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                            <p class="font-bold">Success!</p>
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    <h3 class="text-2xl font-semibold text-gray-800">Order #{{ $order->id }}</h3>
                    <p class="text-sm text-gray-500 mb-6">Placed on {{ $order->created_at->format('F j, Y') }}</p>

                    <div class="border-t border-b border-gray-200 divide-y divide-gray-200">
                        @foreach($order->items as $item)
                        <div class="py-4 flex justify-between items-center">
                            <div>
                                <p class="font-medium text-gray-800">{{ $item->product->name }}</p>
                                <p class="text-sm text-gray-600">
                                    Sold by: <span class="font-semibold text-indigo-600">{{ $item->store->store_name }}</span>
                                </p>
                                <p class="text-sm text-gray-500">Quantity: {{ $item->quantity }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-gray-800">Rp {{ number_format($item->price * $item->quantity, 2, ',', '.') }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="mt-6 text-right">
                        <p class="text-lg font-bold text-gray-900">
                            Total: Rp {{ number_format($order->total_amount, 2, ',', '.') }}
                        </p>
                    </div>

                    <div class="mt-8 text-center">
                        <a href="{{ route('products.index') }}" class="text-indigo-600 hover:text-indigo-900 font-semibold">
                            &larr; Continue Shopping
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout> 