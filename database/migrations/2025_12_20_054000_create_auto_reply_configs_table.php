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
        Schema::create('auto_reply_configs', function (Blueprint $table) {
            $table->id();
            $table->string('trigger')->unique()->comment('Keyword that triggers the auto-reply');
            $table->text('response')->comment('Response message to send');
            $table->boolean('is_active')->default(true)->comment('Whether this auto-reply is active');
            $table->integer('priority')->default(0)->comment('Priority for matching (higher = checked first)');
            $table->boolean('case_sensitive')->default(false)->comment('Whether trigger matching is case-sensitive');
            $table->timestamps();

            $table->index('is_active');
            $table->index('priority');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auto_reply_configs');
    }
};
