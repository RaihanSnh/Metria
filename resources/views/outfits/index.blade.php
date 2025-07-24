@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('My Outfits') }}
            </h2>
            <a href="{{ route('outfits.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                {{ __('Create New Outfit') }}
            </a>
        </div>
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

                    @if($outfits->isEmpty())
                        <div class="text-center text-gray-500 py-16">
                            <p>You haven't created any outfits yet.</p>
                            <a href="{{ route('outfits.create') }}" class="mt-4 inline-block text-indigo-600 hover:text-indigo-900 font-semibold">
                                Create your first outfit now!
                            </a>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                            @foreach ($outfits as $outfit)
                                <div class="border rounded-lg overflow-hidden shadow-lg flex flex-col">
                                    <div class="p-4">
                                        <h3 class="font-bold text-lg truncate">{{ $outfit->name }}</h3>
                                        <p class="text-sm text-gray-500">{{ $outfit->created_at->format('M d, Y') }}</p>
                                    </div>
                                    <div class="flex-grow grid grid-cols-2 grid-rows-2 gap-1 p-2 bg-gray-50 min-h-[200px]">
                                        @php
                                            // Direct debug - uncomment if needed
                                            // dd($outfit->items);
                                            $canvasItems = collect($outfit->items ?? [])->take(4);
                                        @endphp

                                        @if($canvasItems->count() > 0)
                                            @foreach($canvasItems as $item)
                                                <div class="w-full h-full bg-cover bg-center rounded min-h-[90px]" 
                                                    style="background-image: url('{{ $item['image_url'] ?? 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgZmlsbD0iI2RkZCIvPjx0ZXh0IHg9IjEwMCIgeT0iMTAwIiBmb250LWZhbWlseT0iQXJpYWwiIGZvbnQtc2l6ZT0iMTQiIGZpbGw9IiM5OTkiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj5JdGVtPC90ZXh0Pjwvc3ZnPg==' }}')">
                                                </div>
                                            @endforeach
                                            
                                            {{-- Fill remaining slots with placeholders --}}
                                            @for($i = $canvasItems->count(); $i < 4; $i++)
                                                <div class="w-full h-full bg-gray-200 rounded min-h-[90px] flex items-center justify-center">
                                                    <span class="text-gray-400 text-xs">Empty</span>
                                                </div>
                                            @endfor
                                        @else
                                            <div class="col-span-2 row-span-2 flex items-center justify-center text-gray-400">
                                                <p>No items in this outfit yet.</p>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="p-4 border-t flex justify-end">
                                        <a href="{{ route('outfits.show', $outfit) }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800">View Details</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-8">
                            {{ $outfits->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>