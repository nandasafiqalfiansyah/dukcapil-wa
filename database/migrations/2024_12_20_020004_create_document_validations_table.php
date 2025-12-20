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
        Schema::create('document_validations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_request_id')->constrained()->onDelete('cascade');
            $table->foreignId('validated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('document_type');
            $table->string('file_path')->nullable();
            $table->string('original_filename')->nullable();
            $table->enum('validation_status', ['pending', 'valid', 'invalid', 'needs_review'])->default('pending');
            $table->json('validation_results')->nullable();
            $table->text('validation_notes')->nullable();
            $table->timestamp('validated_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index('service_request_id');
            $table->index('validated_by');
            $table->index('validation_status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_validations');
    }
};
