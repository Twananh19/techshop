@extends('admin.layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900">
            Thêm Thuộc tính Sản phẩm
        </h1>
</div>

    
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('admin.attributes.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="category_id" class="block text-sm font-medium text-gray-700">Danh mục *</label>
                            <select name="category_id" id="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                <option value="">-- Chọn danh mục --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Tên thuộc tính *</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="VD: RAM, ROM, Chip, Màn hình" required>
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="unit" class="block text-sm font-medium text-gray-700">Đơn vị</label>
                            <input type="text" name="unit" id="unit" value="{{ old('unit') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="VD: GB, inch, Hz, MP">
                            @error('unit')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.attributes.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded mr-2">
                                Hủy
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Tạo Thuộc tính
                            </button>
                        </div>
                    </form>
                </div>
            </div>
@endsection
