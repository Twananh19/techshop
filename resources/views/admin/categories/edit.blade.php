@extends('admin.layouts.app')

@section('title', 'Sửa danh mục')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900">Sửa danh mục: {{ $category->name }}</h1>
</div>

<div class="bg-white shadow rounded-lg p-6">
    <form action="{{ route('admin.categories.update', $category) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Tên danh mục *</label>
                <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label for="slug" class="block text-sm font-medium text-gray-700">Slug (URL)</label>
                <input type="text" name="slug" id="slug" value="{{ old('slug', $category->slug) }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <p class="mt-1 text-sm text-gray-500">Để trống để tự động tạo từ tên</p>
            </div>

            <div>
                <label for="parent_id" class="block text-sm font-medium text-gray-700">Danh mục cha</label>
                <select name="parent_id" id="parent_id"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">-- Không có (danh mục gốc) --</option>
                    @foreach($parentCategories as $parent)
                        @if($parent->id !== $category->id)
                            <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
                                {{ $parent->name }}
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Trạng thái *</label>
                <select name="status" id="status" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="active" {{ old('status', $category->status) == 'active' ? 'selected' : '' }}>Hoạt động</option>
                    <option value="inactive" {{ old('status', $category->status) == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                </select>
            </div>

            <div>
                <label for="display_order" class="block text-sm font-medium text-gray-700">Thứ tự hiển thị</label>
                <input type="number" name="display_order" id="display_order" value="{{ old('display_order', $category->display_order) }}" min="0"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label for="image_url" class="block text-sm font-medium text-gray-700">URL ảnh</label>
                <input type="url" name="image_url" id="image_url" value="{{ old('image_url', $category->image_url) }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
        </div>

        <div class="mt-6">
            <label for="description" class="block text-sm font-medium text-gray-700">Mô tả</label>
            <textarea name="description" id="description" rows="4"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description', $category->description) }}</textarea>
        </div>

        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.categories.show', $category) }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Hủy</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700" style="background-color: #2563eb; margin-left: 10px;">Cập nhật</button>
        </div>
    </form>
</div>
@endsection
