@extends('admin.layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900">
            Tạo Đơn hàng mới
        </h1>
</div>

    
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('admin.orders.store') }}" method="POST" id="orderForm">
                        @csrf

                        <!-- Customer Info -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-4">Thông tin khách hàng</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="user_id" class="block text-sm font-medium text-gray-700">Chọn khách hàng (tùy chọn)</label>
                                    <select name="user_id" id="user_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">-- Khách vãng lai --</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}" {{ old('user_id') == $customer->id ? 'selected' : '' }}>
                                                {{ $customer->name }} ({{ $customer->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Shipping Info -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-4">Thông tin giao hàng</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="shipping_name" class="block text-sm font-medium text-gray-700">Tên người nhận *</label>
                                    <input type="text" name="shipping_name" id="shipping_name" value="{{ old('shipping_name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                    @error('shipping_name')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="shipping_phone" class="block text-sm font-medium text-gray-700">Số điện thoại *</label>
                                    <input type="text" name="shipping_phone" id="shipping_phone" value="{{ old('shipping_phone') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                    @error('shipping_phone')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="md:col-span-2">
                                    <label for="shipping_address" class="block text-sm font-medium text-gray-700">Địa chỉ giao hàng *</label>
                                    <textarea name="shipping_address" id="shipping_address" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>{{ old('shipping_address') }}</textarea>
                                    @error('shipping_address')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Products -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-4">Sản phẩm</h3>
                            <div id="products-container">
                                <div class="product-item grid grid-cols-12 gap-4 mb-4">
                                    <div class="col-span-6">
                                        <label class="block text-sm font-medium text-gray-700">Sản phẩm *</label>
                                        <select name="items[0][product_id]" class="product-select mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                            <option value="">-- Chọn sản phẩm --</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}" data-price="{{ $product->discount_price ?? $product->price }}" data-stock="{{ $product->stock }}">
                                                    {{ $product->name }} - {{ number_format($product->discount_price ?? $product->price) }}đ (Tồn: {{ $product->stock }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-span-3">
                                        <label class="block text-sm font-medium text-gray-700">Số lượng *</label>
                                        <input type="number" name="items[0][quantity]" min="1" value="1" class="quantity-input mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                    </div>
                                    <div class="col-span-2">
                                        <label class="block text-sm font-medium text-gray-700">Thành tiền</label>
                                        <input type="text" class="subtotal mt-1 block w-full rounded-md border-gray-300 bg-gray-100" readonly value="0đ">
                                    </div>
                                    <div class="col-span-1 flex items-end">
                                        <button type="button" class="remove-product bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-3 rounded" style="display:none;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <button type="button" id="add-product" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                <i class="fas fa-plus"></i> Thêm sản phẩm
                            </button>
                        </div>

                        <!-- Payment Method -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-4">Phương thức thanh toán</h3>
                            <select name="payment_method" class="mt-1 block w-full md:w-1/2 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                <option value="cod" {{ old('payment_method') == 'cod' ? 'selected' : '' }}>COD (Thanh toán khi nhận hàng)</option>
                                <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Chuyển khoản ngân hàng</option>
                                <option value="credit_card" {{ old('payment_method') == 'credit_card' ? 'selected' : '' }}>Thẻ tín dụng</option>
                                <option value="paypal" {{ old('payment_method') == 'paypal' ? 'selected' : '' }}>PayPal</option>
                            </select>
                        </div>

                        <!-- Total -->
                        <div class="mb-6 p-4 bg-gray-50 rounded">
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-semibold">Tổng cộng:</span>
                                <span id="total-amount" class="text-2xl font-bold text-blue-600">0đ</span>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.orders.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Hủy
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Tạo Đơn hàng
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @section('scripts')

    <script>
        let productIndex = 1;

        function calculateTotal() {
            let total = 0;
            document.querySelectorAll('.product-item').forEach(item => {
                const select = item.querySelector('.product-select');
                const quantity = item.querySelector('.quantity-input').value;
                const option = select.options[select.selectedIndex];
                
                if (option.value) {
                    const price = parseFloat(option.dataset.price);
                    const subtotal = price * quantity;
                    total += subtotal;
                    item.querySelector('.subtotal').value = subtotal.toLocaleString('vi-VN') + 'đ';
                }
            });
            
            document.getElementById('total-amount').textContent = total.toLocaleString('vi-VN') + 'đ';
        }

        document.getElementById('add-product').addEventListener('click', function() {
            const container = document.getElementById('products-container');
            const newItem = container.querySelector('.product-item').cloneNode(true);
            
            // Update indices
            newItem.querySelectorAll('select, input').forEach(el => {
                if (el.name) {
                    el.name = el.name.replace(/\[\d+\]/, `[${productIndex}]`);
                }
                if (el.classList.contains('product-select')) {
                    el.value = '';
                }
                if (el.classList.contains('quantity-input')) {
                    el.value = 1;
                }
                if (el.classList.contains('subtotal')) {
                    el.value = '0đ';
                }
            });
            
            newItem.querySelector('.remove-product').style.display = 'block';
            container.appendChild(newItem);
            productIndex++;
            
            // Add event listeners to new item
            newItem.querySelector('.product-select').addEventListener('change', calculateTotal);
            newItem.querySelector('.quantity-input').addEventListener('input', calculateTotal);
            newItem.querySelector('.remove-product').addEventListener('click', function() {
                newItem.remove();
                calculateTotal();
                updateRemoveButtons();
            });
        });

        function updateRemoveButtons() {
            const items = document.querySelectorAll('.product-item');
            items.forEach((item, index) => {
                if (items.length > 1) {
                    item.querySelector('.remove-product').style.display = 'block';
                } else {
                    item.querySelector('.remove-product').style.display = 'none';
                }
            });
        }

        // Initial event listeners
        document.querySelectorAll('.product-select').forEach(select => {
            select.addEventListener('change', calculateTotal);
        });
        
        document.querySelectorAll('.quantity-input').forEach(input => {
            input.addEventListener('input', calculateTotal);
        });
    </script>
    
@endsection
@endsection
