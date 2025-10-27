@extends('admin.layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900">
            Chi tiết Người dùng: {{ $user->name }}
        </h1>
</div>

    
            <!-- User Info -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Thông tin cơ bản</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">ID</p>
                            <p class="font-medium">#{{ $user->id }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Tên</p>
                            <p class="font-medium">{{ $user->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Email</p>
                            <p class="font-medium">{{ $user->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Vai trò</p>
                            <p class="font-medium">
                                @if($user->role === 'admin')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">Admin</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Khách hàng</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Ngày tạo</p>
                            <p class="font-medium">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Cập nhật lần cuối</p>
                            <p class="font-medium">{{ $user->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Addresses -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Địa chỉ ({{ $user->addresses->count() }})</h3>
                    @if($user->addresses->count() > 0)
                        <div class="space-y-3">
                            @foreach($user->addresses as $address)
                                <div class="border rounded p-3">
                                    <p class="font-medium">{{ $address->full_name }} - {{ $address->phone }}</p>
                                    <p class="text-sm text-gray-600">{{ $address->address }}, {{ $address->ward }}, {{ $address->district }}, {{ $address->city }}</p>
                                    @if($address->is_default)
                                        <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">Mặc định</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">Chưa có địa chỉ</p>
                    @endif
                </div>
            </div>

            <!-- Orders -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Đơn hàng ({{ $user->orders->count() }})</h3>
                    @if($user->orders->count() > 0)
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Mã ĐH</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Ngày đặt</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tổng tiền</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Trạng thái</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($user->orders as $order)
                                    <tr>
                                        <td class="px-4 py-2 text-sm">#{{ $order->id }}</td>
                                        <td class="px-4 py-2 text-sm">{{ $order->created_at->format('d/m/Y') }}</td>
                                        <td class="px-4 py-2 text-sm">{{ number_format($order->total_amount) }}đ</td>
                                        <td class="px-4 py-2 text-sm">
                                            <span class="px-2 py-1 text-xs rounded-full 
                                                @if($order->status === 'completed') bg-green-100 text-green-800
                                                @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                                @elseif($order->status === 'shipped') bg-blue-100 text-blue-800
                                                @else bg-yellow-100 text-yellow-800
                                                @endif">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2 text-sm">
                                            <a href="{{ route('admin.orders.show', $order) }}" class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-eye"></i> Xem
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-gray-500">Chưa có đơn hàng</p>
                    @endif
                </div>
            </div>

            <!-- Cart -->
            @if($user->cart)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Giỏ hàng ({{ $user->cart->items->count() }} sản phẩm)</h3>
                    @if($user->cart->items->count() > 0)
                        <div class="space-y-2">
                            @foreach($user->cart->items as $item)
                                <div class="flex justify-between border-b py-2">
                                    <div>
                                        <p class="font-medium">{{ $item->product->name }}</p>
                                        <p class="text-sm text-gray-600">Số lượng: {{ $item->quantity }}</p>
                                    </div>
                                    <p class="font-medium">{{ number_format($item->product->price * $item->quantity) }}đ</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">Giỏ hàng trống</p>
                    @endif
                </div>
            </div>
            @endif

            <div class="mt-6">
                <a href="{{ route('admin.users.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
                <a href="{{ route('admin.users.edit', $user) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-2">
                    <i class="fas fa-edit"></i> Sửa
                </a>
            </div>
@endsection
