<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt hàng thành công - TechShop</title>
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

    <div class="container mx-auto px-4 py-12">
        <div class="max-w-3xl mx-auto">
            <!-- Success Icon -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full mb-4">
                    <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Đặt hàng thành công!</h1>
                <p class="text-gray-600">Cảm ơn bạn đã mua hàng tại TechShop</p>
            </div>

            <!-- Order Info -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="border-b pb-4 mb-4">
                    <h2 class="text-xl font-semibold mb-2">Thông tin đơn hàng</h2>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600">Mã đơn hàng:</span>
                            <span class="font-semibold ml-2">{{ $order->order_number }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Ngày đặt:</span>
                            <span class="font-semibold ml-2">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Trạng thái:</span>
                            <span class="ml-2 px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-xs font-semibold">
                                @if($order->status == 'pending')
                                    Chờ xử lý
                                @elseif($order->status == 'confirmed')
                                    Đã xác nhận
                                @elseif($order->status == 'shipped')
                                    Đang giao hàng
                                @elseif($order->status == 'completed')
                                    Hoàn thành
                                @elseif($order->status == 'cancelled')
                                    Đã hủy
                                @else
                                    {{ $order->status }}
                                @endif
                            </span>
                        </div>
                        <div>
                            <span class="text-gray-600">Thanh toán:</span>
                            <span class="font-semibold ml-2">
                                @if($order->payment_method == 'cod')
                                    COD
                                @elseif($order->payment_method == 'bank_transfer')
                                    Chuyển khoản
                                @elseif($order->payment_method == 'momo')
                                    MoMo
                                @elseif($order->payment_method == 'zalopay')
                                    ZaloPay
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Customer Info -->
                <div class="border-b pb-4 mb-4">
                    <h3 class="font-semibold mb-2">Thông tin khách hàng</h3>
                    <div class="text-sm space-y-1">
                        <p><span class="text-gray-600">Họ tên:</span> <span class="ml-2">{{ $order->customer_name }}</span></p>
                        <p><span class="text-gray-600">Số điện thoại:</span> <span class="ml-2">{{ $order->customer_phone }}</span></p>
                        <p><span class="text-gray-600">Email:</span> <span class="ml-2">{{ $order->customer_email }}</span></p>
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="border-b pb-4 mb-4">
                    <h3 class="font-semibold mb-2">Địa chỉ giao hàng</h3>
                    <p class="text-sm">
                        {{ $order->shipping_address }}, 
                        @if($order->shipping_district)
                            {{ $order->shipping_district }}, 
                        @endif
                        {{ $order->shipping_city }}
                    </p>
                </div>

                <!-- Order Items -->
                <div class="mb-4">
                    <h3 class="font-semibold mb-3">Sản phẩm đã đặt</h3>
                    <div class="space-y-3">
                        @foreach($order->items as $item)
                            <div class="flex items-center space-x-4 p-3 bg-gray-50 rounded">
                                @if($item->product->image)
                                    <img src="{{ $item->product->image }}" alt="{{ $item->product->name }}" class="w-16 h-16 object-cover rounded">
                                @else
                                    <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                                        <span class="text-gray-400 text-xs">No image</span>
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <div class="font-medium">{{ $item->product->name }}</div>
                                    <div class="text-sm text-gray-600">
                                        {{ number_format($item->price) }}₫ × {{ $item->quantity }}
                                    </div>
                                </div>
                                <div class="font-semibold">
                                    {{ number_format($item->subtotal) }}₫
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="bg-gray-50 p-4 rounded space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Tạm tính:</span>
                        <span class="font-semibold">{{ number_format($order->subtotal) }}₫</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Phí vận chuyển:</span>
                        <span class="font-semibold text-green-600">
                            @if($order->shipping_fee == 0)
                                Miễn phí
                            @else
                                {{ number_format($order->shipping_fee) }}₫
                            @endif
                        </span>
                    </div>
                    <div class="border-t pt-2 flex justify-between">
                        <span class="text-lg font-bold">Tổng cộng:</span>
                        <span class="text-lg font-bold text-indigo-600">{{ number_format($order->total_amount) }}₫</span>
                    </div>
                </div>

                @if($order->notes)
                    <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded">
                        <h4 class="font-semibold text-sm mb-1">Ghi chú:</h4>
                        <p class="text-sm text-gray-700">{{ $order->notes }}</p>
                    </div>
                @endif
            </div>

            <!-- Payment Instructions -->
            @if($order->payment_method != 'cod')
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                    <h3 class="font-semibold text-blue-900 mb-3">Hướng dẫn thanh toán</h3>
                    @if($order->payment_method == 'bank_transfer')
                        <div class="text-sm text-blue-800 space-y-2">
                            <p class="font-semibold">Vui lòng chuyển khoản đến:</p>
                            <p>Ngân hàng: <strong>Vietcombank</strong></p>
                            <p>Số tài khoản: <strong>1234567890</strong></p>
                            <p>Chủ tài khoản: <strong>CONG TY TECHSHOP</strong></p>
                            <p>Số tiền: <strong>{{ number_format($order->total_amount) }}₫</strong></p>
                            <p>Nội dung: <strong>{{ $order->order_number }}</strong></p>
                            <p class="text-red-600 mt-2">* Lưu ý: Vui lòng ghi đúng nội dung chuyển khoản để đơn hàng được xử lý nhanh chóng</p>
                        </div>
                    @elseif($order->payment_method == 'momo')
                        <p class="text-sm text-blue-800">Vui lòng mở ứng dụng MoMo và quét mã QR để thanh toán.</p>
                    @elseif($order->payment_method == 'zalopay')
                        <p class="text-sm text-blue-800">Vui lòng mở ứng dụng ZaloPay và quét mã QR để thanh toán.</p>
                    @endif
                </div>
            @endif

            <!-- Actions -->
            <div class="flex justify-center space-x-4">
                <a href="{{ route('home') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-semibold transition">
                    Tiếp tục mua sắm
                </a>
                @auth
                    <a href="{{ route('customer.orders.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-3 rounded-lg font-semibold transition">
                        Xem đơn hàng của tôi
                    </a>
                @endauth
            </div>
        </div>
    </div>
</body>
</html>
