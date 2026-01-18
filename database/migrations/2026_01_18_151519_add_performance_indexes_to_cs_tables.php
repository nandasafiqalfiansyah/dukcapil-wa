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
        Schema::table('cs_training_data', function (Blueprint $table) {
            // Index untuk query aktif dengan priority
            $table->index(['is_active', 'priority'], 'cs_training_active_priority_idx');
            
            // Index untuk intent lookup
            $table->index('intent', 'cs_training_intent_idx');
        });

        Schema::table('auto_reply_configs', function (Blueprint $table) {
            // Index untuk query aktif dengan priority
            $table->index(['is_active', 'priority'], 'auto_reply_active_priority_idx');
            
            // Index untuk trigger lookup
            $table->index('trigger', 'auto_reply_trigger_idx');
        });

        Schema::table('chat_sessions', function (Blueprint $table) {
            // Index untuk session lookup dengan user
            $table->index(['id', 'user_id'], 'chat_sessions_id_user_idx');
        });

        Schema::table('chat_messages', function (Blueprint $table) {
            // Index untuk session messages dengan ordering
            $table->index(['chat_session_id', 'created_at'], 'chat_messages_session_created_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cs_training_data', function (Blueprint $table) {
            $table->dropIndex('cs_training_active_priority_idx');
            $table->dropIndex('cs_training_intent_idx');
        });

        Schema::table('auto_reply_configs', function (Blueprint $table) {
            $table->dropIndex('auto_reply_active_priority_idx');
            $table->dropIndex('auto_reply_trigger_idx');
        });

        Schema::table('chat_sessions', function (Blueprint $table) {
            $table->dropIndex('chat_sessions_id_user_idx');
        });

        Schema::table('chat_messages', function (Blueprint $table) {
            $table->dropIndex('chat_messages_session_created_idx');
        });
    }
};
