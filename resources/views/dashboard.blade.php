<x-app-layout>
    <x-slot name="header">
        <div class="md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-800 sm:text-3xl sm:truncate">
                    Welcome back, {{ auth()->user()->first_name }}!
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Here's a snapshot of your Metria activity.
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <div class="inline-block rounded-full overflow-hidden w-16 h-16">
                    @if(auth()->user()->profile_picture_url)
                        <img src="{{ auth()->user()->profile_picture_url }}" alt="{{ auth()->user()->full_name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-primary-500 flex items-center justify-center text-white font-bold text-2xl">
                            {{ substr(auth()->user()->full_name, 0, 1) }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats -->
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
                <div class="bg-white rounded-xl shadow-soft border border-neutral-200">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-neutral-500 truncate">
                                        Orders Placed
                                    </dt>
                                    <dd class="text-2xl font-bold text-neutral-900">
                                        {{ $stats['orders_placed'] }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-soft border border-neutral-200">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-secondary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-neutral-500 truncate">
                                        Wishlist Items
                                    </dt>
                                    <dd class="text-2xl font-bold text-neutral-900">
                                        {{ $stats['wishlist_items'] }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-soft border border-neutral-200">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-accent-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-neutral-500 truncate">
                                        Affiliate Earnings
                                    </dt>
                                    <dd class="text-2xl font-bold text-neutral-900">
                                        ${{ number_format($stats['affiliate_earnings'], 2) }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-soft border border-neutral-200">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-neutral-500 truncate">
                                        Posts Created
                                    </dt>
                                    <dd class="text-2xl font-bold text-neutral-900">
                                        {{ $stats['posts_created'] }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions & Affiliate Status -->
            <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-soft border border-neutral-200">
                        <div class="px-6 py-4 border-b border-neutral-200">
                            <h3 class="text-lg font-medium text-neutral-900">Quick Actions</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <a href="{{ route('posts.create') }}" class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 transition-all duration-200 ease-in-out bg-primary-600 text-white hover:bg-primary-700 focus:ring-primary-500 shadow-sm hover:shadow-md">
                                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Create New Post
                            </a>
                            <a href="{{ route('wardrobe.create') }}" class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 transition-all duration-200 ease-in-out border-neutral-300 text-neutral-700 bg-white hover:bg-neutral-50 focus:ring-neutral-500">
                                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Add to Wardrobe
                            </a>
                            <a href="{{ route('outfits.create') }}" class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 transition-all duration-200 ease-in-out border-neutral-300 text-neutral-700 bg-white hover:bg-neutral-50 focus:ring-neutral-500">
                                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                </svg>
                                Create an Outfit
                            </a>
                            <a href="{{ route('products.index') }}" class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 transition-all duration-200 ease-in-out border-neutral-300 text-neutral-700 bg-white hover:bg-neutral-50 focus:ring-neutral-500">
                                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                Shop Products
                            </a>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-soft border border-neutral-200 mt-6">
                        @if(auth()->user()->is_affiliate)
                            <div class="px-6 py-4 border-b border-neutral-200">
                                <h3 class="text-lg font-medium text-neutral-900">Affiliate Status</h3>
                            </div>
                            <div class="p-6">
                                <p class="text-sm text-neutral-600 mb-4">You are an active affiliate. Share your link to earn commissions.</p>
                                <div class="flex items-center space-x-2">
                                    <input type="text" value="{{ route('register', ['ref' => auth()->user()->username]) }}" class="block w-full px-3 py-2 border border-neutral-300 rounded-lg shadow-sm placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200" readonly>
                                    <button class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 transition-all duration-200 ease-in-out bg-secondary-600 text-white hover:bg-secondary-700 focus:ring-secondary-500 shadow-sm hover:shadow-md px-3 py-1.5 text-xs">Copy</button>
                                </div>
                                <a href="{{ route('affiliate.dashboard') }}" class="mt-4 w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 transition-all duration-200 ease-in-out bg-secondary-600 text-white hover:bg-secondary-700 focus:ring-secondary-500 shadow-sm hover:shadow-md">
                                    Go to Affiliate Dashboard
                                </a>
                            </div>
                        @else
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-neutral-900">Become an Affiliate</h3>
                                <p class="text-sm text-neutral-600 mt-2 mb-4">
                                    Earn commissions by sharing your favorite products and outfits with your friends and followers.
                                </p>
                                <a href="{{ route('affiliate.register') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 transition-all duration-200 ease-in-out bg-accent-500 text-white hover:bg-accent-600 focus:ring-accent-500 shadow-sm hover:shadow-md px-3 py-1.5 text-xs">
                                    Join Affiliate Program
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <!-- Recent Posts -->
                    <div class="bg-white rounded-xl shadow-soft border border-neutral-200">
                        <div class="px-6 py-4 border-b border-neutral-200">
                            <h3 class="text-lg font-medium text-neutral-900">Your Recent Posts</h3>
                        </div>
                        <div class="p-6">
                            @if($recent_posts->count() > 0)
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    @foreach($recent_posts as $post)
                                        <div class="bg-white rounded-xl shadow-soft border border-neutral-200 overflow-hidden">
                                            @if($post->post_image_url)
                                            <img src="{{ $post->post_image_url }}" alt="Post" class="w-full h-64 object-cover">
                                            @else
                                            <div class="w-full h-64 bg-neutral-100 flex items-center justify-center">
                                                <svg class="w-12 h-12 text-neutral-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                            @endif
                                            <div class="p-4">
                                                <p class="text-sm text-neutral-600 line-clamp-2">{{ $post->caption }}</p>
                                                <div class="flex items-center justify-between mt-3">
                                                    <span class="text-xs text-neutral-500">{{ $post->created_at->diffForHumans() }}</span>
                                                    @if($post->postItems->count() > 0)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800">{{ $post->postItems->count() }} items</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                        <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-neutral-900">No posts yet</h3>
                                    <p class="mt-1 text-sm text-neutral-500">Get started by creating a new post.</p>
                                    <div class="mt-6">
                                        <a href="{{ route('posts.create') }}" class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 transition-all duration-200 ease-in-out bg-primary-600 text-white hover:bg-primary-700 focus:ring-primary-500 shadow-sm hover:shadow-md">
                                            Create Your First Post
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Recent Wardrobe Items -->
                    <div class="bg-white rounded-xl shadow-soft border border-neutral-200 mt-6">
                        <div class="px-6 py-4 border-b border-neutral-200">
                            <h3 class="text-lg font-medium text-neutral-900">Recent Wardrobe Additions</h3>
                        </div>
                        <div class="p-6">
                            @if($recent_wardrobe_items->count() > 0)
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                                    @foreach($recent_wardrobe_items as $item)
                                        <div class="bg-white rounded-lg shadow-soft border border-neutral-200 overflow-hidden hover:shadow-medium transition-shadow duration-200 cursor-pointer">
                                            @if($item->item_image_url)
                                                <img src="{{ $item->item_image_url }}" alt="{{ $item->item_name }}" class="w-full h-32 object-cover">
                                            @else
                                                <div class="w-full h-32 bg-neutral-100 flex items-center justify-center">
                                                     <svg class="w-8 h-8 text-neutral-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                </div>
                                            @endif
                                            <div class="p-3">
                                                <h4 class="text-sm font-medium text-neutral-800 truncate">{{ $item->item_name }}</h4>
                                                <p class="text-xs text-neutral-500">{{ $item->clothing_type }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-neutral-900">Your wardrobe is empty</h3>
                                    <p class="mt-1 text-sm text-neutral-500">Get started by adding your first item.</p>
                                    <div class="mt-6">
                                        <a href="{{ route('wardrobe.create') }}" class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 transition-all duration-200 ease-in-out bg-primary-600 text-white hover:bg-primary-700 focus:ring-primary-500 shadow-sm hover:shadow-md">
                                            Add First Item
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 