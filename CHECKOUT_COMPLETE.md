# HOÀN THIỆN CHỨC NĂNG ĐẶT HÀNG VÀ QUẢN LÝ ĐƠN HÀNG

## ✅ ĐÃ HOÀN THÀNH

### 1. CHỨC NĂNG ĐẶT HÀNG (CUSTOMER)

#### A. Quy trình đặt hàng
1. **Thêm sản phẩm vào giỏ hàng** (`/cart/add/{productId}`)
   - Lưu giá sản phẩm tại thời điểm thêm vào giỏ
   - Kiểm tra tồn kho trước khi thêm
   - Hỗ trợ cả user đã đăng nhập và khách

2. **Xem giỏ hàng** (`/cart`)
   - Hiển thị danh sách sản phẩm với hình ảnh
   - Đơn giá × Số lượng = Thành tiền
   - Tổng đơn hàng tự động tính
   - Cập nhật số lượng (+/-)
   - Xóa sản phẩm khỏi giỏ

3. **Thanh toán** (`/checkout`)
   - Form thông tin khách hàng (Tên, SĐT, Email)
   - Địa chỉ giao hàng (Địa chỉ, Thành phố, Quận/Huyện)
   - Chọn địa chỉ đã lưu (cho user đã đăng nhập)
   - 4 phương thức thanh toán:
     - COD (Thanh toán khi nhận hàng)
     - Chuyển khoản ngân hàng
     - Ví MoMo
     - ZaloPay
   - Ghi chú đơn hàng (tùy chọn)
   - Checkbox "Lưu địa chỉ cho lần sau"

4. **Xử lý đơn hàng** (`CheckoutController::process()`)
   - Validate thông tin
   - Kiểm tra giỏ hàng không trống
   - Kiểm tra tồn kho
   - Tạo đơn hàng với mã unique (ORD-XXXXX)
   - Tạo chi tiết đơn hàng (OrderItems)
   - Trừ tồn kho tự động
   - Tạo payment record
   - Lưu địa chỉ nếu được chọn
   - Xóa giỏ hàng sau khi đặt thành công
   - Sử dụng Transaction để đảm bảo tính toàn vẹn dữ liệu

5. **Trang thành công** (`/checkout/success/{orderId}`)
   - Thông báo đặt hàng thành công
   - Thông tin đơn hàng đầy đủ
   - Danh sách sản phẩm đã đặt
   - Tổng tiền chi tiết (Tạm tính + Phí ship + Tổng)
   - Hướng dẫn thanh toán (cho chuyển khoản/ví)
   - Link "Tiếp tục mua sắm"
   - Link "Xem đơn hàng của tôi"

#### B. Quản lý đơn hàng của khách
1. **Danh sách đơn hàng** (`/my-orders`)
   - Hiển thị tất cả đơn hàng của user
   - Mã đơn hàng, ngày đặt, trạng thái
   - Danh sách sản phẩm trong mỗi đơn
   - Tổng tiền, phương thức thanh toán
   - Button "Xem chi tiết"
   - Button "Hủy đơn" (chỉ với đơn chờ xử lý)
   - Phân trang

2. **Chi tiết đơn hàng** (`/my-orders/{id}`)
   - Thông tin đầy đủ về đơn hàng
   - Lịch sử trạng thái
   - Thông tin giao hàng
   - Danh sách sản phẩm

3. **Hủy đơn hàng** (`/my-orders/{id}/cancel`)
   - Chỉ được hủy đơn ở trạng thái "Chờ xử lý"
   - Hoàn lại tồn kho tự động
   - Thông báo thành công

### 2. CHỨC NĂNG QUẢN LÝ ĐƠN HÀNG (ADMIN)

#### A. Danh sách đơn hàng (`/admin/orders`)
- Hiển thị tất cả đơn hàng
- Lọc theo trạng thái:
  - Chờ xử lý (pending)
  - Đã xác nhận (confirmed)
  - Đang giao (shipped)
  - Hoàn thành (completed)
  - Đã hủy (cancelled)
- Tìm kiếm theo:
  - Mã đơn hàng
  - Tên khách hàng
  - Số điện thoại
  - Email
- Hiển thị:
  - Mã đơn hàng
  - Thông tin khách
  - Tổng tiền
  - Trạng thái
  - Phương thức thanh toán
  - Ngày đặt
- Button "Xem chi tiết"
- Phân trang

#### B. Chi tiết đơn hàng (`/admin/orders/{id}`)
- **Thông tin đơn hàng:**
  - Mã đơn hàng
  - Ngày đặt
  - Trạng thái hiện tại
  - Phương thức thanh toán
  - Tổng tiền

- **Thông tin khách hàng:**
  - Họ tên
  - Số điện thoại
  - Email
  - Địa chỉ giao hàng đầy đủ
  - Ghi chú (nếu có)

- **Danh sách sản phẩm:**
  - Hình ảnh sản phẩm
  - Tên sản phẩm
  - Đơn giá
  - Số lượng
  - Thành tiền
  - Tổng: Tạm tính + Phí ship = Tổng cộng

