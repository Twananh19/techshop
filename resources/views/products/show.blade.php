<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $product->name }} - TechShop</title>
    
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
                    <a href="{{ route('home') }}" class="text-2xl font-bold text-white drop-shadow-lg" style="color: #000000ff;"    >
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
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                        <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg shadow-md hover:shadow-lg transition">
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

    <!-- Breadcrumb -->
    <div class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <nav class="flex text-sm">
                <a href="{{ route('home') }}" class="text-gray-500 hover:text-gray-700">Trang chủ</a>
                <span class="mx-2 text-gray-400">/</span>
                <a href="{{ route('products.category', $product->inventoryItem->category->slug) }}" class="text-gray-500 hover:text-gray-700">
                    {{ $product->inventoryItem->category->name }}
                </a>
                <span class="mx-2 text-gray-400">/</span>
                <span class="text-gray-900">{{ $product->name }}</span>
            </nav>
        </div>
    </div>

    <!-- Product Detail -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- Product Image -->
            <div class="p-8">
                <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden">
                    <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                </div>
            </div>

            <!-- Product Info -->
            <div class="p-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $product->name }}</h1>
                
                <!-- Category Badge -->
                <div class="mb-4">
                    <a href="{{ route('products.category', $product->inventoryItem->category->slug) }}" 
                       class="inline-block px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-sm font-medium hover:bg-purple-200 transition">
                        {{ $product->inventoryItem->category->name }}
                    </a>
                </div>

                <!-- Price -->
                <div class="mb-6">
                    <div class="flex items-baseline gap-3">
                        <span class="text-4xl font-bold text-red-600">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                        @if($product->discount_price)
                            <span class="text-xl text-gray-400 line-through">{{ number_format($product->discount_price, 0, ',', '.') }}₫</span>
                        @endif
                    </div>
                </div>

                <!-- Stock Status -->
                <div class="mb-6">
                    @if($product->stock > 0)
                        <span class="inline-flex items-center px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Còn {{ $product->stock }} sản phẩm
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-medium">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            Hết hàng
                        </span>
                    @endif
                </div>

                <!-- Description -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Mô tả sản phẩm</h3>
                    <p class="text-gray-600 leading-relaxed">{{ $product->description }}</p>
                </div>

                <!-- Add to Cart Form -->
                @if($product->stock > 0)
                    <form action="{{ route('cart.add', $product->id) }}" method="POST" x-data="{ quantity: 1 }">
                        @csrf
                        <div class="flex items-center gap-4 mb-6">
                            <label class="text-gray-700 font-medium">Số lượng:</label>
                            <div class="flex items-center border border-gray-300 rounded-lg">
                                <button type="button" 
                                        @click="quantity = Math.max(1, quantity - 1)"
                                        class="px-4 py-2 text-gray-600 hover:bg-gray-100 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                    </svg>
                                </button>
                                <input type="number" 
                                       name="quantity" 
                                       x-model="quantity"
                                       min="1" 
                                       max="{{ $product->stock }}"
                                       class="w-16 text-center border-0 focus:ring-0">
                                <button type="button" 
                                        @click="quantity = Math.min({{ $product->stock }}, quantity + 1)"
                                        class="px-4 py-2 text-gray-600 hover:bg-gray-100 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <button type="submit" 
                                class="w-full bg-gradient-to-r from-purple-600 to-pink-600 text-white px-8 py-4 rounded-lg font-semibold text-lg hover:shadow-xl transition transform hover:-translate-y-0.5">
                            <svg class="w-6 h-6 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Thêm vào giỏ hàng
                        </button>
                    </form>
                @else
                    <button disabled class="w-full bg-gray-300 text-gray-500 px-8 py-4 rounded-lg font-semibold text-lg cursor-not-allowed">
                        Hết hàng
                    </button>
                @endif

                <!-- Product Details -->
                <div class="mt-8 pt-8 border-t border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Thông tin chi tiết</h3>
                    <dl class="space-y-2">
                        <div class="flex">
                            <dt class="w-1/3 text-gray-600">Thương hiệu:</dt>
                            <dd class="w-2/3 font-medium">{{ $product->inventoryItem->brand }}</dd>
                        </div>
                        <div class="flex">
                            <dt class="w-1/3 text-gray-600">SKU:</dt>
                            <dd class="w-2/3 font-medium">{{ $product->inventoryItem->sku }}</dd>
                        </div>
                        <div class="flex">
                            <dt class="w-1/3 text-gray-600">Trạng thái:</dt>
                            <dd class="w-2/3">
                                <span class="inline-flex px-2 py-1 bg-green-100 text-green-800 rounded text-sm">
                                    {{ ucfirst($product->status) }}
                                </span>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if($relatedProducts->count() > 0)
        <div class="mt-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-8">Sản phẩm liên quan</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedProducts as $relatedProduct)
                <a href="{{ route('products.show', $relatedProduct->id) }}" class="group bg-white rounded-xl shadow-md hover:shadow-xl transition overflow-hidden">
                    <div class="aspect-square bg-gray-100 overflow-hidden">
                        <img src="{{ $relatedProduct->image }}" 
                             alt="{{ $relatedProduct->name }}" 
                             class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2 group-hover:text-purple-600 transition">
                            {{ $relatedProduct->name }}
                        </h3>
                        <p class="text-red-600 font-bold text-lg">
                            {{ number_format($relatedProduct->price, 0, ',', '.') }}₫
                        </p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="text-center">
                <h3 class="text-2xl font-bold mb-4">TechShop</h3>
                <p class="text-gray-400">Cửa hàng công nghệ hàng đầu Việt Nam</p>
                <p class="text-gray-500 mt-4">&copy; 2025 TechShop. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Alpine.js is already loaded via Vite
    </script>
</body>
</html>
