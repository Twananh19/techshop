<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đơn hàng của tôi - TechShop</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 text-white shadow-lg">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <a href="{{ route('home') }}" class="text-2xl font-bold">TechShop</a>
                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ route('customer.orders.index') }}" class="hover:text-gray-200">Đơn hàng của tôi</a>
                        <span>Xin chào, {{ Auth::user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded-lg transition">Đăng xuất</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="bg-green-500 hover:bg-green-600 px-4 py-2 rounded-lg transition">Đăng nhập</a>
                        <a href="{{ route('register') }}" class="bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded-lg transition">Đăng ký</a>
                    @endauth
                    <a href="{{ route('cart.index') }}" class="relative bg-black hover:bg-gray-800 px-4 py-2 rounded-lg transition">
                        <svg class="w-6 h-6 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <div class="container mx-auto px-4 py-8">
        <div class="mb-6">
            <h1 class="text-3xl font-bold mb-2">Đơn hàng của tôi</h1>
            <nav class="text-sm breadcrumbs">
                <ul class="flex space-x-2 text-gray-600">
                    <li><a href="{{ route('home') }}" class="hover:text-indigo-600">Trang chủ</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li class="text-indigo-600 font-semibold">Đơn hàng của tôi</li>
                </ul>
            </nav>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                {{ session('error') }}
            </div>
        @endif

        @if($orders->count() > 0)
            <div class="space-y-4">
                @foreach($orders as $order)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="bg-gray-50 px-6 py-4 border-b flex justify-between items-center">
                            <div>
                                <p class="text-sm text-gray-600">Mã đơn hàng: <span class="font-semibold text-gray-900">{{ $order->order_number }}</span></p>
                                <p class="text-sm text-gray-600">Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div>
                                <span class="px-3 py-1 text-sm font-semibold rounded-full 
                                    @if($order->status === 'completed') bg-green-100 text-green-800
                                    @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                    @elseif($order->status === 'shipped') bg-blue-100 text-blue-800
                                    @elseif($order->status === 'confirmed') bg-purple-100 text-purple-800
                                    @else bg-yellow-100 text-yellow-800
                                    @endif">
                                    @if($order->status === 'pending') Chờ xử lý
                                    @elseif($order->status === 'confirmed') Đã xác nhận
                                    @elseif($order->status === 'shipped') Đang giao hàng
                                    @elseif($order->status === 'completed') Hoàn thành
                                    @else Đã hủy
                                    @endif
                                </span>
                            </div>
                        </div>
                        
                        <div class="p-6">
                            <div class="space-y-4">
                                @foreach($order->items as $item)
                                    <div class="flex items-center space-x-4 pb-4 border-b last:border-b-0">
                                        @if($item->product->image)
                                            <img src="{{ $item->product->image }}" alt="{{ $item->product->name }}" class="w-20 h-20 object-cover rounded">
                                        @else
                                            <div class="w-20 h-20 bg-gray-200 rounded flex items-center justify-center">
                                                <span class="text-gray-400 text-xs">No image</span>
                                            </div>
                                        @endif
                                        <div class="flex-1">
                                            <h3 class="font-semibold">{{ $item->product->name }}</h3>
                                            <p class="text-sm text-gray-600">Số lượng: {{ $item->quantity }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-semibold">{{ number_format($item->price) }}₫</p>
                                            <p class="text-sm text-gray-600">Tổng: {{ number_format($item->subtotal) }}₫</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-6 flex justify-between items-center">
                                <div>
                                    <p class="text-lg font-bold">Tổng tiền: <span class="text-indigo-600">{{ number_format($order->total_amount) }}₫</span></p>
                                    <p class="text-sm text-gray-600">
                                        Thanh toán: 
                                        @if($order->payment_method == 'cod') COD
                                        @elseif($order->payment_method == 'bank_transfer') Chuyển khoản
                                        @elseif($order->payment_method == 'momo') MoMo
                                        @elseif($order->payment_method == 'zalopay') ZaloPay
                                        @endif
                                    </p>
                                </div>
                                <div class="flex space-x-2">
                                    <a href="{{ route('customer.orders.show', $order->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition">
                                        Xem chi tiết
                                    </a>
                                    @if($order->status === 'pending')
                                        <form action="{{ route('customer.orders.cancel', $order->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition" onclick="return confirm('Bạn có chắc muốn hủy đơn hàng này?')">
                                                Hủy đơn
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $orders->links() }}
            </div>
        @else
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Chưa có đơn hàng nào</h3>
                <p class="text-gray-600 mb-4">Bạn chưa có đơn hàng nào. Hãy mua sắm ngay!</p>
                <a href="{{ route('home') }}" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-semibold transition">
                    Tiếp tục mua sắm
                </a>
            </div>
        @endif
    </div>
</body>
</html>
