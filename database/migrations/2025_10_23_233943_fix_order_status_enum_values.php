<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // MySQL không hỗ trợ ALTER COLUMN cho ENUM trực tiếp, phải dùng raw SQL
        DB::statement("ALTER TABLE `orders` MODIFY COLUMN `status` ENUM('pending', 'confirmed', 'shipped', 'completed', 'cancelled') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE `orders` MODIFY COLUMN `status` ENUM('pending', 'processing', 'shipping', 'completed', 'cancelled') NOT NULL DEFAULT 'pending'");
    }
};
