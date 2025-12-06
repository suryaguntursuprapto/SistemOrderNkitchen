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
            // Cek dan tambahkan kolom jika belum ada
            if (!Schema::hasColumn('orders', 'courier')) {
                $table->string('courier')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('orders', 'shipping_service')) {
                $table->string('shipping_service')->nullable()->after('courier');
            }
            if (!Schema::hasColumn('orders', 'city_id')) {
                $table->string('city_id')->nullable()->after('shipping_service');
            }
            if (!Schema::hasColumn('orders', 'total_weight')) {
                $table->integer('total_weight')->default(0)->after('shipping_cost');
            }
            if (!Schema::hasColumn('orders', 'recipient_name')) {
                $table->string('recipient_name')->nullable()->after('user_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['courier', 'shipping_service', 'city_id', 'total_weight', 'recipient_name']);
        });
    }
};