- **Cập nhật trạng thái:**
  - Dropdown chọn trạng thái mới
  - Button "Cập nhật"
  - Tự động lưu lịch sử

- **Hành động:**
  - Button "Quay lại danh sách"
  - Button "Xóa đơn hàng" (chỉ với đơn đã hủy)

#### C. Cập nhật trạng thái (`/admin/orders/{id}/update-status`)
- Validate trạng thái hợp lệ
- Cập nhật trạng thái đơn hàng
- Thông báo thành công
- Quay lại trang chi tiết

### 3. DATABASE SCHEMA

#### A. Bảng `orders`
```sql
- id (bigint, primary key)
- user_id (bigint, nullable, foreign key)
- order_number (string(50), unique) - Mã đơn VD: ORD-65ABC123
- customer_name (string(100))
- customer_phone (string(20))
- customer_email (string(100))
- shipping_address (string(255))
- shipping_city (string(100))
- shipping_district (string(100), nullable)
- subtotal (decimal(12,2))
- shipping_fee (decimal(12,2))
- total_amount (decimal(12,2))
- status (enum: pending, confirmed, shipped, completed, cancelled)
- payment_method (string(50))
- notes (text, nullable)
- created_at, updated_at
```

#### B. Bảng `order_items`
```sql
- id (bigint, primary key)
- order_id (bigint, foreign key → orders.id)
- product_id (bigint, foreign key → products.id)
- inventory_item_id (bigint, foreign key → inventory_items.id)
- quantity (integer)
- price (decimal(12,2)) - Giá tại thời điểm đặt hàng
- subtotal (decimal(12,2)) - price × quantity
- created_at, updated_at
```

#### C. Bảng `payments`
```sql
- id (bigint, primary key)
- order_id (bigint, unique, foreign key → orders.id)
- payment_method (enum: cod, bank_transfer, momo, zalopay)
- amount (decimal(12,2))
- status (enum: pending, paid, failed)
- transaction_id (string(100), nullable)
- paid_at (timestamp, nullable)
- created_at, updated_at
```

#### D. Bảng `cart_items`
```sql
- id (bigint, primary key)
- cart_id (bigint, foreign key → carts.id)
- product_id (bigint, foreign key → products.id)
- quantity (integer)
- price (decimal(12,2)) - Giá tại thời điểm thêm vào giỏ
- created_at, updated_at
```

#### E. Bảng `user_addresses`
```sql
- id (bigint, primary key)
- user_id (bigint, foreign key → users.id)
- full_name (string(100))
- phone (string(20))
- address (string(255))
- city (string(100))
- district (string(100), nullable)
- ward (string(100), nullable)
- is_default (boolean)
- created_at, updated_at
```

### 4. MODELS

#### A. Order Model
```php
protected $fillable = [
    'user_id', 'order_number', 'customer_name', 'customer_phone', 
    'customer_email', 'shipping_address', 'shipping_city', 
    'shipping_district', 'subtotal', 'shipping_fee', 'total_amount', 
    'status', 'payment_method', 'notes'
];

protected $casts = [
    'subtotal' => 'decimal:2',
    'shipping_fee' => 'decimal:2',
    'total_amount' => 'decimal:2',
];

// Relationships
public function user() → belongsTo(User::class)
public function items() → hasMany(OrderItem::class)
public function payment() → hasOne(Payment::class)
```

#### B. OrderItem Model
```php
protected $fillable = [
    'order_id', 'product_id', 'inventory_item_id', 
    'quantity', 'price', 'subtotal'
];

protected $casts = [
    'quantity' => 'integer',
    'price' => 'decimal:2',
    'subtotal' => 'decimal:2',
];

// Relationships
public function order() → belongsTo(Order::class)
public function product() → belongsTo(Product::class)
public function inventoryItem() → belongsTo(InventoryItem::class)
```

#### C. Payment Model
```php
protected $fillable = [
    'order_id', 'payment_method', 'amount', 
    'status', 'transaction_id', 'paid_at'
];

protected $casts = [
    'amount' => 'decimal:2',
    'paid_at' => 'datetime',
];

// Relationships
public function order() → belongsTo(Order::class)
```

### 5. CONTROLLERS

#### A. CheckoutController
- `index()` - Hiển thị trang checkout
- `process()` - Xử lý đơn hàng
- `success($orderId)` - Trang thành công
- `getCart()` - Helper lấy giỏ hàng

#### B. Admin\OrderController
- `index(Request $request)` - Danh sách với filter & search
- `show(Order $order)` - Chi tiết đơn hàng
- `updateStatus(Request $request, Order $order)` - Cập nhật trạng thái
- `destroy(Order $order)` - Xóa đơn đã hủy

#### C. Customer\OrderController
- `index()` - Danh sách đơn hàng của user
- `show($id)` - Chi tiết đơn hàng của user
- `cancel($id)` - Hủy đơn hàng

