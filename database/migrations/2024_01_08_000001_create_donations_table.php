<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->string('donor_name');
            $table->string('donor_name_bn')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->foreignId('member_id')->nullable()->constrained()->nullOnDelete();
            
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('SAR');
            
            $table->string('purpose');
            $table->string('purpose_other')->nullable();
            
            $table->string('payment_method');
            $table->string('gateway')->nullable();
            $table->string('gateway_transaction_id')->nullable();
            $table->foreignId('payment_id')->nullable()->constrained()->nullOnDelete();
            
            $table->enum('status', ['pending', 'completed', 'refunded', 'cancelled'])->default('pending');
            
            $table->boolean('is_anonymous')->default(false);
            $table->text('message')->nullable();
            
            $table->timestamp('received_at')->nullable();
            $table->foreignId('received_by')->nullable()->constrained('users')->nullOnDelete();
            
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamps();

            $table->index('status');
            $table->index('purpose');
            $table->index('member_id');
            $table->index('received_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
