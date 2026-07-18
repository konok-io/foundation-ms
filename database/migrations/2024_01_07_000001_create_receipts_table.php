<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_no')->unique();
            
            $table->string('type');
            $table->foreignId('member_id')->nullable()->constrained()->nullOnDelete();
            
            $table->foreignId('payment_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedBigInteger('contribution_id')->nullable();
            $table->unsignedBigInteger('emergency_payment_id')->nullable();
            $table->unsignedBigInteger('donation_id')->nullable();
            
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('SAR');
            
            $table->string('payment_method');
            $table->timestamp('paid_at');
            
            $table->string('purpose')->nullable();
            $table->text('description')->nullable();
            
            $table->string('qr_code')->nullable();
            
            $table->boolean('is_printed')->default(false);
            $table->timestamp('printed_at')->nullable();
            
            $table->boolean('is_emailed')->default(false);
            $table->timestamp('emailed_at')->nullable();
            
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamps();

            $table->index('receipt_no');
            $table->index('type');
            $table->index('member_id');
            $table->index('paid_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('receipts');
    }
};
