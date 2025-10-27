@extends('admin.layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900">
        Chi tiết Đơn hàng {{ $order->order_number }}
    </h1>
</div>

    
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Order Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Order Details -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-semibold mb-4">Thông tin đơn hàng</h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-600">Mã đơn hàng</p>
                                <p class="font-medium">{{ $order->order_number }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Ngày đặt</p>
                                <p class="font-medium">{{ $order->created_at->format('d/m/Y H:i:s') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Phương thức thanh toán</p>
                                <p class="font-medium">
                                    @if($order->payment_method == 'cod') Thanh toán khi nhận hàng
                                    @elseif($order->payment_method == 'bank_transfer') Chuyển khoản
                                    @elseif($order->payment_method == 'momo') MoMo
                                    @elseif($order->payment_method == 'zalopay') ZaloPay
                                    @endif
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Trạng thái</p>
                                <p>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                        @if($order->status === 'completed') bg-green-100 text-green-800
                                        @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                        @elseif($order->status === 'shipped') bg-blue-100 text-blue-800
                                        @elseif($order->status === 'confirmed') bg-purple-100 text-purple-800
                                        @else bg-yellow-100 text-yellow-800
                                        @endif">
                                        @if($order->status === 'pending') Chờ xử lý
                                        @elseif($order->status === 'confirmed') Đã xác nhận
                                        @elseif($order->status === 'shipped') Đang giao
                                        @elseif($order->status === 'completed') Hoàn thành
                                        @else Đã hủy
                                        @endif
                                    </span>
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Tổng tiền</p>
                                <p class="font-bold text-lg text-blue-600">{{ number_format($order->total_amount) }}đ</p>
                            </div>
                        </div>

                        <!-- Update Status -->
                        @if($order->status !== 'cancelled' && $order->status !== 'completed')
                        <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="mt-6">
                            @csrf
                            @method('PATCH')
                            <label class="block text-sm font-medium text-gray-700 mb-2">Cập nhật trạng thái</label>
                            <div class="flex gap-2">
                                <select name="status" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                    <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                                    <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Đang giao</option>
                                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                                    <option value="cancelled">Hủy đơn</option>
                                </select>
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" style="background-color: #3B82F6;">
                                    Cập nhật
                                </button>
                            </div>
                        </form>
                        @endif
                    </div>
                </div>

                <!-- Customer Info -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-semibold mb-4">Thông tin khách hàng</h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-600">Họ tên</p>
                                <p class="font-medium">{{ $order->customer_name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Số điện thoại</p>
                                <p class="font-medium">{{ $order->customer_phone }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Email</p>
                                <p class="font-medium">{{ $order->customer_email }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Địa chỉ giao hàng</p>
                                <p class="font-medium">
                                    {{ $order->shipping_address }}, 
                                    @if($order->shipping_district)
                                        {{ $order->shipping_district }}, 
                                    @endif
                                    {{ $order->shipping_city }}
                                </p>
                            </div>
                            @if($order->notes)
                            <div>
                                <p class="text-sm text-gray-600">Ghi chú</p>
                                <p class="font-medium">{{ $order->notes }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Sản phẩm ({{ $order->items->count() }})</h3>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sản phẩm</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Đơn giá</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Số lượng</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($order->items as $item)
                                <tr>
                                    <td class="px-6 py-4 text-sm">
                                        <div class="flex items-center">
                                            @if($item->product->image)
                                                <img src="{{ $item->product->image }}" alt="{{ $item->product->name }}" class="w-12 h-12 object-cover rounded mr-3">
                                            @endif
                                            <p class="font-medium">{{ $item->product->name }}</p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        {{ number_format($item->price) }}₫
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        {{ $item->quantity }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium">
                                        {{ number_format($item->subtotal) }}₫
                                    </td>
                                </tr>
                            @endforeach
                            <tr class="bg-gray-50">
                                <td colspan="2" class="px-6 py-3 text-sm text-gray-600">Tạm tính:</td>
                                <td colspan="2" class="px-6 py-3 text-sm font-medium">{{ number_format($order->subtotal) }}₫</td>
                            </tr>
                            <tr class="bg-gray-50">
                                <td colspan="2" class="px-6 py-3 text-sm text-gray-600">Phí vận chuyển:</td>
                                <td colspan="2" class="px-6 py-3 text-sm font-medium text-green-600">
                                    @if($order->shipping_fee == 0)
                                        Miễn phí
                                    @else
                                        {{ number_format($order->shipping_fee) }}₫
                                    @endif
                                </td>
                            </tr>
                            <tr class="bg-gray-100">
                                <td colspan="2" class="px-6 py-4 text-right font-bold text-lg">Tổng cộng:</td>
                                <td colspan="2" class="px-6 py-4 font-bold text-xl text-blue-600">
                                    {{ number_format($order->total_amount) }}₫
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6 flex gap-2">
                <a href="{{ route('admin.orders.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
                @if($order->status === 'cancelled')
                <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('Bạn có chắc muốn xóa đơn hàng này?')">
                        <i class="fas fa-trash"></i> Xóa đơn hàng
                    </button>
                </form>
                @endif
            </div>
@endsection
