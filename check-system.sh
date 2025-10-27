#!/bin/bash
# Script kiểm tra trạng thái hệ thống TechShop

echo "╔════════════════════════════════════════════════╗"
echo "║   KIỂM TRA TRẠNG THÁI HỆ THỐNG TECHSHOP       ║"
echo "╚════════════════════════════════════════════════╝"
echo ""

cd "/home/twan/web advance/empty/techshop"

# 1. Check PHP Version
echo "📌 PHP Version:"
php -v | head -n 1
echo ""

# 2. Check Laravel Version
echo "📌 Laravel Version:"
php artisan --version
echo ""

# 3. Check Database Connection
echo "📌 Database Connection:"
if mysql -u laravel -plaravel123 techshop -e "SELECT 1;" >/dev/null 2>&1; then
    echo "   ✅ Connected to MySQL/MariaDB"
    mysql -u laravel -plaravel123 techshop -e "SELECT COUNT(*) as total_users FROM users;" 2>/dev/null | tail -n 1 | awk '{print "   👥 Total Users: "$1}'
else
    echo "   ❌ KHÔNG THỂ KẾT NỐI DATABASE"
fi
echo ""

# 4. Check Migrations
echo "📌 Migrations Status:"
MIGRATIONS=$(php artisan migrate:status 2>/dev/null | grep -c "Ran")
echo "   ✅ $MIGRATIONS migrations đã chạy"
echo ""

# 5. Check Storage Link
echo "📌 Storage Link:"
if [ -L "public/storage" ]; then
    echo "   ✅ Storage đã được link"
else
    echo "   ❌ Storage chưa được link"
    echo "   Fix: php artisan storage:link"
fi
echo ""

# 6. Check Permissions
echo "📌 Permissions:"
if [ -w "storage" ] && [ -w "bootstrap/cache" ]; then
    echo "   ✅ Storage & bootstrap/cache có quyền ghi"
else
    echo "   ⚠️  Một số thư mục không có quyền ghi"
    echo "   Fix: chmod -R 775 storage bootstrap/cache"
fi
echo ""

# 7. Check Port 8000
echo "📌 Server Status:"
if lsof -ti:8000 >/dev/null 2>&1; then
    echo "   🟢 Server đang chạy trên port 8000"
    echo "   🌐 URL: http://127.0.0.1:8000"
else
    echo "   ⚪ Server chưa chạy"
    echo "   Start: bash start-server.sh"
fi
echo ""

# 8. Check .env
echo "📌 Environment Config:"
if [ -f ".env" ]; then
    echo "   ✅ File .env tồn tại"
    APP_ENV=$(grep "^APP_ENV=" .env | cut -d'=' -f2)
    APP_DEBUG=$(grep "^APP_DEBUG=" .env | cut -d'=' -f2)
    echo "   Environment: $APP_ENV"
    echo "   Debug Mode: $APP_DEBUG"
else
    echo "   ❌ File .env không tồn tại"
fi
echo ""

# 9. Check Cache
echo "📌 Cache Status:"
if [ -d "bootstrap/cache/packages.php" ] || [ -d "bootstrap/cache/config.php" ]; then
    echo "   ⚠️  Cache đang tồn tại"
    echo "   Clear: php artisan optimize:clear"
else
    echo "   ✅ Không có cache"
fi
echo ""

# 10. Summary
echo "╔════════════════════════════════════════════════╗"
echo "║              TỔNG KẾT KIỂM TRA                 ║"
echo "╚════════════════════════════════════════════════╝"
echo ""

ISSUES=0

# Count issues
if ! mysql -u laravel -plaravel123 techshop -e "SELECT 1;" >/dev/null 2>&1; then
    ((ISSUES++))
fi

if [ ! -L "public/storage" ]; then
    ((ISSUES++))
fi

if [ ! -w "storage" ] || [ ! -w "bootstrap/cache" ]; then
    ((ISSUES++))
fi

if [ ! -f ".env" ]; then
    ((ISSUES++))
fi

if [ $ISSUES -eq 0 ]; then
    echo "✅ Hệ thống hoạt động tốt!"
    echo "🚀 Sẵn sàng chạy server!"
    echo ""
    echo "Chạy lệnh: bash start-server.sh"
else
    echo "⚠️  Phát hiện $ISSUES vấn đề"
    echo "📖 Xem hướng dẫn fix tại: FIX_ERRORS.md"
fi

echo ""