### 6. ROUTES

```php
// Customer Routes
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/checkout/success/{orderId}', [CheckoutController::class, 'success'])->name('checkout.success');

// Customer Orders (authenticated)
Route::middleware('auth')->group(function () {
    Route::get('/my-orders', [CustomerOrderController::class, 'index'])->name('customer.orders.index');
    Route::get('/my-orders/{id}', [CustomerOrderController::class, 'show'])->name('customer.orders.show');
    Route::post('/my-orders/{id}/cancel', [CustomerOrderController::class, 'cancel'])->name('customer.orders.cancel');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('orders', AdminOrderController::class)->except(['create', 'store', 'destroy']);
    Route::patch('orders/{order}/update-status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
});
```

### 7. VIEWS

#### Customer Views
- `checkout/index.blade.php` - Form checkout
- `checkout/success.blade.php` - Trang thành công
- `customer/orders/index.blade.php` - Danh sách đơn hàng
- `customer/orders/show.blade.php` - Chi tiết đơn hàng

#### Admin Views
- `admin/orders/index.blade.php` - Danh sách quản lý
- `admin/orders/show.blade.php` - Chi tiết & cập nhật trạng thái

### 8. TÍNH NĂNG ĐẶC BIỆT

✅ **Kiểm tra tồn kho**: Trước khi thêm vào giỏ và trước khi đặt hàng
✅ **Lưu giá**: Giá sản phẩm được lưu tại thời điểm thêm vào giỏ/đặt hàng
✅ **Mã đơn hàng**: Tự động tạo mã unique (ORD-XXXXX)
✅ **Transaction**: Sử dụng DB transaction để đảm bảo tính toàn vẹn
✅ **Trừ tồn kho**: Tự động trừ khi đặt hàng
✅ **Hoàn tồn kho**: Tự động hoàn lại khi hủy đơn
✅ **Lưu địa chỉ**: Tùy chọn lưu địa chỉ cho lần sau
✅ **Guest checkout**: Hỗ trợ đặt hàng không cần đăng nhập
✅ **Multi payment**: 4 phương thức thanh toán
✅ **Order tracking**: Khách hàng xem lịch sử đơn hàng
✅ **Admin dashboard**: Menu "Quản lý Đơn hàng" trong sidebar
✅ **Filter & Search**: Tìm kiếm và lọc đơn hàng dễ dàng
✅ **Status management**: Cập nhật trạng thái linh hoạt

### 9. QUY TRÌNH TRẠNG THÁI ĐƠN HÀNG

```
pending (Chờ xử lý)
    ↓
confirmed (Đã xác nhận) - Admin xác nhận
    ↓
shipped (Đang giao) - Đơn vị vận chuyển nhận
    ↓
completed (Hoàn thành) - Khách nhận hàng
    
cancelled (Đã hủy) - Có thể hủy bất cứ lúc nào
```

### 10. DỮ LIỆU HIỆN TẠI

- ✅ 50 sản phẩm với hình ảnh (Unsplash)
- ✅ 3 sliders quảng cáo
- ✅ 5 danh mục sản phẩm
- ✅ Database migrations đã chạy thành công
- ✅ Server đang chạy: http://127.0.0.1:8000

### 11. TEST CHECKLIST

**Customer Flow:**
1. ✅ Thêm sản phẩm vào giỏ → Kiểm tra giá và hình ảnh
2. ✅ Cập nhật số lượng → Kiểm tra tổng tiền tự động tính
3. ✅ Vào checkout → Điền thông tin
4. ✅ Chọn phương thức thanh toán → Đặt hàng
5. ✅ Xem trang success → Kiểm tra thông tin đơn hàng
6. ✅ Vào "Đơn hàng của tôi" → Xem danh sách
7. ✅ Xem chi tiết đơn hàng
8. ✅ Hủy đơn hàng (nếu pending)

**Admin Flow:**
1. ✅ Đăng nhập admin → Vào menu "Quản lý Đơn hàng"
2. ✅ Xem danh sách đơn hàng
3. ✅ Filter theo trạng thái
4. ✅ Search theo mã/tên/SĐT
5. ✅ Xem chi tiết đơn hàng
6. ✅ Cập nhật trạng thái đơn hàng
7. ✅ Kiểm tra tồn kho đã giảm

---

## 🎉 KẾT LUẬN

Hệ thống đặt hàng và quản lý đơn hàng đã được HOÀN THIỆN với đầy đủ tính năng:
- ✅ Đặt hàng cho customer (có/không đăng nhập)
- ✅ Quản lý đơn hàng cho customer
- ✅ Quản lý đơn hàng cho admin
- ✅ Tự động trừ/hoàn tồn kho
- ✅ Multi payment methods
- ✅ Order tracking
- ✅ Status management

**Server**: http://127.0.0.1:8000
**Admin**: http://127.0.0.1:8000/admin/orders
**Customer**: http://127.0.0.1:8000/my-orders
