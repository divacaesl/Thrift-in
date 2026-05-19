<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. API Keys Table
        Schema::create('api_keys', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('key')->unique();
            $table->text('permissions')->nullable(); // JSON array of permissions
            $table->timestamp('last_used_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Broadcast Messages Table
        Schema::create('broadcast_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('content');
            $table->enum('type', ['promo', 'announcement', 'maintenance', 'warning']);
            $table->enum('target_audience', ['all', 'buyers', 'sellers', 'admins']);
            $table->enum('channel', ['email', 'push', 'sms', 'in_app']);
            $table->enum('status', ['draft', 'scheduled', 'sent', 'failed'])->default('draft');
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('broadcast_messages');
        Schema::dropIfExists('api_keys');
    }
};
