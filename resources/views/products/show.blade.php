<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $product->name }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ 
        selectedCondition: null, 
        selectedSize: null,
        variations: {{ json_encode($productVariations) }},
        stock: {{ json_encode($stockBySize) }},
        selectCondition(condition) {
            this.selectedCondition = condition;
            this.selectedSize = null;
        },
        totalStockForCondition() {
            if (!this.selectedCondition || !this.stock[this.selectedCondition]) {
                return 0;
            }
            return Object.values(this.stock[this.selectedCondition]).reduce((total, count) => total + Number(count), 0);
        }
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                        
                        <div>
                            <img src="{{ asset('storage/' . $product->image_url) }}" alt="{{ $product->name }}" class="w-full h-auto object-cover rounded-lg shadow-md">
                        </div>

                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">{{ $product->name }}</h1>
                            
                            <div class="flex items-center space-x-4 text-sm text-gray-500 my-3">
                                <span>Type: <span class="font-semibold text-gray-700">{{ Str::title($product->clothing_type) }}</span></span>
                                @if($product->materials->isNotEmpty())
                                <span>Materials: <span class="font-semibold text-gray-700">{{ $product->materials->pluck('name')->join(', ') }}</span></span>
                                @endif
                                @if($product->genres->isNotEmpty())
                                <span>Genres: <span class="font-semibold text-gray-700">{{ $product->genres->pluck('name')->join(', ') }}</span></span>
                                @endif
                            </div>

                            <p class="text-gray-600 mb-6">{{ $product->description }}</p>

                            <div class="mb-4">
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Select Condition</h3>
                                <div class="flex space-x-2">
                                    <button @click="selectCondition('new')" :class="selectedCondition === 'new' ? 'bg-indigo-600 text-white' : 'bg-gray-200'" class="px-4 py-2 rounded-md text-sm font-medium">Boutique</button>
                                    <button @click="selectCondition('pre-loved')" :class="selectedCondition === 'pre-loved' ? 'bg-indigo-600 text-white' : 'bg-gray-200'" class="px-4 py-2 rounded-md text-sm font-medium">Archive</button>
                                </div>
                            </div>
                            
                            <div x-show="selectedCondition" class="mt-4 border-t pt-4" style="display: none;">
                                <div class="mb-4">
                                    <p class="text-2xl font-bold text-gray-900" x-text="variations[selectedCondition] ? 'Rp ' + parseInt(variations[selectedCondition].price).toLocaleString('id-ID') : ''"></p>
                                    <p class="text-md font-semibold text-gray-800 mt-1">
                                        Stock: <span x-text="totalStockForCondition()"></span>
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="text-sm font-medium mb-2">Select Size (Stock Available):</p>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($product->sizeCharts as $size)
                                            <button 
                                                type="button"
                                                @click="selectedSize = (stock[selectedCondition] && stock[selectedCondition]['{{$size->size_label}}'] > 0) ? '{{$size->size_label}}' : null"
                                                :class="{
                                                    'bg-indigo-600 text-white': selectedSize == '{{$size->size_label}}',
                                                    'bg-gray-200 text-gray-400 cursor-not-allowed': !(stock[selectedCondition] && stock[selectedCondition]['{{$size->size_label}}'] > 0),
                                                    'hover:bg-gray-300': (stock[selectedCondition] && stock[selectedCondition]['{{$size->size_label}}'] > 0)
                                                }"
                                                class="px-3 py-1 border rounded-md text-sm flex items-center"
                                                :disabled="!(stock[selectedCondition] && stock[selectedCondition]['{{$size->size_label}}'] > 0)">
                                                <span>{{ $size->size_label }}</span>
                                                <span class="text-xs ml-1.5" x-text="`(${stock[selectedCondition] ? (stock[selectedCondition]['{{$size->size_label}}'] || 0) : 0})`"></span>
                                            </button>
                                        @endforeach
                                    </div>
                                </div>

                                <form x-show="selectedSize" action="{{ route('orders.store') }}" method="POST" class="mt-4" style="display: none;">
                                    @csrf
                                    <input type="hidden" name="product_id" :value="variations[selectedCondition].id">
                                    <input type="hidden" name="quantity" value="1">
                                    <input type="hidden" name="size" x-model="selectedSize">
                                    <x-primary-button>Buy Now</x-primary-button>
                                </form>
                            </div>
                        </div>
                    </div>

                    @if($product->sizeCharts->isNotEmpty())
                    <div class="mt-8 border-t pt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Size Guide</h3>
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th class="py-2 px-4">Size</th>
                                    <th class="py-2 px-4">Bust (cm)</th>
                                    <th class="py-2 px-4">Waist (cm)</th>
                                    <th class="py-2 px-4">Hip (cm)</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($product->sizeCharts as $size)
                                <tr class="bg-white border-b">
                                    <td class="py-2 px-4 font-medium">{{ $size->size_label }}</td>
                                    <td class="py-2 px-4">{{ $size->chest_cm ?? '-' }}</td>
                                    <td class="py-2 px-4">{{ $size->waist_cm ?? '-' }}</td>
                                    <td class="py-2 px-4">{{ $size->hip_cm ?? '-' }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 