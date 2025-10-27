@extends('admin.layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900">
            Chi tiết Kho hàng: {{ $item->sku }}
        </h1>
</div>

    
            <!-- Inventory Info -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Thông tin cơ bản</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <p class="text-sm text-gray-600">SKU</p>
                            <p class="font-medium text-lg">{{ $item->sku }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Tên sản phẩm</p>
                            <p class="font-medium text-lg">{{ $item->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Danh mục</p>
                            <p class="font-medium">{{ $item->category->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Thương hiệu</p>
                            <p class="font-medium">{{ $item->brand }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Giá nhập</p>
                            <p class="font-medium text-blue-600">{{ number_format($item->cost_price) }}đ</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Tồn kho</p>
                            <p class="font-bold text-lg {{ $item->stock_quantity < 10 ? 'text-red-600' : 'text-green-600' }}">
                                {{ $item->stock_quantity }}
                            </p>
                        </div>
                        <div class="md:col-span-3">
                            <p class="text-sm text-gray-600">Mô tả</p>
                            <p class="font-medium">{{ $item->description }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attributes -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Thuộc tính ({{ $item->attributeValues->count() }})</h3>
                    @if($item->attributeValues->count() > 0)
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach($item->attributeValues as $attrValue)
                                <div class="border rounded p-3">
                                    <p class="text-xs text-gray-600">{{ $attrValue->attribute->name }}</p>
                                    <p class="font-medium">{{ $attrValue->value }} {{ $attrValue->attribute->unit }}</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">Chưa có thuộc tính</p>
                    @endif
                </div>
            </div>

            <!-- Products -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Sản phẩm bán ({{ $item->products->count() }})</h3>
                    @if($item->products->count() > 0)
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tên</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Giá bán</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tồn</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Trạng thái</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($item->products as $product)
                                    <tr>
                                        <td class="px-4 py-2 text-sm">{{ $product->name }}</td>
                                        <td class="px-4 py-2 text-sm">{{ number_format($product->price) }}đ</td>
                                        <td class="px-4 py-2 text-sm">{{ $product->stock }}</td>
                                        <td class="px-4 py-2 text-sm">
                                            <span class="px-2 py-1 text-xs rounded-full
                                                @if($product->status === 'active') bg-green-100 text-green-800
                                                @elseif($product->status === 'draft') bg-gray-100 text-gray-800
                                                @else bg-red-100 text-red-800
                                                @endif">
                                                {{ ucfirst($product->status) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2 text-sm">
                                            <a href="{{ route('admin.products.show', $product) }}" class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-eye"></i> Xem
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-gray-500">Chưa có sản phẩm bán</p>
                    @endif
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Giao dịch gần đây (10 mới nhất)</h3>
                    @if($item->transactions->count() > 0)
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Ngày</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Loại</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Số lượng</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Ghi chú</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($item->transactions->take(10) as $transaction)
                                    <tr>
                                        <td class="px-4 py-2 text-sm">{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="px-4 py-2 text-sm">
                                            <span class="px-2 py-1 text-xs rounded-full
                                                @if($transaction->type === 'import') bg-green-100 text-green-800
                                                @elseif($transaction->type === 'export') bg-red-100 text-red-800
                                                @else bg-yellow-100 text-yellow-800
                                                @endif">
                                                {{ ucfirst($transaction->type) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2 text-sm font-medium {{ $transaction->quantity >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $transaction->quantity >= 0 ? '+' : '' }}{{ $transaction->quantity }}
                                        </td>
                                        <td class="px-4 py-2 text-sm">{{ Str::limit($transaction->note, 40) }}</td>
                                        <td class="px-4 py-2 text-sm">
                                            <a href="{{ route('admin.transactions.show', $transaction) }}" class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-eye"></i> Xem
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-gray-500">Chưa có giao dịch</p>
                    @endif
                </div>
            </div>

            <div class="mt-6">
                <a href="{{ route('admin.inventory.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
                <a href="{{ route('admin.inventory.edit', $item) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-2">
                    <i class="fas fa-edit"></i> Sửa
                </a>
            </div>
@endsection
