<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Metria') }} - @yield('title', 'Sustainable Fashion Social Commerce')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Additional head content -->
    @stack('head')
</head>
<body class="font-sans bg-neutral-50 text-neutral-900 antialiased">
    <div class="min-h-screen">
        <!-- Navigation -->
        @auth
            <nav class="bg-white shadow-sm border-b border-neutral-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <!-- Logo -->
                            <div class="flex-shrink-0">
                                <a href="{{ route('dashboard') }}" class="flex items-center">
                                    <div class="w-8 h-8 bg-gradient-primary rounded-lg flex items-center justify-center">
                                        <span class="text-white font-bold text-lg">M</span>
                                    </div>
                                    <span class="ml-2 text-xl font-display font-bold text-gradient">Metria</span>
                                </a>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                <a href="{{ route('dashboard') }}" class="text-neutral-600 hover:text-neutral-900 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 {{ request()->routeIs('dashboard') ? 'text-primary-600 bg-primary-50' : '' }}">
                                    Dashboard
                                </a>
                                <a href="{{ route('feed') }}" class="text-neutral-600 hover:text-neutral-900 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 {{ request()->routeIs('feed') ? 'text-primary-600 bg-primary-50' : '' }}">
                                    Feed
                                </a>
                                <a href="{{ route('wardrobe.index') }}" class="text-neutral-600 hover:text-neutral-900 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 {{ request()->routeIs('wardrobe.*') ? 'text-primary-600 bg-primary-50' : '' }}">
                                    Wardrobe
                                </a>
                                <a href="{{ route('outfits.index') }}" class="text-neutral-600 hover:text-neutral-900 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 {{ request()->routeIs('outfits.*') ? 'text-primary-600 bg-primary-50' : '' }}">
                                    Outfits
                                </a>
                                <a href="{{ route('products.index') }}" class="text-neutral-600 hover:text-neutral-900 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 {{ request()->routeIs('products.*') ? 'text-primary-600 bg-primary-50' : '' }}">
                                    Shop
                                </a>
                            </div>
                        </div>

                        <!-- Right Side -->
                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <!-- Search -->
                            <div class="relative mr-4">
                                <input type="text" placeholder="Search..." class="block w-full px-3 py-2 border border-neutral-300 rounded-lg shadow-sm placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200 pl-10 pr-4 py-2 w-64">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                            </div>

                            <!-- Notifications -->
                            <button class="text-neutral-600 hover:text-neutral-900 hover:bg-neutral-100 focus:ring-neutral-500 p-2 mr-2">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5-5-5 5h5zm0 0v-1.5a2.5 2.5 0 00-5 0V17h5z"></path>
                                </svg>
                            </button>

                            <!-- User Dropdown -->
                            <div class="relative ml-3" x-data="{ open: false }">
                                <button @click="open = !open" class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                    <div class="inline-block rounded-full overflow-hidden w-10 h-10">
                                        @if(auth()->user()->profile_picture_url)
                                            <img src="{{ auth()->user()->profile_picture_url }}" alt="{{ auth()->user()->full_name }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full bg-primary-500 flex items-center justify-center text-white font-medium">
                                                {{ substr(auth()->user()->full_name, 0, 1) }}
                                            </div>
                                        @endif
                                    </div>
                                </button>

                                <div x-show="open" @click.away="open = false" x-transition class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                                    <div class="py-1">
                                        <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-100">Profile</a>
                                        <a href="{{ route('affiliate.dashboard') }}" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-100">Affiliate</a>
                                        <a href="{{ route('orders.index') }}" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-100">Orders</a>
                                        <div class="border-t border-neutral-100"></div>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-100">
                                                Logout
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Mobile menu button -->
                        <div class="sm:hidden flex items-center">
                            <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-neutral-600 hover:text-neutral-900 hover:bg-neutral-100 focus:ring-neutral-500 p-2">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </nav>
        @endauth

        <!-- Page Content -->
        <main>
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-neutral-200 mt-auto">
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div class="col-span-1 md:col-span-2">
                        <div class="flex items-center mb-4">
                            <div class="w-8 h-8 bg-gradient-primary rounded-lg flex items-center justify-center">
                                <span class="text-white font-bold text-lg">M</span>
                            </div>
                            <span class="ml-2 text-xl font-display font-bold text-gradient">Metria</span>
                        </div>
                        <p class="text-neutral-600 text-sm">
                            Sustainable fashion social commerce platform that empowers conscious consumers and supports local businesses.
                        </p>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-neutral-900 tracking-wider uppercase mb-4">Features</h3>
                        <ul class="space-y-2">
                            <li><a href="#" class="text-sm text-neutral-600 hover:text-neutral-900">Digital Wardrobe</a></li>
                            <li><a href="#" class="text-sm text-neutral-600 hover:text-neutral-900">Outfit Constructor</a></li>
                            <li><a href="#" class="text-sm text-neutral-600 hover:text-neutral-900">Size Recommendation</a></li>
                            <li><a href="#" class="text-sm text-neutral-600 hover:text-neutral-900">Affiliate Program</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-neutral-900 tracking-wider uppercase mb-4">Support</h3>
                        <ul class="space-y-2">
                            <li><a href="#" class="text-sm text-neutral-600 hover:text-neutral-900">Help Center</a></li>
                            <li><a href="#" class="text-sm text-neutral-600 hover:text-neutral-900">Contact Us</a></li>
                            <li><a href="#" class="text-sm text-neutral-600 hover:text-neutral-900">Privacy Policy</a></li>
                            <li><a href="#" class="text-sm text-neutral-600 hover:text-neutral-900">Terms of Service</a></li>
                        </ul>
                    </div>
                </div>
                <div class="mt-8 pt-8 border-t border-neutral-200">
                    <p class="text-center text-sm text-neutral-500">
                        © {{ date('Y') }} Metria. All rights reserved.
                    </p>
                </div>
            </div>
        </footer>
    </div>

    <!-- Scripts -->
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('scripts')
</body>
</html> 