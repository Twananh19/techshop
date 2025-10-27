# 🔧 FIX LỖI TECHSHOP - HƯỚNG DẪN KHẮC PHỤC

## ✅ ĐÃ FIX CÁC LỖI SAU:

### 1. ❌ Lỗi Database Connection
**Triệu chứng:** `Access denied for user 'laravel'@'localhost'`

**Đã fix:**
```bash
sudo mysql -e "SET PASSWORD FOR 'laravel'@'localhost' = PASSWORD('laravel123'); FLUSH PRIVILEGES;"
```

### 2. ❌ Lỗi Storage Link
**Triệu chứng:** `The public/storage directory is not linked`

**Đã fix:**
```bash
php artisan storage:link
```

### 3. ❌ Lỗi Cache
**Triệu chứng:** View hoặc config bị cache cũ

**Đã fix:**
```bash
php artisan optimize:clear
```

### 4. ❌ Lỗi Port 8000 đang được sử dụng
**Triệu chứng:** `Failed to listen on 127.0.0.1:8000 (reason: Address already in use)`

**Đã fix:**
```bash
kill -9 $(lsof -ti:8000)
```

### 5. ❌ Lỗi Permission
**Triệu chứng:** `Permission denied` khi ghi file

**Đã fix:**
```bash
chmod -R 775 storage bootstrap/cache
```

---

## 🚀 CÁCH CHẠY SERVER:

### Cách 1: Dùng script tự động (KHUYẾN NGHỊ)
```bash
bash start-server.sh
```

### Cách 2: Chạy thủ công
```bash
php artisan serve
```

---

## 📋 CHECKLIST TRƯỚC KHI CHẠY:

- [x] Database `techshop` đã tạo
- [x] User `laravel` có quyền truy cập
- [x] Đã chạy migrations
- [x] Storage đã link
- [x] Cache đã clear
- [x] Permission đúng (775)

---

## 🌐 TRUY CẬP WEBSITE:

- **Trang chủ:** http://127.0.0.1:8000
- **Admin Dashboard:** http://127.0.0.1:8000/admin/dashboard
- **Login:** http://127.0.0.1:8000/login
- **Register:** http://127.0.0.1:8000/register

---

## 👤 TÀI KHOẢN ADMIN MẶC ĐỊNH:

- **Email:** admin@techshop.com
- **Password:** password

---

## 🛠️ LỆNH HỮU ÍCH:

### Clear all cache:
```bash
php artisan optimize:clear
```

### Check database connection:
```bash
php artisan migrate:status
```

### View users:
```bash
bash check-users.sh
```

### Stop server:
```bash
kill -9 $(lsof -ti:8000)
```

### Restart server:
```bash
bash start-server.sh
```

---

## ❓ NẾU VẪN CÒN LỖI:

### Lỗi database:
1. Check .env file có đúng:
   - DB_USERNAME=laravel
   - DB_PASSWORD=laravel123
   - DB_DATABASE=techshop

2. Reset password database:
```bash
sudo mysql -e "SET PASSWORD FOR 'laravel'@'localhost' = PASSWORD('laravel123'); FLUSH PRIVILEGES;"
```

### Lỗi view:
```bash
php artisan view:clear
rm -rf storage/framework/views/*
```

### Lỗi route:
```bash
php artisan route:clear
php artisan route:cache
```

### Lỗi khác:
```bash
# Clear tất cả
php artisan optimize:clear

# Chạy lại migrations (CẨN THẬN: sẽ xóa data)
php artisan migrate:fresh --seed
```

---

## 📞 LOGS:

Xem log lỗi tại: `storage/logs/laravel.log`

```bash
tail -f storage/logs/laravel.log
```

---

✨ **Server đang chạy thành công!** 
🎉 **Không còn lỗi!**
