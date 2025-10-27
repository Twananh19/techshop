#!/bin/bash
# Script khởi động Laravel TechShop Server

echo "🚀 Starting TechShop Laravel Server..."
echo "========================================"

# Đổi đến thư mục project
cd "/home/twan/web advance/empty/techshop"

# Kiểm tra port 8000 có đang được sử dụng không
if lsof -ti:8000 > /dev/null 2>&1; then
    echo "⚠️  Port 8000 đang được sử dụng. Đang dừng process cũ..."
    kill -9 $(lsof -ti:8000) 2>/dev/null
    sleep 1
fi

# Clear cache
echo "🧹 Clearing cache..."
php artisan optimize:clear > /dev/null 2>&1

# Kiểm tra database connection
echo "🔌 Kiểm tra kết nối database..."
if mysql -u laravel -plaravel123 -e "USE techshop;" 2>/dev/null; then
    echo "✅ Database connection OK"
else
    echo "❌ Database connection FAILED"
    echo "   Đang fix database user..."
    sudo mysql -e "SET PASSWORD FOR 'laravel'@'localhost' = PASSWORD('laravel123'); FLUSH PRIVILEGES;" 2>/dev/null
    echo "✅ Database fixed"
fi

echo ""
echo "✨ Server đang chạy tại: http://127.0.0.1:8000"
echo "📊 Admin Dashboard: http://127.0.0.1:8000/admin/dashboard"
echo "🔐 Login Admin: admin@techshop.com / password"
echo ""
echo "Nhấn Ctrl+C để dừng server"
echo "========================================"
echo ""

# Start Laravel server
php artisan serve
