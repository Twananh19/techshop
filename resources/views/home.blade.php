<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TechShop - Trang chủ</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-50">
    <!-- Header / Navigation -->
    <header class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="{{ route('home') }}" class="text-2xl font-bold text-white drop-shadow-lg" style="color: #000000ff;">
                        TechShop
                    </a>
                </div>

                <!-- Search Bar (Desktop) -->
                <div class="hidden md:flex flex-1 max-w-2xl mx-8">
                    <form class="w-full">
                        <div class="relative">
                            <input type="text" 
                                   placeholder="Tìm kiếm sản phẩm..."
                                   class="w-full px-4 py-2 pr-10 border-0 rounded-lg shadow-md focus:ring-2 focus:ring-white focus:ring-opacity-50 bg-white bg-opacity-90 backdrop-blur">
                            <button type="submit" class="absolute right-2 top-1/2 transform -translate-y-1/2 text-purple-600 hover:text-purple-800">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Navigation Links -->
                <nav class="flex items-center space-x-4">
                    @auth
                        <!-- Cart -->
                        <a href="{{ route('cart.index') }}" class="p-2 text-gray-900 hover:text-gray-700 relative bg-white rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            @php
                                $cartCount = 0;
                                if (Auth::check()) {
                                    $cart = \App\Models\Cart::where('user_id', Auth::id())->first();
                                    $cartCount = $cart ? $cart->items()->sum('quantity') : 0;
                                } else {
                                    $cart = \App\Models\Cart::where('session_id', Session::getId())->first();
                                    $cartCount = $cart ? $cart->items()->sum('quantity') : 0;
                                }
                            @endphp
                            @if($cartCount > 0)
                                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold">{{ $cartCount }}</span>
                            @endif
                        </a>

                        <!-- User Dropdown -->
                        <div class="relative" x-data="{ open: false }" @click.away="open = false">
                            <button @click="open = !open" class="flex items-center space-x-2 px-3 py-2 rounded-lg hover:bg-white hover:bg-opacity-20 transition">
                                @if(auth()->user()->avatar)
                                    <img src="{{ auth()->user()->avatar }}" alt="Avatar" class="w-8 h-8 rounded-full border-2 border-white">
                                @else
                                    <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-purple-600 font-bold text-sm border-2 border-white">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </div>
                                @endif
                                <span class="text-sm font-medium text-black">{{ auth()->user()->name }}</span>
                                <svg class="w-4 h-4 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 z-50"
                                 style="display: none;">
                                @if(auth()->user()->role === 'admin')
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        Quản trị
                                    </a>
                                @endif
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    Tài khoản
                                </a>
                                <a href="{{ route('customer.orders.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                    </svg>
                                    Đơn hàng
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        Đăng xuất
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg shadow-md hover:shadow-lg transition" style="background-color: #159604ff;">
                            Đăng nhập
                        </a>
                        <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-md hover:shadow-lg transition font-semibold">
                            Đăng ký
                        </a>
                    @endauth
                </nav>
            </div>
        </div>
    </header>

    <!-- Slider Section -->
    @if($sliders->count() > 0)
    <section class="relative overflow-hidden bg-gray-900" x-data="slider()">
        <div class="relative h-[500px] md:h-[600px]">
            <!-- Slides -->
            @foreach($sliders as $index => $slider)
            <div x-show="currentSlide === {{ $index }}"
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0 transform translate-x-full"
                 x-transition:enter-end="opacity-100 transform translate-x-0"
                 x-transition:leave="transition ease-in duration-500"
                 x-transition:leave-start="opacity-100 transform translate-x-0"
                 x-transition:leave-end="opacity-0 transform -translate-x-full"
                 class="absolute inset-0"
                 style="display: none;">
                
                @if($slider->link)
                <a href="{{ $slider->link }}" class="block h-full">
                @endif
                    <div class="relative h-full bg-cover bg-center" style="background-image: url('{{ $slider->image }}');">
                        <div class="absolute inset-0 bg-gradient-to-r from-black/70 to-black/30"></div>
                        <div class="relative h-full flex items-center">
                            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                                <div class="max-w-2xl text-white">
                                    <h1 class="text-4xl md:text-6xl font-bold mb-4 animate-fade-in">
                                        {{ $slider->title }}
                                    </h1>
                                    @if($slider->subtitle)
                                    <p class="text-lg md:text-2xl mb-8 text-gray-200">
                                        {{ $slider->subtitle }}
                                    </p>
                                    @endif
                                    @if($slider->link)
                                    <button class="px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg font-semibold hover:shadow-2xl transition transform hover:-translate-y-1">
                                        Khám phá ngay
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @if($slider->link)
                </a>
                @endif
            </div>
            @endforeach

            <!-- Navigation Arrows -->
            @if($sliders->count() > 1)
            <button @click="prevSlide()" 
                    class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/30 hover:bg-white/50 backdrop-blur-sm text-white p-3 rounded-full transition z-10">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>
            <button @click="nextSlide()" 
                    class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/30 hover:bg-white/50 backdrop-blur-sm text-white p-3 rounded-full transition z-10">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>

            <!-- Dots Indicator -->
            <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex space-x-2 z-10">
                @foreach($sliders as $index => $slider)
                <button @click="currentSlide = {{ $index }}"
                        :class="currentSlide === {{ $index }} ? 'bg-white w-8' : 'bg-white/50 w-3'"
                        class="h-3 rounded-full transition-all duration-300"></button>
                @endforeach
            </div>
            @endif
        </div>
    </section>
    @endif

    <!-- Categories -->
    <section id="categories" class="py-16 bg-white" x-data="{ selectedCategory: '{{ $selectedCategory ?? 'all' }}' }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Danh mục sản phẩm</h2>
            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                <!-- All Products Category -->
                <a href="{{ route('home', ['category' => 'all']) }}" 
                   @click="selectedCategory = 'all'"
                   :class="selectedCategory === 'all' ? 'ring-4 ring-purple-500 scale-105' : ''"
                   class="group cursor-pointer">
                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-6 text-center hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                        <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-r from-purple-500 to-pink-600 rounded-full flex items-center justify-center text-white shadow-lg">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 group-hover:text-purple-600 transition">
                            Tất cả
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">
                            {{ $categories->sum('inventory_items_count') }} sản phẩm
                        </p>
                    </div>
                </a>

                @foreach($categories as $index => $category)
                    @php
                        // Map categories to icons
                        $categoryIcons = [
                            'Tai Nghe' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />',
                            'Màn hình' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />',
                            'Sạc pin' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />',
                            'Bàn phím' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />',
                            'Chuột' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />',
                        ];
                        
                        // Gradient colors for each category
                        $gradients = [
                            'from-blue-500 to-cyan-600',
                            'from-green-500 to-emerald-600',
                            'from-yellow-500 to-orange-600',
                            'from-red-500 to-pink-600',
                            'from-indigo-500 to-purple-600',
                        ];
                        
                        $bgGradients = [
                            'from-blue-50 to-cyan-50',
                            'from-green-50 to-emerald-50',
                            'from-yellow-50 to-orange-50',
                            'from-red-50 to-pink-50',
                            'from-indigo-50 to-purple-50',
                        ];
                        
                        $iconPath = $categoryIcons[$category->name] ?? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />';
                        $gradient = $gradients[$index % count($gradients)];
                        $bgGradient = $bgGradients[$index % count($bgGradients)];
                    @endphp
                    
                    <a href="{{ route('home', ['category' => $category->id]) }}" 
                       @click="selectedCategory = '{{ $category->id }}'"
                       :class="selectedCategory === '{{ $category->id }}' ? 'ring-4 ring-blue-500 scale-105' : ''"
                       class="group cursor-pointer">
                        <div class="bg-gradient-to-br {{ $bgGradient }} rounded-xl p-6 text-center hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                            <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-r {{ $gradient }} rounded-full flex items-center justify-center text-white shadow-lg">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    {!! $iconPath !!}
                                </svg>
                            </div>
                            <h3 class="font-semibold text-gray-900 group-hover:text-blue-600 transition">
                                {{ $category->name }}
                            </h3>
                            <p class="text-sm text-gray-500 mt-1">
                                {{ $category->inventory_items_count }} sản phẩm
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Filtered Products by Category -->
    @if($selectedCategory && $selectedCategory !== 'all')
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @php
                $currentCategory = $categories->firstWhere('id', $selectedCategory);
            @endphp
            
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900 mb-2">
                            Sản phẩm {{ $currentCategory ? $currentCategory->name : '' }}
                        </h2>
                        <p class="text-gray-600">Tìm thấy {{ $allProducts->count() }} sản phẩm</p>
                    </div>
                    <a href="{{ route('home') }}" class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-medium transition">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Xóa bộ lọc
                    </a>
                </div>
            </div>

            @if($allProducts->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($allProducts as $product)
                    <a href="{{ route('products.show', $product->id) }}" class="block bg-white rounded-xl shadow-md overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 group">
                        <!-- Product Image -->
                        <div class="relative h-48 bg-gray-100 overflow-hidden">
                            @if($product->image)
                                <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                    <svg class="w-20 h-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                            
                            @if($product->inventoryItem && $product->inventoryItem->stock_quantity < 10)
                                <div class="absolute top-2 left-2">
                                    <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                                        Sắp hết
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Product Info -->
                        <div class="p-4">
                            <p class="text-xs text-gray-500 mb-1">{{ $product->inventoryItem->category->name }}</p>
                            <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2 group-hover:text-purple-600 transition">
                                {{ $product->name }}
                            </h3>
                            
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-lg font-bold text-red-600">
                                        {{ number_format($product->price, 0, ',', '.') }}₫
                                    </p>
                                </div>
                                
                                <div class="p-2 bg-purple-600 text-white rounded-lg group-hover:bg-purple-700 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            @else
            <div class="text-center py-12">
                <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
                <h3 class="mt-4 text-xl font-medium text-gray-900">Không có sản phẩm</h3>
                <p class="mt-2 text-gray-500">Danh mục này chưa có sản phẩm nào</p>
            </div>
            @endif
        </div>
    </section>
    @endif

    <!-- Featured Products -->
    @if(!$selectedCategory || $selectedCategory === 'all')
    <section id="featured" class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900">Sản phẩm nổi bật</h2>
                <a href="#" class="text-blue-600 hover:text-blue-700 font-medium">Xem tất cả →</a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($featuredProducts as $product)
                    <a href="{{ route('products.show', $product->id) }}" class="block bg-white rounded-xl shadow-md overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 group">
                        <!-- Product Image -->
                        <div class="relative h-48 bg-gray-100 overflow-hidden">
                            @if($product->image)
                                <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                    <svg class="w-20 h-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                            
                            <!-- Featured Badge -->
                            <div class="absolute top-2 right-2">
                                <span class="bg-gradient-to-r from-yellow-400 to-orange-500 text-white text-xs font-bold px-2 py-1 rounded-full flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    HOT
                                </span>
                            </div>
                        </div>

                        <!-- Product Info -->
                        <div class="p-4">
                            <p class="text-xs text-gray-500 mb-1">{{ $product->inventoryItem->category->name }}</p>
                            <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2 group-hover:text-purple-600 transition">
                                {{ $product->name }}
                            </h3>
                            
                            <div class="flex items-center justify-between">
                                <div>
                                    @if($product->discount_price)
                                        <p class="text-lg font-bold text-red-600">
                                            {{ number_format($product->discount_price, 0, ',', '.') }}₫
                                        </p>
                                        <p class="text-sm text-gray-400 line-through">
                                            {{ number_format($product->price, 0, ',', '.') }}₫
                                        </p>
                                    @else
                                        <p class="text-lg font-bold text-red-600">
                                            {{ number_format($product->price, 0, ',', '.') }}₫
                                        </p>
                                    @endif
                                </div>
                                
                                <div class="p-2 bg-purple-600 text-white rounded-lg group-hover:bg-purple-700 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Latest Products -->
    @if(!$selectedCategory || $selectedCategory === 'all')
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900">Sản phẩm mới nhất</h2>
                <a href="#" class="text-blue-600 hover:text-blue-700 font-medium">Xem tất cả →</a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($latestProducts->take(8) as $product)
                    <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                        <div class="relative h-48 bg-gray-100">
                            @php
                                $mainImage = $product->images->where('is_main', true)->first() ?? $product->images->first();
                            @endphp
                            @if($mainImage)
                                <img src="{{ $mainImage->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300">
                                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                            
                            <div class="absolute top-2 right-2">
                                <span class="bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-full">Mới</span>
                            </div>
                        </div>

                        <div class="p-4">
                            <p class="text-xs text-gray-500 mb-1">{{ $product->inventoryItem->category->name }}</p>
                            <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">
                                {{ $product->name }}
                            </h3>
                            
                            <div class="flex items-center justify-between mt-3">
                                <div>
                                    @if($product->discount_price)
                                        <p class="text-lg font-bold text-red-600">
                                            {{ number_format($product->discount_price) }}đ
                                        </p>
                                        <p class="text-sm text-gray-400 line-through">
                                            {{ number_format($product->price) }}đ
                                        </p>
                                    @else
                                        <p class="text-lg font-bold text-gray-900">
                                            {{ number_format($product->price) }}đ
                                        </p>
                                    @endif
                                </div>
                                
                                <button class="p-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-2xl font-bold mb-4 text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-500">TechShop</h3>
                    <p class="text-gray-400 text-sm">
                        Cung cấp các sản phẩm điện tử chất lượng cao với giá tốt nhất.
                    </p>
                </div>
                
                <div>
                    <h4 class="font-semibold mb-4">Về chúng tôi</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="#" class="hover:text-white">Giới thiệu</a></li>
                        <li><a href="#" class="hover:text-white">Liên hệ</a></li>
                        <li><a href="#" class="hover:text-white">Tuyển dụng</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-semibold mb-4">Hỗ trợ</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="#" class="hover:text-white">Chính sách bảo hành</a></li>
                        <li><a href="#" class="hover:text-white">Chính sách đổi trả</a></li>
                        <li><a href="#" class="hover:text-white">Hướng dẫn mua hàng</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-semibold mb-4">Theo dõi chúng tôi</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center hover:bg-blue-700">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-pink-600 rounded-full flex items-center justify-center hover:bg-pink-700">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073z"/></svg>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm text-gray-400">
                <p>&copy; 2025 TechShop. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        function slider() {
            return {
                currentSlide: 0,
                totalSlides: {{ $sliders->count() }},
                
                init() {
                    // Auto-play slider every 5 seconds
                    setInterval(() => {
                        this.nextSlide();
                    }, 5000);
                },
                
                nextSlide() {
                    this.currentSlide = (this.currentSlide + 1) % this.totalSlides;
                },
                
                prevSlide() {
                    this.currentSlide = (this.currentSlide - 1 + this.totalSlides) % this.totalSlides;
                }
            }
        }
    </script>
</body>
</html>
