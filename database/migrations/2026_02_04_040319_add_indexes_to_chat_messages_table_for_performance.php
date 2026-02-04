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
        Schema::table('chat_messages', function (Blueprint $table) {
            // Add composite index for the most common query pattern
            // Queries typically filter by role='bot' and intent IS NOT NULL, then order by created_at
            $table->index(['role', 'intent', 'created_at'], 'idx_chat_messages_nlp_logs');
            
            // Add index for confidence queries (used in statistics and filtering)
            $table->index('confidence', 'idx_chat_messages_confidence');
            
            // Add index for intent filtering
            $table->index('intent', 'idx_chat_messages_intent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->dropIndex('idx_chat_messages_nlp_logs');
            $table->dropIndex('idx_chat_messages_confidence');
            $table->dropIndex('idx_chat_messages_intent');
        });
    }
};
