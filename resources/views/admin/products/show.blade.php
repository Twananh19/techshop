@extends('admin.layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900">
            Chi tiết Sản phẩm: {{ $product->name }}
        </h1>
</div>

    
            <!-- Product Info -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Images -->
                        <div>
                            @if($product->images->count() > 0)
                                <div class="mb-4">
                                    <img src="{{ asset('storage/' . $product->mainImage->image_url) }}" alt="{{ $product->name }}" class="w-full h-64 object-cover rounded">
                                </div>
                                @if($product->images->count() > 1)
                                    <div class="grid grid-cols-4 gap-2">
                                        @foreach($product->images as $image)
                                            <img src="{{ asset('storage/' . $image->image_url) }}" alt="{{ $product->name }}" class="w-full h-20 object-cover rounded">
                                        @endforeach
                                    </div>
                                @endif
                            @else
                                <div class="w-full h-64 bg-gray-200 rounded flex items-center justify-center">
                                    <span class="text-gray-400">Chưa có ảnh</span>
                                </div>
                            @endif
                        </div>

                        <!-- Info -->
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-600">Tên sản phẩm</p>
                                <p class="font-medium text-lg">{{ $product->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">SKU Kho</p>
                                <p class="font-medium">
                                    <a href="{{ route('admin.inventory.show', $product->inventoryItem) }}" class="text-blue-600 hover:text-blue-900">
                                        {{ $product->inventoryItem->sku }}
                                    </a>
                                </p>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600">Giá bán</p>
                                    <p class="font-bold text-lg text-blue-600">{{ number_format($product->price) }}đ</p>
                                </div>
                                @if($product->discount_price)
                                <div>
                                    <p class="text-sm text-gray-600">Giá khuyến mãi</p>
                                    <p class="font-bold text-lg text-red-600">{{ number_format($product->discount_price) }}đ</p>
                                </div>
                                @endif
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600">Tồn kho hiển thị</p>
                                    <p class="font-medium {{ $product->stock < 10 ? 'text-red-600' : 'text-green-600' }}">{{ $product->stock }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Giới hạn tồn kho</p>
                                    <p class="font-medium">{{ $product->max_stock }}</p>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Trạng thái</p>
                                <p>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                                        @if($product->status === 'active') bg-green-100 text-green-800
                                        @elseif($product->status === 'draft') bg-gray-100 text-gray-800
                                        @elseif($product->status === 'out_of_stock') bg-red-100 text-red-800
                                        @else bg-yellow-100 text-yellow-800
                                        @endif">
                                        {{ ucfirst($product->status) }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Sản phẩm nổi bật</p>
                                <p class="font-medium">{{ $product->is_featured ? 'Có' : 'Không' }}</p>
                            </div>
                            @if($product->published_at)
                            <div>
                                <p class="text-sm text-gray-600">Ngày công khai</p>
                                <p class="font-medium">{{ $product->published_at->format('d/m/Y H:i') }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    @if($product->description)
                    <div class="mt-6 pt-6 border-t">
                        <p class="text-sm text-gray-600 mb-2">Mô tả</p>
                        <p class="text-gray-700">{!! nl2br(e($product->description)) !!}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Inventory Item Details -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Thông tin Kho hàng</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Tên kho</p>
                            <p class="font-medium">{{ $product->inventoryItem->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Thương hiệu</p>
                            <p class="font-medium">{{ $product->inventoryItem->brand }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Giá nhập</p>
                            <p class="font-medium">{{ number_format($product->inventoryItem->cost_price) }}đ</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Tồn kho thực tế</p>
                            <p class="font-medium">{{ $product->inventoryItem->stock_quantity }}</p>
                        </div>
                    </div>

                    @if($product->inventoryItem->attributeValues->count() > 0)
                    <div class="mt-6">
                        <h4 class="font-semibold mb-3">Thuộc tính</h4>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach($product->inventoryItem->attributeValues as $attrValue)
                                <div class="border rounded p-3">
                                    <p class="text-xs text-gray-600">{{ $attrValue->attribute->name }}</p>
                                    <p class="font-medium">{{ $attrValue->value }} {{ $attrValue->attribute->unit }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="mt-6 flex gap-2">
                <a href="{{ route('admin.products.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
                <a href="{{ route('admin.products.edit', $product) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-edit"></i> Sửa
                </a>
                @if($product->status === 'draft')
                    <form action="{{ route('admin.products.publish', $product->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            <i class="fas fa-check"></i> Công khai
                        </button>
                    </form>
                @elseif($product->status === 'active')
                    <form action="{{ route('admin.products.unpublish', $product->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                            <i class="fas fa-eye-slash"></i> Ẩn
                        </button>
                    </form>
                @endif
            </div>
@endsection
