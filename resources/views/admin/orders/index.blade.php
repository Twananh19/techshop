@extends('admin.layouts.app')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-3xl font-bold text-gray-900">
        Quản lý Đơn hàng
    </h1>
</div>

    
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Filters -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="GET" action="{{ route('admin.orders.index') }}" class="flex gap-4">
                        <div class="flex-1">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm theo mã ĐH, tên hoặc SĐT..." class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <select name="status" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Tất cả trạng thái</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                                <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Đang giao</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                            </select>
                        </div>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            <i class="fas fa-search"></i> Tìm kiếm
                        </button>
                        @if(request()->hasAny(['search', 'status']))
                            <a href="{{ route('admin.orders.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                <i class="fas fa-times"></i> Xóa bộ lọc
                            </a>
                        @endif
                    </form>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mã ĐH</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Khách hàng</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tổng tiền</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thanh toán</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày đặt</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($orders as $order)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $order->order_number }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $order->customer_name }}
                                        <br>
                                        <span class="text-xs text-gray-500">{{ $order->customer_phone }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ number_format($order->total_amount) }}đ
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
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
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($order->payment)
                                            <span class="px-2 py-1 text-xs rounded-full
                                                @if($order->payment->status === 'paid') bg-green-100 text-green-800
                                                @elseif($order->payment->status === 'failed') bg-red-100 text-red-800
                                                @else bg-yellow-100 text-yellow-800
                                                @endif">
                                                {{ strtoupper($order->payment->method) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $order->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                            <i class="fas fa-eye"></i> Xem
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                        Không tìm thấy đơn hàng
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
@endsection
