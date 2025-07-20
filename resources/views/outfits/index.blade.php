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
                                    <div class="flex-grow grid grid-cols-2 grid-rows-2 gap-1 p-2 bg-gray-50">
                                        @foreach ($outfit->items->take(4) as $item)
                                            @if($item->itemable)
                                            <div class="w-full h-full bg-cover bg-center rounded" style="background-image: url('{{ Storage::url($item->itemable->image_url) }}')">
                                            </div>
                                            @endif
                                        @endforeach
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