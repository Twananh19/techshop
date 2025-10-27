@extends('admin.layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900">
            Sửa Người dùng: {{ $user->name }}
        </h1>
</div>

    
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('admin.users.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Tên *</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email *</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="role" class="block text-sm font-medium text-gray-700">Vai trò *</label>
                            <select name="role" id="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                <option value="customer" {{ old('role', $user->role) == 'customer' ? 'selected' : '' }}>Khách hàng</option>
                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                            @error('role')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="block text-sm font-medium text-gray-700">Mật khẩu mới (để trống nếu không đổi)</label>
                            <input type="password" name="password" id="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Xác nhận mật khẩu mới</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.users.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded mr-2">
                                Hủy
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Cập nhật
                            </button>
                        </div>
                    </form>
                </div>
            </div>
@endsection
