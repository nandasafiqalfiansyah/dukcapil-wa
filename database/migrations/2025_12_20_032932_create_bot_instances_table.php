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
        Schema::create('bot_instances', function (Blueprint $table) {
            $table->id();
            $table->string('bot_id')->unique();
            $table->string('name');
            $table->enum('status', ['not_initialized', 'initializing', 'qr_generated', 'authenticated', 'connected', 'disconnected', 'auth_failed'])->default('not_initialized');
            $table->string('phone_number')->nullable();
            $table->string('platform')->nullable();
            $table->text('qr_code')->nullable();
            $table->timestamp('last_connected_at')->nullable();
            $table->timestamp('last_disconnected_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bot_instances');
    }
};
