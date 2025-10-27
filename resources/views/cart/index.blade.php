<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Giỏ hàng - TechShop</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-50">
    <!-- Header -->
    <header class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex-shrink-0">
                    <a href="{{ route('home') }}" class="text-2xl font-bold text-white drop-shadow-lg" style="color: #000000ff;">
                        TechShop
                    </a>
                </div>
                <nav class="flex items-center space-x-4">
                    @auth
                        <a href="{{ route('cart.index') }}" class="p-2 text-gray-900 hover:text-gray-700 relative bg-white rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            @if($cartItems->sum('quantity') > 0)
                                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold">{{ $cartItems->sum('quantity') }}</span>
                            @endif
                        </a>
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
                            </button>
                            <div x-show="open" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 z-50" style="display: none;">
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
                <span class="text-gray-900">Giỏ hàng</span>
            </nav>
        </div>
    </div>

    <!-- Cart Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Giỏ hàng của bạn</h1>

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

        @if($cartItems->count() > 0)
            <div class="space-y-6">
                <!-- Cart Items -->
                <div class="space-y-4">
                    @foreach($cartItems as $item)
                    <div class="bg-white rounded-lg shadow-md p-6 flex gap-4">
                        <!-- Product Image -->
                        <a href="{{ route('products.show', $item->product->id) }}" class="flex-shrink-0">
                            <img src="{{ $item->product->image }}" 
                                 alt="{{ $item->product->name }}" 
                                 class="w-24 h-24 object-cover rounded-lg">
                        </a>

                        <!-- Product Info -->
                        <div class="flex-1">
                            <a href="{{ route('products.show', $item->product->id) }}" 
                               class="text-lg font-semibold text-gray-900 hover:text-purple-600 transition">
                                {{ $item->product->name }}
                            </a>
                            <div class="mt-2">
                                <p class="text-gray-600 text-sm">Đơn giá: {{ number_format($item->price, 0, ',', '.') }}₫</p>
                                <p class="text-red-600 font-bold text-xl mt-1">
                                    Thành tiền: {{ number_format($item->price * $item->quantity, 0, ',', '.') }}₫
                                </p>
                            </div>

                            <!-- Quantity Controls -->
                            <form action="{{ route('cart.update', $item->id) }}" method="POST" class="mt-4">
                                @csrf
                                @method('PATCH')
                                <div class="flex items-center gap-4">
                                    <label class="text-gray-600 text-sm">Số lượng:</label>
                                    <div class="flex items-center border border-gray-300 rounded-lg">
                                        <button type="button" 
                                                onclick="this.nextElementSibling.stepDown(); this.closest('form').submit();"
                                                class="px-3 py-1 text-gray-600 hover:bg-gray-100">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                            </svg>
                                        </button>
                                        <input type="number" 
                                               name="quantity" 
                                               value="{{ $item->quantity }}"
                                               min="1" 
                                               max="{{ $item->product->stock }}"
                                               class="w-16 text-center border-0 focus:ring-0 text-sm"
                                               onchange="this.form.submit()">
                                        <button type="button" 
                                                onclick="this.previousElementSibling.stepUp(); this.closest('form').submit();"
                                                class="px-3 py-1 text-gray-600 hover:bg-gray-100">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Remove Button -->
                        <div class="flex-shrink-0">
                            <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-600 hover:text-red-800 p-2"
                                        onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Order Summary at Bottom -->
                <div class="max-w-2xl ml-auto" style="width: 80%;">
                    <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl shadow-lg p-8 border-2 border-purple-200" style="width: 100%;">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" />
                            </svg>
                            Tổng đơn hàng
                        </h2>
                        
                        <div class="space-y-4 mb-6">
                            <div class="flex justify-between text-gray-700 text-lg">
                                <span>Tạm tính:</span>
                                <span class="font-semibold">{{ number_format($cartItems->sum(fn($item) => $item->price * $item->quantity), 0, ',', '.') }}₫</span>
                            </div>
                            <div class="flex justify-between text-gray-700 text-lg">
                                <span>Phí vận chuyển:</span>
                                <span class="font-semibold text-green-600">Miễn phí</span>
                            </div>
                            <div class="border-t-2 border-purple-300 pt-4">
                                <div class="flex justify-between text-2xl font-bold">
                                    <span class="text-gray-900">Tổng cộng:</span>
                                    <span class="text-red-600">{{ number_format($cartItems->sum(fn($item) => $item->price * $item->quantity), 0, ',', '.') }}₫</span>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <a href="{{ route('checkout.index') }}" class="block w-full bg-gradient-to-r from-purple-600 to-pink-600 text-white py-4 rounded-lg font-bold text-lg hover:shadow-2xl transition transform hover:-translate-y-1 text-center">
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                                Tiến hành thanh toán
                            </a>

                            <a href="{{ route('home') }}" class="block text-center text-purple-600 hover:text-purple-800 font-medium text-lg py-2">
                                <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Tiếp tục mua sắm
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Empty Cart -->
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Giỏ hàng trống</h2>
                <p class="text-gray-600 mb-6">Bạn chưa có sản phẩm nào trong giỏ hàng</p>
                <a href="{{ route('home') }}" 
                   class="inline-block bg-gradient-to-r from-purple-600 to-pink-600 text-white px-8 py-3 rounded-lg font-semibold hover:shadow-xl transition">
                    Mua sắm ngay
                </a>
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
</body>
</html>
