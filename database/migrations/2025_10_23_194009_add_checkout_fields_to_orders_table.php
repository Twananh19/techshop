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
            // Add new fields only (không thay đổi cột có sẵn vì SQLite không hỗ trợ)
            $table->string('order_number', 50)->unique()->after('id');
            $table->string('customer_name', 100)->after('user_id');
            $table->string('customer_phone', 20)->after('customer_name');
            $table->string('customer_email', 100)->after('customer_phone');
            $table->string('shipping_city', 100)->after('shipping_address');
            $table->string('shipping_district', 100)->nullable()->after('shipping_city');
            $table->decimal('subtotal', 12, 2)->default(0)->after('shipping_district');
            $table->decimal('shipping_fee', 12, 2)->default(0)->after('subtotal');
            $table->string('payment_method', 50)->after('total_amount');
            $table->text('notes')->nullable()->after('payment_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'order_number',
                'customer_name',
                'customer_phone',
                'customer_email',
                'shipping_city',
                'shipping_district',
                'subtotal',
                'shipping_fee',
                'payment_method',
                'notes'
            ]);
        });
    }
};
