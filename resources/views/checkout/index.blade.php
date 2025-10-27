<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán - TechShop</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 text-white shadow-lg">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <a href="{{ route('home') }}" class="text-2xl font-bold" style="color: #000000ff;">TechShop</a>
                <div class="flex items-center space-x-4">
                    @auth
                        <span style="color: black;">Xin chào, {{ Auth::user()->name }}</span>
                    @else
                        <a href="{{ route('login') }}" class="bg-green-500 hover:bg-green-600 px-4 py-2 rounded-lg transition">Đăng nhập</a>
                        <a href="{{ route('register') }}" class="bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded-lg transition">Đăng ký</a>
                    @endauth
                    <a href="{{ route('cart.index') }}" class="relative bg-black hover:bg-gray-800 px-4 py-2 rounded-lg transition">
                        <svg class="w-6 h-6 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <div class="container mx-auto px-4 py-8" x-data="checkoutForm()">
        <!-- Breadcrumb -->
        <div class="mb-6">
            <nav class="text-sm breadcrumbs">
                <ul class="flex space-x-2 text-gray-600">
                    <li><a href="{{ route('home') }}" class="hover:text-indigo-600">Trang chủ</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li><a href="{{ route('cart.index') }}" class="hover:text-indigo-600">Giỏ hàng</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li class="text-indigo-600 font-semibold">Thanh toán</li>
                </ul>
            </nav>
        </div>

        <h1 class="text-3xl font-bold mb-8">Thanh toán</h1>

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('checkout.process') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            @csrf
            
            <!-- Left Column - Thông tin giao hàng -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Thông tin khách hàng -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold mb-4">Thông tin khách hàng</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Họ và tên <span class="text-red-500">*</span></label>
                            <input type="text" name="customer_name" x-model="form.customer_name" 
                                value="{{ old('customer_name', Auth::user()->name ?? '') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                required>
                            @error('customer_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Số điện thoại <span class="text-red-500">*</span></label>
                                <input type="tel" name="customer_phone" x-model="form.customer_phone"
                                    value="{{ old('customer_phone') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                    required>
                                @error('customer_phone')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                                <input type="email" name="customer_email" x-model="form.customer_email"
                                    value="{{ old('customer_email', Auth::user()->email ?? '') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                    required>
                                @error('customer_email')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Địa chỉ giao hàng -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold mb-4">Địa chỉ giao hàng</h2>

                    @if($savedAddresses->count() > 0)
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Chọn địa chỉ đã lưu</label>
                            <select @change="fillAddress($event.target.value)" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                <option value="">-- Nhập địa chỉ mới --</option>
                                @foreach($savedAddresses as $address)
                                    <option value="{{ $address->id }}" 
                                        data-name="{{ $address->full_name }}"
                                        data-phone="{{ $address->phone }}"
                                        data-address="{{ $address->address }}"
                                        data-city="{{ $address->city }}"
                                        data-district="{{ $address->district }}">
                                        {{ $address->full_name }} - {{ $address->phone }} - {{ $address->address }}, {{ $address->city }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Địa chỉ <span class="text-red-500">*</span></label>
                            <input type="text" name="shipping_address" x-model="form.shipping_address"
                                value="{{ old('shipping_address') }}"
                                placeholder="Số nhà, tên đường"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                required>
                            @error('shipping_address')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Thành phố <span class="text-red-500">*</span></label>
                                <input type="text" name="shipping_city" x-model="form.shipping_city"
                                    value="{{ old('shipping_city') }}"
                                    placeholder="VD: Hà Nội, TP.HCM"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                    required>
                                @error('shipping_city')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Quận/Huyện</label>
                                <input type="text" name="shipping_district" x-model="form.shipping_district"
                                    value="{{ old('shipping_district') }}"
                                    placeholder="VD: Cầu Giấy, Quận 1"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                @error('shipping_district')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        @auth
                            <div class="flex items-center">
                                <input type="checkbox" name="save_address" id="save_address" value="1" class="mr-2">
                                <label for="save_address" class="text-sm text-gray-700">Lưu địa chỉ này cho lần mua sau</label>
                            </div>
                        @endauth
                    </div>
                </div>

                <!-- Phương thức thanh toán -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold mb-4">Phương thức thanh toán</h2>
                    
                    <div class="space-y-3">
                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-indigo-500 transition">
                            <input type="radio" name="payment_method" value="cod" x-model="form.payment_method" class="mr-3" checked>
                            <div class="flex-1">
                                <div class="font-semibold">Thanh toán khi nhận hàng (COD)</div>
                                <div class="text-sm text-gray-600">Thanh toán bằng tiền mặt khi nhận hàng</div>
                            </div>
                        </label>

                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-indigo-500 transition">
                            <input type="radio" name="payment_method" value="bank_transfer" x-model="form.payment_method" class="mr-3">
                            <div class="flex-1">
                                <div class="font-semibold">Chuyển khoản ngân hàng</div>
                                <div class="text-sm text-gray-600">Chuyển khoản qua Internet Banking</div>
                            </div>
                        </label>

                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-indigo-500 transition">
                            <input type="radio" name="payment_method" value="momo" x-model="form.payment_method" class="mr-3">
                            <div class="flex-1">
                                <div class="font-semibold">Ví MoMo</div>
                                <div class="text-sm text-gray-600">Thanh toán qua ví điện tử MoMo</div>
                            </div>
                        </label>

                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-indigo-500 transition">
                            <input type="radio" name="payment_method" value="zalopay" x-model="form.payment_method" class="mr-3">
                            <div class="flex-1">
                                <div class="font-semibold">ZaloPay</div>
                                <div class="text-sm text-gray-600">Thanh toán qua ví điện tử ZaloPay</div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Ghi chú -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold mb-4">Ghi chú đơn hàng</h2>
                    <textarea name="notes" x-model="form.notes" rows="4" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        placeholder="Ghi chú về đơn hàng (tùy chọn)">{{ old('notes') }}</textarea>
                </div>
            </div>

            <!-- Right Column - Tóm tắt đơn hàng -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
                    <h2 class="text-xl font-semibold mb-4">Đơn hàng ({{ $cartItems->count() }} sản phẩm)</h2>
                    
                    <!-- Danh sách sản phẩm -->
                    <div class="space-y-4 mb-6 max-h-64 overflow-y-auto">
                        @foreach($cartItems as $item)
                            <div class="flex items-center space-x-3 pb-3 border-b">
                                @if($item->product->image)
                                    <img src="{{ $item->product->image }}" alt="{{ $item->product->name }}" class="w-16 h-16 object-cover rounded">
                                @else
                                    <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                                        <span class="text-gray-400 text-xs">No image</span>
                                    </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <div class="font-medium text-sm truncate">{{ $item->product->name }}</div>
                                    <div class="text-sm text-gray-600">SL: {{ $item->quantity }}</div>
                                </div>
                                <div class="text-sm font-semibold">{{ number_format($item->price * $item->quantity) }}₫</div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Tổng tiền -->
                    <div class="space-y-2 mb-6">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Tạm tính:</span>
                            <span class="font-semibold">{{ number_format($subtotal) }}₫</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Phí vận chuyển:</span>
                            <span class="font-semibold text-green-600">Miễn phí</span>
                        </div>
                        <div class="border-t pt-2 flex justify-between">
                            <span class="text-lg font-bold">Tổng cộng:</span>
                            <span class="text-lg font-bold text-indigo-600">{{ number_format($total) }}₫</span>
                        </div>
                    </div>

                    <!-- Nút đặt hàng -->
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition shadow-lg">
                        Đặt hàng
                    </button>

                    <a href="{{ route('cart.index') }}" class="block text-center text-indigo-600 hover:text-indigo-700 mt-4">
                        ← Quay lại giỏ hàng
                    </a>
                </div>
            </div>
        </form>
    </div>

    <script>
        function checkoutForm() {
            return {
                form: {
                    customer_name: '{{ old('customer_name', Auth::user()->name ?? '') }}',
                    customer_phone: '{{ old('customer_phone') }}',
                    customer_email: '{{ old('customer_email', Auth::user()->email ?? '') }}',
                    shipping_address: '{{ old('shipping_address') }}',
                    shipping_city: '{{ old('shipping_city') }}',
                    shipping_district: '{{ old('shipping_district') }}',
                    payment_method: 'cod',
                    notes: ''
                },
                fillAddress(addressId) {
                    if (!addressId) return;
                    
                    const select = event.target;
                    const option = select.options[select.selectedIndex];
                    
                    this.form.customer_name = option.dataset.name;
                    this.form.customer_phone = option.dataset.phone;
                    this.form.shipping_address = option.dataset.address;
                    this.form.shipping_city = option.dataset.city;
                    this.form.shipping_district = option.dataset.district || '';
                }
            }
        }
    </script>
</body>
</html>
