<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('emergency_collection_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('emergency_collection_id')->constrained()->cascadeOnDelete();
            $table->foreignId('member_id')->constrained()->cascadeOnDelete();
            
            $table->decimal('amount', 10, 2)->default(0);
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->decimal('due_amount', 10, 2)->default(0);
            
            $table->date('paid_date')->nullable();
            
            $table->enum('status', ['pending', 'partial', 'paid', 'waived', 'exempted'])->default('pending');
            $table->enum('payment_method', ['cash', 'bank_transfer', 'online', 'check', 'other'])->nullable();
            
            $table->string('transaction_id')->nullable();
            $table->string('receipt_no')->nullable();
            $table->text('notes')->nullable();
            
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('paid_by')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamps();

            $table->unique(['emergency_collection_id', 'member_id'], 'emergency_payments_unique');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emergency_collection_payments');
    }
};
