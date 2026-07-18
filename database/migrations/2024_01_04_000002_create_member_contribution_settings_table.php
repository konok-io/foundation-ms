<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('member_contribution_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->cascadeOnDelete();
            
            $table->decimal('monthly_amount', 10, 2)->default(100);
            $table->decimal('penalty_rate', 5, 2)->default(0);
            $table->integer('penalty_grace_days')->default(0);
            
            $table->boolean('auto_generate')->default(true);
            $table->boolean('is_active')->default(true);
            
            $table->date('effective_from')->nullable();
            $table->date('effective_to')->nullable();
            
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamps();

            $table->unique(['member_id', 'effective_from']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_contribution_settings');
    }
};
