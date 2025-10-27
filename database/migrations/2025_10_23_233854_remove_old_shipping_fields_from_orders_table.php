<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Xóa các cột cũ không còn dùng
            $table->dropColumn(['shipping_name', 'shipping_phone']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('shipping_name', 100)->after('status');
            $table->string('shipping_phone', 20)->after('shipping_name');
        });
    }
};
