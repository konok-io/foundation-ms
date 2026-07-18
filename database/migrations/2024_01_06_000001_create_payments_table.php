<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_id')->unique();
            
            $table->foreignId('member_id')->nullable()->constrained()->nullOnDelete();
            
            $table->string('type');
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->string('reference_type')->nullable();
            
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('SAR');
            
            $table->string('payment_method');
            $table->string('gateway')->nullable();
            
            $table->string('gateway_transaction_id')->nullable();
            $table->json('gateway_response')->nullable();
            
            $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'refunded', 'partially_refunded', 'cancelled'])->default('pending');
            
            $table->timestamp('paid_at')->nullable();
            $table->string('failure_reason')->nullable();
            
            $table->string('refund_id')->nullable();
            $table->decimal('refund_amount', 12, 2)->nullable();
            $table->text('refund_reason')->nullable();
            $table->timestamp('refunded_at')->nullable();
            
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamps();

            $table->index('payment_id');
            $table->index('member_id');
            $table->index('type');
            $table->index('status');
            $table->index('gateway');
            $table->index(['reference_type', 'reference_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
