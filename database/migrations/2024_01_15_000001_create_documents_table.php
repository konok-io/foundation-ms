<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->cascadeOnDelete();
            $table->enum('document_type', ['national_id', 'passport', 'iqama', 'birth_certificate', 'driving_license', 'certificate', 'academic_certificate', 'professional_certificate', 'other']);
            $table->string('title');
            $table->string('file_path');
            $table->string('file_name');
            $table->unsignedInteger('file_size')->nullable();
            $table->string('mime_type')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('document_type');
            $table->index('is_verified');
            $table->index('member_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
