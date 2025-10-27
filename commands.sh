#!/bin/bash
# Quick reference commands for TechShop

cat << "EOF"
╔══════════════════════════════════════════════════════════════╗
║           TECHSHOP - QUICK COMMANDS REFERENCE                ║
╚══════════════════════════════════════════════════════════════╝

🚀 SERVER MANAGEMENT
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
  bash start-server.sh          Start Laravel server
  bash check-system.sh          Check system status
  kill -9 $(lsof -ti:8000)     Stop server on port 8000
  php artisan serve             Manual server start

📊 DATABASE
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
  bash check-users.sh           View database users
  php artisan migrate           Run migrations
  php artisan migrate:status    Check migration status
  php artisan migrate:fresh     Reset database
  php artisan db:seed          Seed database

🧹 CACHE MANAGEMENT
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
  php artisan optimize:clear    Clear all caches
  php artisan cache:clear       Clear application cache
  php artisan config:clear      Clear config cache
  php artisan route:clear       Clear route cache
  php artisan view:clear        Clear compiled views

🔍 DEBUGGING
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
  php artisan route:list        List all routes
  php artisan tinker           Laravel REPL
  tail -f storage/logs/laravel.log    Watch logs
  php artisan about            Show Laravel info

🔧 DEVELOPMENT
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
  php artisan make:controller   Create controller
  php artisan make:model        Create model
  php artisan make:migration    Create migration
  php artisan make:seeder       Create seeder
  composer install              Install dependencies
  npm install                   Install Node packages
  npm run build                 Build assets

🌐 QUICK ACCESS
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
  Website:    http://127.0.0.1:8000
  Admin:      http://127.0.0.1:8000/admin/dashboard
  Login:      http://127.0.0.1:8000/login
  Register:   http://127.0.0.1:8000/register

🔐 DEFAULT CREDENTIALS
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
  Admin Email:     admin@techshop.com
  Admin Password:  password
  
  Customer Email:  customer@techshop.com
  Customer Pass:   password

📚 DOCUMENTATION
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
  INDEX.md              📋 Complete documentation index
  SYSTEM_READY.md       ✅ System ready status
  FIX_ERRORS.md         🔧 Error fixing guide
  USAGE_GUIDE.md        📖 Complete usage guide
  DATABASE_STRUCTURE.md 📊 Database design

💡 TIPS
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
  • Always run check-system.sh before starting
  • Clear cache after code changes
  • Backup database before migrations
  • Check logs when errors occur

🆘 COMMON FIXES
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
  Database error:
    sudo mysql -e "SET PASSWORD FOR 'laravel'@'localhost' = PASSWORD('laravel123');"
  
  Port in use:
    kill -9 $(lsof -ti:8000)
  
  Storage not linked:
    php artisan storage:link
  
  Permission issues:
    chmod -R 775 storage bootstrap/cache

╔══════════════════════════════════════════════════════════════╗
║  Need help? Read INDEX.md for complete documentation index  ║
╚══════════════════════════════════════════════════════════════╝
EOF
