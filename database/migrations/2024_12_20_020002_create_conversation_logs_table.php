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
        Schema::create('conversation_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('whatsapp_user_id')->constrained()->onDelete('cascade');
            $table->string('message_id')->unique();
            $table->enum('direction', ['incoming', 'outgoing']);
            $table->text('message_content');
            $table->enum('message_type', ['text', 'image', 'document', 'audio', 'video', 'location', 'interactive'])->default('text');
            $table->json('metadata')->nullable();
            $table->enum('status', ['sent', 'delivered', 'read', 'failed'])->default('sent');
            $table->string('intent')->nullable();
            $table->timestamps();
            
            $table->index('whatsapp_user_id');
            $table->index('direction');
            $table->index('created_at');
            $table->index('intent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversation_logs');
    }
};
