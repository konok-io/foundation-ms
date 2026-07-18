<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('title_bn')->nullable();
            $table->longText('description')->nullable();
            $table->longText('description_bn')->nullable();
            $table->enum('event_type', ['meeting', 'workshop', 'seminar', 'cultural', 'sports', 'religious', 'volunteer', 'fundraiser', 'other']);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('location')->nullable();
            $table->string('location_bn')->nullable();
            $table->unsignedInteger('max_attendees')->nullable();
            $table->boolean('registration_required')->default(false);
            $table->date('registration_deadline')->nullable();
            $table->string('banner')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('start_date');
            $table->index('event_type');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
