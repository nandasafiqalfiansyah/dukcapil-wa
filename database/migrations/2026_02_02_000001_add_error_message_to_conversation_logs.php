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
        Schema::table('conversation_logs', function (Blueprint $table) {
            // Only add error_message if it doesn't exist
            if (!Schema::hasColumn('conversation_logs', 'error_message')) {
                $table->text('error_message')->nullable()->after('metadata');
            }
            // Only add bot_instance_id if it doesn't exist
            if (!Schema::hasColumn('conversation_logs', 'bot_instance_id')) {
                $table->foreignId('bot_instance_id')->nullable()->after('whatsapp_user_id')->constrained()->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('conversation_logs', function (Blueprint $table) {
            $table->dropForeign(['bot_instance_id']);
            $table->dropColumn(['error_message', 'bot_instance_id']);
        });
    }
};
