<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gallery_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('album_id')->constrained()->cascadeOnDelete();
            $table->string('title')->nullable();
            $table->string('image_path');
            $table->string('thumbnail_path')->nullable();
            $table->string('video_url')->nullable();
            $table->enum('type', ['photo', 'video']);
            $table->boolean('is_featured')->default(false);
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->index('type');
            $table->index('is_featured');
            $table->index('album_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gallery_images');
    }
};
