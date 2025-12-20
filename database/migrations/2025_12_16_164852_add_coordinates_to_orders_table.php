<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Add coordinates columns for GoSend/Grab instant delivery
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('destination_latitude', 10, 7)->nullable()->after('destination_postal_code');
            $table->decimal('destination_longitude', 11, 7)->nullable()->after('destination_latitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['destination_latitude', 'destination_longitude']);
        });
    }
};
