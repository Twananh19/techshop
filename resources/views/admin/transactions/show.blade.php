@extends('admin.layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900">
            Chi tiết Giao dịch Kho #{{ $transaction->id }}
        </h1>
</div>

    
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-6">Thông tin giao dịch</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-600">Mã giao dịch</p>
                            <p class="font-medium">#{{ $transaction->id }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600">Ngày giao dịch</p>
                            <p class="font-medium">{{ $transaction->created_at->format('d/m/Y H:i:s') }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600">Loại giao dịch</p>
                            <p>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    @if($transaction->type === 'import') bg-green-100 text-green-800
                                    @elseif($transaction->type === 'export') bg-red-100 text-red-800
                                    @elseif($transaction->type === 'return') bg-blue-100 text-blue-800
                                    @else bg-yellow-100 text-yellow-800
                                    @endif">
                                    @if($transaction->type === 'import') Nhập kho
                                    @elseif($transaction->type === 'export') Xuất kho
                                    @elseif($transaction->type === 'return') Hoàn trả
                                    @else Điều chỉnh
                                    @endif
                                </span>
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600">Số lượng</p>
                            <p class="font-bold text-lg {{ $transaction->quantity >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $transaction->quantity >= 0 ? '+' : '' }}{{ $transaction->quantity }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600">Người tạo</p>
                            <p class="font-medium">{{ $transaction->creator->name ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600">Loại tham chiếu</p>
                            <p class="font-medium">{{ $transaction->reference_type ?? '-' }}</p>
                        </div>

                        @if($transaction->reference_id)
                        <div>
                            <p class="text-sm text-gray-600">ID tham chiếu</p>
                            <p class="font-medium">
                                @if($transaction->reference_type === 'order')
                                    <a href="{{ route('admin.orders.show', $transaction->reference_id) }}" class="text-blue-600 hover:text-blue-900">
                                        #{{ $transaction->reference_id }}
                                    </a>
                                @else
                                    #{{ $transaction->reference_id }}
                                @endif
                            </p>
                        </div>
                        @endif
                    </div>

                    <div class="mt-6 pt-6 border-t">
                        <h4 class="font-semibold mb-3">Thông tin sản phẩm</h4>
                        <div class="bg-gray-50 rounded p-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600">SKU</p>
                                    <p class="font-medium">{{ $transaction->inventoryItem->sku }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Tên sản phẩm</p>
                                    <p class="font-medium">{{ $transaction->inventoryItem->name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Danh mục</p>
                                    <p class="font-medium">{{ $transaction->inventoryItem->category->name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Tồn kho hiện tại</p>
                                    <p class="font-medium">{{ $transaction->inventoryItem->stock_quantity }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($transaction->note)
                    <div class="mt-6 pt-6 border-t">
                        <h4 class="font-semibold mb-3">Ghi chú</h4>
                        <div class="bg-gray-50 rounded p-4">
                            <p class="text-gray-700">{{ $transaction->note }}</p>
                        </div>
                    </div>
                    @endif

                    <div class="mt-6 pt-6 border-t">
                        <a href="{{ route('admin.transactions.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                        <a href="{{ route('admin.inventory.show', $transaction->inventoryItem) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-2">
                            <i class="fas fa-box"></i> Xem Kho hàng
                        </a>
                    </div>
                </div>
            </div>
@endsection
