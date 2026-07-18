<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('title_bn')->nullable();
            $table->longText('description')->nullable();
            $table->longText('description_bn')->nullable();
            $table->enum('activity_type', ['medical', 'education', 'disaster_relief', 'community', 'environmental', 'food', 'housing', 'employment', 'other']);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('location')->nullable();
            $table->string('location_bn')->nullable();
            $table->unsignedInteger('beneficiaries_count')->default(0);
            $table->unsignedInteger('volunteers_count')->default(0);
            $table->decimal('budget', 12, 2)->default(0);
            $table->enum('status', ['planned', 'in_progress', 'completed', 'cancelled'])->default('planned');
            $table->string('image')->nullable();
            $table->string('report')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('activity_type');
            $table->index('status');
            $table->index('start_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
