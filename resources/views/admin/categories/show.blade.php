@extends('admin.layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900">
            Chi tiết Danh mục: {{ $category->name }}
        </h1>
</div>

    
            <!-- Category Info -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            @if($category->image_url)
                                <img src="{{ asset('storage/' . $category->image_url) }}" alt="{{ $category->name }}" class="w-full h-48 object-cover rounded">
                            @else
                                <div class="w-full h-48 bg-gray-200 rounded flex items-center justify-center">
                                    <span class="text-gray-400">Chưa có ảnh</span>
                                </div>
                            @endif
                        </div>
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-600">Tên danh mục</p>
                                <p class="font-medium text-lg">{{ $category->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Slug</p>
                                <p class="font-medium">{{ $category->slug }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Danh mục cha</p>
                                <p class="font-medium">{{ $category->parent->name ?? 'Không có' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Trạng thái</p>
                                <p>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $category->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($category->status) }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Thứ tự hiển thị</p>
                                <p class="font-medium">{{ $category->display_order }}</p>
                            </div>
                        </div>
                        @if($category->description)
                        <div class="md:col-span-2">
                            <p class="text-sm text-gray-600 mb-2">Mô tả</p>
                            <p class="text-gray-700">{{ $category->description }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sub Categories -->
            @if($category->children->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Danh mục con ({{ $category->children->count() }})</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($category->children as $child)
                            <div class="border rounded p-3">
                                <p class="font-medium">{{ $child->name }}</p>
                                <p class="text-xs text-gray-600">{{ $child->inventoryItems->count() }} sản phẩm</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Inventory Items -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Kho hàng ({{ $category->inventoryItems->count() }})</h3>
                    @if($category->inventoryItems->count() > 0)
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">SKU</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tên</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Thương hiệu</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tồn kho</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($category->inventoryItems as $item)
                                    <tr>
                                        <td class="px-4 py-2 text-sm">{{ $item->sku }}</td>
                                        <td class="px-4 py-2 text-sm">{{ $item->name }}</td>
                                        <td class="px-4 py-2 text-sm">{{ $item->brand }}</td>
                                        <td class="px-4 py-2 text-sm {{ $item->stock_quantity < 10 ? 'text-red-600 font-medium' : '' }}">
                                            {{ $item->stock_quantity }}
                                        </td>
                                        <td class="px-4 py-2 text-sm">
                                            <a href="{{ route('admin.inventory.show', $item) }}" class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-eye"></i> Xem
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-gray-500">Chưa có sản phẩm trong kho</p>
                    @endif
                </div>
            </div>

            <!-- Attributes -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Thuộc tính ({{ $category->productAttributes->count() }})</h3>
                    @if($category->productAttributes->count() > 0)
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach($category->productAttributes as $attribute)
                                <div class="border rounded p-3">
                                    <p class="font-medium">{{ $attribute->name }}</p>
                                    <p class="text-xs text-gray-600">{{ $attribute->unit ?? 'N/A' }}</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">Chưa có thuộc tính</p>
                    @endif
                </div>
            </div>

            <div class="mt-6">
                <a href="{{ route('admin.categories.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
                <a href="{{ route('admin.categories.edit', $category) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-2">
                    <i class="fas fa-edit"></i> Sửa
                </a>
            </div>
@endsection
