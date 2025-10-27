# 🎯 HƯỚNG DẪN SỬ DỤNG TECHSHOP

## 🚀 KHỞI ĐỘNG NHANH

### Cách 1: Sử dụng script tự động (Khuyến nghị)
```bash
bash start-server.sh
```

### Cách 2: Chạy thủ công
```bash
php artisan serve
```

Server sẽ chạy tại: **http://127.0.0.1:8000**

---

## 📊 KIỂM TRA HỆ THỐNG

Kiểm tra trạng thái hệ thống trước khi chạy:
```bash
bash check-system.sh
```

---

## 🔐 TÀI KHOẢN MẶC ĐỊNH

### Admin:
- **Email:** admin@techshop.com
- **Password:** password
- **URL:** http://127.0.0.1:8000/admin/dashboard

### Customer Demo:
- **Email:** customer@techshop.com
- **Password:** password

---

## 🛠️ LỆNH THƯỜNG DÙNG

### 1. Quản lý Server
```bash
# Khởi động server
bash start-server.sh

# Dừng server
kill -9 $(lsof -ti:8000)

# Kiểm tra trạng thái
bash check-system.sh
```

### 2. Database
```bash
# Xem danh sách users
bash check-users.sh

# Chạy migrations
php artisan migrate

# Reset database (XÓA DATA)
php artisan migrate:fresh --seed

# Kiểm tra migration status
php artisan migrate:status
```

### 3. Cache Management
```bash
# Clear tất cả cache
php artisan optimize:clear

# Clear từng loại
php artisan cache:clear      # Application cache
php artisan config:clear     # Config cache
php artisan route:clear      # Route cache
php artisan view:clear       # View cache
```

### 4. Development
```bash
# Xem routes
php artisan route:list

# Tạo controller mới
php artisan make:controller ControllerName

# Tạo model mới
php artisan make:model ModelName -m

# Chạy tests
php artisan test
```

---

## 📁 CẤU TRÚC DỰ ÁN

```
techshop/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── Admin/          # Admin controllers
│   └── Models/                 # Database models
├── resources/
│   └── views/
│       ├── admin/              # Admin views
│       ├── auth/               # Authentication views
│       └── layouts/            # Layouts
├── routes/
│   ├── web.php                 # Web routes
│   └── auth.php                # Auth routes
├── database/
│   ├── migrations/             # Database migrations
│   └── seeders/                # Database seeders
├── public/                     # Public assets
├── storage/                    # Storage files
└── .env                        # Environment config
```

---

## 🎨 MODULE ADMIN ĐÃ CÓ

1. **Dashboard** - Tổng quan hệ thống
2. **Quản lý Kho** - Inventory management
3. **Quản lý Danh mục** - Categories
4. **Quản lý Sản phẩm** - Products
5. **Thuộc tính SP** - Product attributes
6. **Quản lý Đơn hàng** - Orders
7. **Quản lý Người dùng** - Users
8. **Lịch sử GD Kho** - Inventory transactions

---

## 🌐 ROUTES QUAN TRỌNG

### Public:
- Home: `http://127.0.0.1:8000`
- Login: `http://127.0.0.1:8000/login`
- Register: `http://127.0.0.1:8000/register`

### Admin (Cần đăng nhập):
- Dashboard: `http://127.0.0.1:8000/admin/dashboard`
- Inventory: `http://127.0.0.1:8000/admin/inventory`
- Categories: `http://127.0.0.1:8000/admin/categories`
- Products: `http://127.0.0.1:8000/admin/products`
- Orders: `http://127.0.0.1:8000/admin/orders`
- Users: `http://127.0.0.1:8000/admin/users`
- Attributes: `http://127.0.0.1:8000/admin/attributes`
- Transactions: `http://127.0.0.1:8000/admin/transactions`

---

## 🔧 TROUBLESHOOTING

### Lỗi: "Access denied for user 'laravel'"
```bash
sudo mysql -e "SET PASSWORD FOR 'laravel'@'localhost' = PASSWORD('laravel123'); FLUSH PRIVILEGES;"
```

### Lỗi: "Port 8000 already in use"
```bash
kill -9 $(lsof -ti:8000)
```

### Lỗi: "Storage not linked"
```bash
php artisan storage:link
```

### Lỗi: "Permission denied"
```bash
chmod -R 775 storage bootstrap/cache
```

### View bị cache cũ
```bash
php artisan view:clear
rm -rf storage/framework/views/*
```

**Chi tiết hơn xem:** `FIX_ERRORS.md`

---

## 📚 TÀI LIỆU THAM KHẢO

- Laravel Documentation: https://laravel.com/docs
- Tailwind CSS: https://tailwindcss.com/docs
- Chart.js: https://www.chartjs.org/docs

---

## ✨ TÍNH NĂNG ĐÃ HOÀN THIỆN

- ✅ Authentication (Login/Register/Logout)
- ✅ Social Login (Google OAuth)
- ✅ Admin Dashboard với biểu đồ
- ✅ CRUD đầy đủ cho tất cả modules
- ✅ Inventory Management
- ✅ Order Management
- ✅ User Management
- ✅ Product Attributes
- ✅ Category Management
- ✅ Responsive Design
- ✅ Database seeded với demo data

---

## 📝 GHI CHÚ

- Database: MySQL/MariaDB
- PHP Version: 8.3.6
- Laravel Version: 12.33.0
- Frontend: Tailwind CSS + Blade
- Charts: Chart.js

---

## 🎉 BẮT ĐẦU NGAY

```bash
# 1. Kiểm tra hệ thống
bash check-system.sh

# 2. Khởi động server
bash start-server.sh

# 3. Mở trình duyệt
# http://127.0.0.1:8000

# 4. Đăng nhập admin
# Email: admin@techshop.com
# Password: password
```

**Chúc bạn code vui vẻ! 🚀**
