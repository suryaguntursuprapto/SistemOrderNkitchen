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
            $table->string('biteship_order_id')->nullable()->after('tracking_number');
            $table->string('biteship_waybill_id')->nullable()->after('biteship_order_id');
            $table->string('biteship_status')->nullable()->after('biteship_waybill_id');
            $table->text('biteship_label_url')->nullable()->after('biteship_status');
            $table->text('biteship_tracking_url')->nullable()->after('biteship_label_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'biteship_order_id',
                'biteship_waybill_id', 
                'biteship_status',
                'biteship_label_url',
                'biteship_tracking_url'
            ]);
        });
    }
};
