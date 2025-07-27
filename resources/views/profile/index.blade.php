@php
    use Illuminate\Support\Facades\Storage;
@endphp

<x-app-layout>
    <div class="min-h-screen bg-gray-50">
        <!-- Cover Image -->
        <div class="relative h-80 bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600">
            @if($user->cover_image)
                <img src="{{ Storage::url($user->cover_image) }}" alt="Cover" class="w-full h-full object-cover">
            @endif
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-900/50 via-purple-900/30 to-pink-900/50"></div>
        </div>

        <!-- Profile Header -->
        <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-xl -mt-20 relative z-10 border border-gray-100">
                <div class="px-8 py-8">
                    <div class="flex flex-col lg:flex-row items-start lg:items-center space-y-6 lg:space-y-0 lg:space-x-8">
                        <!-- Profile Image -->
                        <div class="flex-shrink-0">
                            <div class="relative">
                                <img src="{{ $user->profile_image ? Storage::url($user->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($user->full_name ?? $user->name) . '&size=160&background=6366f1&color=ffffff' }}" 
                                     alt="{{ $user->full_name ?? $user->name }}" 
                                     class="w-40 h-40 rounded-full border-4 border-white shadow-2xl object-cover ring-4 ring-indigo-50">
                                <div class="absolute bottom-3 right-3 w-8 h-8 bg-green-500 rounded-full border-4 border-white shadow-lg"></div>
                            </div>
                        </div>

                        <!-- Profile Info -->
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between">
                                <div class="flex-1">
                                    <h1 class="text-3xl font-bold text-gray-900 mb-1">{{ $user->full_name ?? $user->name }}</h1>
                                    <p class="text-lg text-indigo-600 font-medium mb-3">@{{ $user->name }}</p>
                                    @if($user->bio)
                                        <p class="text-gray-700 text-base leading-relaxed mb-4 max-w-2xl">{{ $user->bio }}</p>
                                    @endif
                                    <div class="flex flex-wrap items-center text-sm text-gray-600 space-x-6">
                                        @if($user->location)
                                            <span class="flex items-center">
                                                <svg class="w-5 h-5 mr-2 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                                </svg>
                                                {{ $user->location }}
                                            </span>
                                        @endif
                                        @if($user->website)
                                            <a href="{{ $user->website }}" target="_blank" class="flex items-center hover:text-indigo-600 transition-colors">
                                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z" clip-rule="evenodd" />
                                                </svg>
                                                {{ str_replace(['http://', 'https://'], '', $user->website) }}
                                            </a>
                                        @endif
                                        <span class="flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                            </svg>
                                            Joined {{ $user->created_at->format('M Y') }}
                                        </span>
                                    </div>
                                </div>

                                <div class="mt-6 lg:mt-0 flex space-x-3">
                                    <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-xl shadow-sm text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit Profile
                                    </a>
                                    <button class="inline-flex items-center px-6 py-3 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z" />
                                        </svg>
                                        Share Profile
                                    </button>
                                </div>
                            </div>

                            <!-- Stats -->
                            <div class="mt-8 grid grid-cols-2 lg:grid-cols-4 gap-6">
                                <div class="text-center p-4 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border border-blue-100">
                                    <div class="text-3xl font-bold text-blue-600">{{ $stats['posts_created'] }}</div>
                                    <div class="text-sm font-medium text-blue-700 mt-1">Posts</div>
                                </div>
                                <div class="text-center p-4 bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl border border-purple-100">
                                    <div class="text-3xl font-bold text-purple-600">{{ $stats['followers'] }}</div>
                                    <div class="text-sm font-medium text-purple-700 mt-1">Followers</div>
                                </div>
                                <div class="text-center p-4 bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl border border-green-100">
                                    <div class="text-3xl font-bold text-green-600">{{ $stats['following'] }}</div>
                                    <div class="text-sm font-medium text-green-700 mt-1">Following</div>
                                </div>
                                <div class="text-center p-4 bg-gradient-to-br from-orange-50 to-red-50 rounded-xl border border-orange-100">
                                    <div class="text-3xl font-bold text-orange-600">{{ $stats['wardrobe_items'] }}</div>
                                    <div class="text-sm font-medium text-orange-700 mt-1">Wardrobe</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Tabs -->
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 mt-8 pb-12">
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100">
                <!-- Tab Navigation -->
                <div class="border-b border-gray-200" x-data="{ activeTab: 'posts' }">
                    <nav class="-mb-px flex space-x-8 px-8 pt-6">
                        <button @click="activeTab = 'posts'" 
                                :class="activeTab === 'posts' ? 'border-indigo-500 text-indigo-600 bg-indigo-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="whitespace-nowrap py-3 px-4 border-b-2 font-semibold text-sm rounded-t-lg transition-all">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Posts
                        </button>
                        <button @click="activeTab = 'wardrobe'" 
                                :class="activeTab === 'wardrobe' ? 'border-indigo-500 text-indigo-600 bg-indigo-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="whitespace-nowrap py-3 px-4 border-b-2 font-semibold text-sm rounded-t-lg transition-all">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2h4a1 1 0 110 2h-1v12a2 2 0 01-2 2H5a2 2 0 01-2-2V6H2a1 1 0 110-2h4zM8 6v10h8V6H8z" />
                            </svg>
                            Wardrobe
                        </button>
                        <button @click="activeTab = 'outfits'" 
                                :class="activeTab === 'outfits' ? 'border-indigo-500 text-indigo-600 bg-indigo-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="whitespace-nowrap py-3 px-4 border-b-2 font-semibold text-sm rounded-t-lg transition-all">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            Outfits
                        </button>
                        <button @click="activeTab = 'activity'" 
                                :class="activeTab === 'activity' ? 'border-indigo-500 text-indigo-600 bg-indigo-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="whitespace-nowrap py-3 px-4 border-b-2 font-semibold text-sm rounded-t-lg transition-all">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                            Activity
                        </button>
                    </nav>

                    <!-- Tab Content -->
                    <div class="p-8">
                        <!-- Posts Tab -->
                        <div x-show="activeTab === 'posts'" class="space-y-6">
                            @if($recent_posts->count() > 0)
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                                    @foreach($recent_posts as $post)
                                        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                                            <img src="{{ Storage::url($post->post_image_url) }}" alt="{{ $post->caption }}" class="w-full h-48 object-cover">
                                            <div class="p-4">
                                                <p class="text-sm text-gray-600 line-clamp-2">{{ $post->caption ?: 'No caption' }}</p>
                                                <div class="mt-2 flex items-center justify-between text-xs text-gray-500">
                                                    <span>{{ $post->created_at->diffForHumans() }}</span>
                                                    <div class="flex items-center space-x-2">
                                                        <span>❤️ {{ $post->likes_count ?? 0 }}</span>
                                                        <span>💬 {{ $post->comments_count ?? 0 }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M34 40h10v-4a6 6 0 00-10.712-3.714M34 40H14m20 0v-4a9.971 9.971 0 00-.712-3.714M14 40H4v-4a6 6 0 0110.713-3.714M14 40v-4c0-1.313.253-2.566.713-3.714m0 0A10.003 10.003 0 0124 26c4.21 0 7.813 2.602 9.288 6.286M30 14a6 6 0 11-12 0 6 6 0 0112 0zm12 6a4 4 0 11-8 0 4 4 0 018 0zm-28 0a4 4 0 11-8 0 4 4 0 018 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No posts yet</h3>
                                    <p class="mt-1 text-sm text-gray-500">Start sharing your fashion moments!</p>
                                    <div class="mt-6">
                                        <a href="{{ route('posts.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                            Create Post
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Wardrobe Tab -->
                        <div x-show="activeTab === 'wardrobe'" class="space-y-6">
                            @if($recent_wardrobe->count() > 0)
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                                    @foreach($recent_wardrobe as $item)
                                        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                                            <img src="{{ $item->item_image_url ? Storage::url($item->item_image_url) : asset('images/placeholder.jpg') }}" 
                                                 alt="{{ $item->item_name }}" 
                                                 class="w-full h-32 object-cover">
                                            <div class="p-2">
                                                <p class="text-xs font-medium text-gray-900 truncate">{{ $item->item_name }}</p>
                                                <p class="text-xs text-gray-500 capitalize">{{ str_replace('_', ' ', is_object($item->clothing_type) ? $item->clothing_type->value : $item->clothing_type) }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="text-center">
                                    <a href="{{ route('wardrobe.index') }}" class="text-indigo-600 hover:text-indigo-500 text-sm font-medium">
                                        View all wardrobe items →
                                    </a>
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2h4a1 1 0 110 2h-1v12a2 2 0 01-2 2H5a2 2 0 01-2-2V6H2a1 1 0 110-2h4zM8 6v10h8V6H8z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No wardrobe items</h3>
                                    <p class="mt-1 text-sm text-gray-500">Start building your digital wardrobe!</p>
                                    <div class="mt-6">
                                        <a href="{{ route('wardrobe.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                            Add Item
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Outfits Tab -->
                        <div x-show="activeTab === 'outfits'" class="space-y-6">
                            @if($recent_outfits->count() > 0)
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                                    @foreach($recent_outfits as $outfit)
                                        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                                            <div class="grid grid-cols-2 grid-rows-2 gap-1 h-48 bg-gray-50">
                                                @php
                                                    $outfitItems = collect($outfit->items ?? [])->take(4);
                                                @endphp
                                                @foreach($outfitItems as $item)
                                                    <div class="bg-cover bg-center" style="background-image: url('{{ $item['image_url'] ?? 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgZmlsbD0iI2RkZCIvPjx0ZXh0IHg9IjEwMCIgeT0iMTAwIiBmb250LWZhbWlseT0iQXJpYWwiIGZvbnQtc2l6ZT0iMTQiIGZpbGw9IiM5OTkiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj5JdGVtPC90ZXh0Pjwvc3ZnPg==' }}')"></div>
                                                @endforeach
                                                @for($i = $outfitItems->count(); $i < 4; $i++)
                                                    <div class="bg-gray-200 flex items-center justify-center">
                                                        <span class="text-xs text-gray-400">Empty</span>
                                                    </div>
                                                @endfor
                                            </div>
                                            <div class="p-4">
                                                <h3 class="text-sm font-medium text-gray-900">{{ $outfit->name }}</h3>
                                                <p class="text-xs text-gray-500 mt-1">{{ $outfit->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="text-center">
                                    <a href="{{ route('outfits.index') }}" class="text-indigo-600 hover:text-indigo-500 text-sm font-medium">
                                        View all outfits →
                                    </a>
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No outfits created</h3>
                                    <p class="mt-1 text-sm text-gray-500">Create your first outfit combination!</p>
                                    <div class="mt-6">
                                        <a href="{{ route('outfits.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                            Create Outfit
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Activity Tab -->
                        <div x-show="activeTab === 'activity'" class="space-y-6">
                            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
                                <div class="bg-gradient-to-r from-cyan-500 to-blue-600 rounded-lg p-5 text-white">
                                    <dt class="text-sm font-medium opacity-75">Total Orders</dt>
                                    <dd class="mt-1 text-3xl font-semibold">{{ $stats['orders_placed'] }}</dd>
                                </div>
                                <div class="bg-gradient-to-r from-purple-500 to-pink-600 rounded-lg p-5 text-white">
                                    <dt class="text-sm font-medium opacity-75">Wishlist Items</dt>
                                    <dd class="mt-1 text-3xl font-semibold">{{ $stats['wishlist_items'] }}</dd>
                                </div>
                                <div class="bg-gradient-to-r from-green-500 to-teal-600 rounded-lg p-5 text-white">
                                    <dt class="text-sm font-medium opacity-75">Affiliate Earnings</dt>
                                    <dd class="mt-1 text-3xl font-semibold">Rp {{ number_format($stats['affiliate_earnings'], 0) }}</dd>
                                </div>
                                <div class="bg-gradient-to-r from-orange-500 to-red-600 rounded-lg p-5 text-white">
                                    <dt class="text-sm font-medium opacity-75">Outfits Created</dt>
                                    <dd class="mt-1 text-3xl font-semibold">{{ $stats['outfits_created'] }}</dd>
                                </div>
                            </div>

                            <!-- Quick Actions -->
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                    <a href="{{ route('posts.create') }}" class="flex items-center p-4 bg-white rounded-lg border border-gray-300 hover:border-indigo-500 hover:shadow-md transition-all">
                                        <div class="flex-shrink-0">
                                            <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <p class="text-sm font-medium text-gray-900">Create Post</p>
                                            <p class="text-sm text-gray-500">Share your style</p>
                                        </div>
                                    </a>

                                    <a href="{{ route('wardrobe.create') }}" class="flex items-center p-4 bg-white rounded-lg border border-gray-300 hover:border-indigo-500 hover:shadow-md transition-all">
                                        <div class="flex-shrink-0">
                                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2h4a1 1 0 110 2h-1v12a2 2 0 01-2 2H5a2 2 0 01-2-2V6H2a1 1 0 110-2h4zM8 6v10h8V6H8z" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <p class="text-sm font-medium text-gray-900">Add to Wardrobe</p>
                                            <p class="text-sm text-gray-500">Digitize your clothes</p>
                                        </div>
                                    </a>

                                    <a href="{{ route('outfits.create') }}" class="flex items-center p-4 bg-white rounded-lg border border-gray-300 hover:border-indigo-500 hover:shadow-md transition-all">
                                        <div class="flex-shrink-0">
                                            <div class="w-10 h-10 bg-pink-100 rounded-lg flex items-center justify-center">
                                                <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <p class="text-sm font-medium text-gray-900">Create Outfit</p>
                                            <p class="text-sm text-gray-500">Mix and match</p>
                                        </div>
                                    </a>

                                    <a href="{{ route('products.index') }}" class="flex items-center p-4 bg-white rounded-lg border border-gray-300 hover:border-indigo-500 hover:shadow-md transition-all">
                                        <div class="flex-shrink-0">
                                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <p class="text-sm font-medium text-gray-900">Browse Shop</p>
                                            <p class="text-sm text-gray-500">Discover products</p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>