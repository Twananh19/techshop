@extends('admin.layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900">
            Lịch sử Giao dịch Kho
        </h1>
</div>

    
            <!-- Filters -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="GET" action="{{ route('admin.transactions.index') }}" class="flex gap-4 flex-wrap">
                        <div class="flex-1 min-w-[200px]">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm theo SKU hoặc ghi chú..." class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <select name="type" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Tất cả loại GD</option>
                                <option value="import" {{ request('type') == 'import' ? 'selected' : '' }}>Nhập kho</option>
                                <option value="export" {{ request('type') == 'export' ? 'selected' : '' }}>Xuất kho</option>
                                <option value="adjustment" {{ request('type') == 'adjustment' ? 'selected' : '' }}>Điều chỉnh</option>
                                <option value="return" {{ request('type') == 'return' ? 'selected' : '' }}>Hoàn trả</option>
                            </select>
                        </div>
                        <div>
                            <select name="inventory_item_id" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Tất cả sản phẩm</option>
                                @foreach($inventoryItems as $item)
                                    <option value="{{ $item->id }}" {{ request('inventory_item_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->sku }} - {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            <i class="fas fa-search"></i> Tìm kiếm
                        </button>
                        @if(request()->hasAny(['search', 'type', 'inventory_item_id']))
                            <a href="{{ route('admin.transactions.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                <i class="fas fa-times"></i> Xóa bộ lọc
                            </a>
                        @endif
                    </form>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày GD</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU / Sản phẩm</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Loại GD</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số lượng</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Người tạo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ghi chú</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($transactions as $transaction)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        #{{ $transaction->id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $transaction->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <p class="font-medium">{{ $transaction->inventoryItem->sku }}</p>
                                        <p class="text-gray-600 text-xs">{{ $transaction->inventoryItem->name }}</p>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
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
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span class="font-medium {{ $transaction->quantity >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $transaction->quantity >= 0 ? '+' : '' }}{{ $transaction->quantity }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $transaction->creator->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ Str::limit($transaction->note, 50) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('admin.transactions.show', $transaction) }}" class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-eye"></i> Xem
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                        Không tìm thấy giao dịch
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $transactions->links() }}
                    </div>
                </div>
            </div>
@endsection
