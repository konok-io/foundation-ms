<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cms_pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('title_bn')->nullable();
            $table->string('slug')->unique();
            $table->longText('content')->nullable();
            $table->longText('content_bn')->nullable();
            $table->text('excerpt')->nullable();
            $table->text('excerpt_bn')->nullable();
            $table->string('image')->nullable();
            $table->string('icon')->nullable();
            $table->integer('position')->default(0);
            $table->boolean('status')->default(true);
            $table->enum('page_type', ['about', 'mission', 'history', 'chairman', 'secretary', 'contact', 'footer', 'team', 'faq'])->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->timestamps();

            $table->index(['page_type', 'status']);
            $table->index('position');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_pages');
    }
};
