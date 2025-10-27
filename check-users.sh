#!/bin/bash
# Script để kiểm tra users trong database TechShop

echo "=================================================="
echo "  KIỂM TRA USERS TRONG DATABASE TECHSHOP"
echo "=================================================="
echo ""

# Đếm tổng số users
echo "📊 TỔNG SỐ USERS:"
mysql -u laravel -plaravel123 techshop -e "SELECT COUNT(*) as total_users FROM users;" 2>/dev/null

echo ""
echo "👥 DANH SÁCH USERS (10 mới nhất):"
mysql -u laravel -plaravel123 techshop -e "SELECT id, name, email, role, created_at FROM users ORDER BY created_at DESC LIMIT 10;" 2>/dev/null

echo ""
echo "👤 USERS THEO VAI TRÒ:"
mysql -u laravel -plaravel123 techshop -e "SELECT role, COUNT(*) as count FROM users GROUP BY role;" 2>/dev/null

echo ""
echo "=================================================="
