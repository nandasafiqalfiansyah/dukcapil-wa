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
            $table->foreignId('bot_instance_id')->nullable()->after('id')->constrained('bot_instances')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('conversation_logs', function (Blueprint $table) {
            $table->dropForeign(['bot_instance_id']);
            $table->dropColumn('bot_instance_id');
        });
    }
};
