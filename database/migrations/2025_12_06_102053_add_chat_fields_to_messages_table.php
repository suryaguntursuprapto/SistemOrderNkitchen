<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            // Tambah conversation_id untuk grouping
            if (!Schema::hasColumn('messages', 'conversation_id')) {
                $table->unsignedBigInteger('conversation_id')->nullable()->after('id');
                $table->foreign('conversation_id')->references('id')->on('conversations')->onDelete('cascade');
            }
            
            // Sender type: user, admin, chatbot
            if (!Schema::hasColumn('messages', 'sender_type')) {
                $table->string('sender_type')->default('user')->after('user_id');
            }
            
            // Sender ID (admin_id jika dikirim admin)
            if (!Schema::hasColumn('messages', 'sender_id')) {
                $table->unsignedBigInteger('sender_id')->nullable()->after('sender_type');
            }
            
            // Status: sent, delivered, read
            if (!Schema::hasColumn('messages', 'message_status')) {
                $table->string('message_status')->default('sent')->after('message');
            }
        });
    }

    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign(['conversation_id']);
            $table->dropColumn(['conversation_id', 'sender_type', 'sender_id', 'message_status']);
        });
    }
};
