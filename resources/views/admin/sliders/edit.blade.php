@extends('admin.layouts.app')

@section('title', 'Chỉnh sửa Slider')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900">Chỉnh sửa Slider</h1>
    <p class="mt-1 text-sm text-gray-600">Cập nhật thông tin banner quảng cáo</p>
</div>

<div class="bg-white shadow rounded-lg p-6">
    <form action="{{ route('admin.sliders.update', $slider) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-6">
            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">Tiêu đề *</label>
                <input type="text" name="title" id="title" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                       value="{{ old('title', $slider->title) }}">
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Subtitle -->
            <div>
                <label for="subtitle" class="block text-sm font-medium text-gray-700">Phụ đề</label>
                <input type="text" name="subtitle" id="subtitle"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                       value="{{ old('subtitle', $slider->subtitle) }}">
                @error('subtitle')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Image URL -->
            <div>
                <label for="image" class="block text-sm font-medium text-gray-700">URL Ảnh *</label>
                <input type="text" name="image" id="image" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                       value="{{ old('image', $slider->image) }}">
                <p class="mt-1 text-sm text-gray-500">Kích thước khuyến nghị: 1920x600px</p>
                @error('image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                
                <!-- Preview -->
                @if($slider->image)
                    <div class="mt-2">
                        <img src="{{ $slider->image }}" alt="Preview" class="h-32 rounded border">
                    </div>
                @endif
            </div>

            <!-- Link -->
            <div>
                <label for="link" class="block text-sm font-medium text-gray-700">Link (URL)</label>
                <input type="text" name="link" id="link"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                       value="{{ old('link', $slider->link) }}">
                <p class="mt-1 text-sm text-gray-500">Link khi click vào slider</p>
                @error('link')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Order -->
            <div>
                <label for="order" class="block text-sm font-medium text-gray-700">Thứ tự hiển thị *</label>
                <input type="number" name="order" id="order" required min="0"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                       value="{{ old('order', $slider->order) }}">
                <p class="mt-1 text-sm text-gray-500">Slider có thứ tự nhỏ hơn sẽ hiển thị trước</p>
                @error('order')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Trạng thái *</label>
                <select name="status" id="status" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="active" {{ old('status', $slider->status) === 'active' ? 'selected' : '' }}>Hiển thị</option>
                    <option value="inactive" {{ old('status', $slider->status) === 'inactive' ? 'selected' : '' }}>Ẩn</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.sliders.index') }}" 
               class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                Hủy
            </a>
            <button type="submit" 
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                Cập nhật Slider
            </button>
        </div>
    </form>
</div>
@endsection
