@extends('admin.layouts.app')

@section('title', 'Quản lý Slider')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Quản lý Slider</h1>
        <p class="mt-1 text-sm text-gray-600">Quản lý ảnh banner quảng cáo trang chủ</p>
    </div>
    <a href="{{ route('admin.sliders.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Thêm Slider
    </a>
</div>

@if(session('success'))
    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white shadow rounded-lg overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ảnh</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tiêu đề</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phụ đề</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Thứ tự</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Trạng thái</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Thao tác</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($sliders as $slider)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <img src="{{ $slider->image }}" alt="{{ $slider->title }}" class="h-16 w-32 object-cover rounded">
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">{{ $slider->title }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-600">{{ $slider->subtitle }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100">{{ $slider->order }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $slider->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $slider->status === 'active' ? 'Đang hiển thị' : 'Đã ẩn' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.sliders.edit', $slider) }}" 
                               class="text-blue-600 hover:text-blue-900">
                                Sửa
                            </a>
                            <form action="{{ route('admin.sliders.destroy', $slider) }}" method="POST" 
                                  onsubmit="return confirm('Bạn có chắc muốn xóa slider này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">
                                    Xóa
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                        Chưa có slider nào. <a href="{{ route('admin.sliders.create') }}" class="text-blue-600 hover:text-blue-800">Tạo slider đầu tiên</a>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
