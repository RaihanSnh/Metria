<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                   @if(isset($posts) && $posts->count() > 0)
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Posts</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                            @foreach($posts as $post)
                                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                                    <img src="{{ asset('storage/' . $post->post_image_url) }}" alt="{{ $post->caption }}" class="w-full h-48 object-cover">
                                    <div class="p-4">
                                        <p class="text-sm text-gray-600 truncate">{{ $post->caption }}</p>
                                        <div class="flex items-center justify-between mt-3">
                                            <span class="text-xs text-gray-500">{{ $post->created_at->diffForHumans() }}</span>
                                            @if($post->items->count() > 0)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                    {{ $post->items->count() }} items
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                        <p>{{ __("You're logged in!") }}</p>
                        @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 