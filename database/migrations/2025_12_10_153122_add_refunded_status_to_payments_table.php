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
        // Modify enum to include refunded and expired status
        DB::statement("ALTER TABLE payments MODIFY COLUMN status ENUM('pending', 'confirmed', 'failed', 'cancelled', 'paid', 'settlement', 'capture', 'refunded', 'expired') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE payments MODIFY COLUMN status ENUM('pending', 'confirmed', 'failed', 'cancelled') DEFAULT 'pending'");
    }
};
