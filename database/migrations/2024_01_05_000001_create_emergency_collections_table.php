<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('emergency_collections', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('title_bn')->nullable();
            $table->text('description')->nullable();
            $table->text('description_bn')->nullable();
            $table->enum('type', ['medical', 'natural_disaster', 'funeral', 'flood_relief', 'earthquake', 'fire_relief', 'education', 'other']);
            
            $table->decimal('target_amount', 12, 2)->default(0);
            $table->decimal('collected_amount', 12, 2)->default(0);
            $table->decimal('amount_per_member', 10, 2)->default(0);
            
            $table->date('start_date');
            $table->date('end_date')->nullable();
            
            $table->enum('status', ['draft', 'active', 'closed', 'cancelled'])->default('draft');
            
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            
            $table->timestamps();

            $table->index('status');
            $table->index('type');
            $table->index('start_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emergency_collections');
    }
};
