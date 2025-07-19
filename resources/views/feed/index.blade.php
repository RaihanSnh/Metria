<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Community Feed') }}
            </h2>
            <a href="{{ route('posts.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                Create Post
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="bg-green-100 border-green-400 text-green-700 border-l-4 p-4" role="alert">
                    <p class="font-bold">Success</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @forelse ($posts as $post)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <!-- Post Header -->
                        <div class="flex items-center mb-4">
                            <img class="h-10 w-10 rounded-full object-cover" src="{{ $post->user->profile_picture_url ?? 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($post->user->email))) }}" alt="{{ $post->user->full_name }}">
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">{{ $post->user->full_name }}</p>
                                <p class="text-sm text-gray-500">{{ $post->created_at->diffForHumans() }}</p>
                            </div>
                        </div>

                        <!-- Post Media -->
                        <div>
                            @if(in_array(pathinfo($post->post_image_url, PATHINFO_EXTENSION), ['mp4', 'mov', 'avi']))
                                <video controls class="w-full rounded-lg bg-black">
                                    <source src="{{ asset('storage/' . $post->post_image_url) }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            @else
                                <img src="{{ asset('storage/' . $post->post_image_url) }}" alt="Post by {{ $post->user->full_name }}" class="w-full rounded-lg">
                            @endif
                        </div>

                        <!-- Post Caption -->
                        @if($post->caption)
                            <p class="mt-4 text-gray-800">{{ $post->caption }}</p>
                        @endif

                        <!-- Tagged Products -->
                        @if($post->items->isNotEmpty())
                            <div class="mt-4 border-t pt-4">
                                <h4 class="font-semibold text-sm text-gray-600 mb-2">Tagged Products:</h4>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($post->items as $postItem)
                                        @if($postItem->item instanceof \App\Models\Product)
                                            <a href="{{ route('products.show', $postItem->item) }}" class="text-xs bg-indigo-100 text-indigo-800 font-medium px-2.5 py-1 rounded-full hover:bg-indigo-200 transition">
                                                {{ $postItem->item->name }} ({{$postItem->item->condition}})
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-500 py-16">
                    <p>The feed is empty. Be the first to post!</p>
                </div>
            @endforelse

            <div class="mt-8">
                {{ $posts->links() }}
            </div>
        </div>
    </div>
</x-app-layout> 