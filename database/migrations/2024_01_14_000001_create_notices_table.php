<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notices', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('title_bn')->nullable();
            $table->longText('content')->nullable();
            $table->longText('content_bn')->nullable();
            $table->enum('notice_type', ['general', 'meeting', 'emergency', 'event', 'holiday', 'member']);
            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal');
            $table->date('publish_date')->nullable();
            $table->date('expire_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('notice_type');
            $table->index('priority');
            $table->index('publish_date');
            $table->index('expire_date');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notices');
    }
};
