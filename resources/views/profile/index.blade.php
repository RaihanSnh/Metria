<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold leading-7 text-gray-800 sm:text-3xl sm:truncate">
                    Welcome back, {{ Auth::user()->full_name }}!
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Here's a snapshot of your Metria activity.
                </p>
            </div>
             <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                Edit Profile
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- Stats -->
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
                <div class="bg-white rounded-lg shadow p-5">
                    <dt class="text-sm font-medium text-gray-500 truncate">Orders Placed</dt>
                    <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $stats['orders_placed'] }}</dd>
                </div>
                <div class="bg-white rounded-lg shadow p-5">
                    <dt class="text-sm font-medium text-gray-500 truncate">Wishlist Items</dt>
                    <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $stats['wishlist_items'] }}</dd>
                </div>
                <div class="bg-white rounded-lg shadow p-5">
                    <dt class="text-sm font-medium text-gray-500 truncate">Affiliate Earnings</dt>
                    <dd class="mt-1 text-3xl font-semibold text-gray-900">Rp {{ number_format($stats['affiliate_earnings'], 2) }}</dd>
                </div>
                <div class="bg-white rounded-lg shadow p-5">
                    <dt class="text-sm font-medium text-gray-500 truncate">Posts Created</dt>
                    <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $stats['posts_created'] }}</dd>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: Quick Actions & Affiliate -->
                <div class="lg:col-span-1 space-y-6">
                     <!-- Quick Actions -->
                    <div class="bg-white rounded-lg shadow">
                        <div class="p-4 border-b">
                             <h3 class="text-lg font-medium text-neutral-900">Quick Actions</h3>
                        </div>
                        <div class="p-4 space-y-3">
                            <a href="{{ route('posts.create') }}" class="w-full text-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">Create New Post</a>
                            <a href="{{ route('wardrobe.create') }}" class="w-full text-center block px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Add to Wardrobe</a>
                        </div>
                    </div>
                     <!-- Affiliate Status -->
                    <div class="bg-white rounded-lg shadow">
                         @if(Auth::user()->is_affiliate)
                            <div class="p-4 border-b"><h3 class="text-lg font-medium">Affiliate Status</h3></div>
                            <div class="p-4">
                                <p class="text-sm text-gray-600 mb-2">Share your link to earn commissions:</p>
                                <input type="text" value="{{ route('register', ['ref' => Auth::user()->affiliate_code]) }}" class="w-full border-gray-300 rounded-md shadow-sm" readonly>
                                <a href="{{ route('affiliate.dashboard') }}" class="mt-3 w-full text-center block px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">Affiliate Dashboard</a>
                            </div>
                         @else
                            <div class="p-4">
                                <h3 class="text-lg font-medium">Become an Affiliate</h3>
                                <p class="text-sm text-gray-600 mt-1 mb-3">Earn commissions by sharing your favorite products.</p>
                                <a href="{{ route('affiliate.register') }}" class="w-full text-center block px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">Join Program</a>
                            </div>
                         @endif
                    </div>
                </div>

                <!-- Right Column: Recent Activity -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Your Recent Posts</h3>
                             @if($recent_posts->count() > 0)
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    @foreach($recent_posts as $post)
                                        <div class="bg-gray-50 rounded-lg overflow-hidden">
                                            <a href="#">
                                                <img src="{{ asset('storage/' . $post->post_image_url) }}" alt="{{ $post->caption }}" class="w-full h-40 object-cover">
                                                <div class="p-3">
                                                    <p class="text-sm text-gray-600 truncate">{{ $post->caption ?: 'No caption' }}</p>
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500">You haven't made any posts yet.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout> 