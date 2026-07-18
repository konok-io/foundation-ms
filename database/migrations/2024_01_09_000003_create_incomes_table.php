<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->string('voucher_no')->unique();
            $table->foreignId('category_id')->constrained('income_categories')->cascadeOnDelete();
            $table->foreignId('member_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('SAR');
            $table->string('payment_method');
            $table->string('received_from')->nullable();
            $table->text('description')->nullable();
            $table->date('date');
            $table->string('reference_no')->nullable();
            $table->string('attachment')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('voucher_no');
            $table->index('date');
            $table->index('category_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incomes');
    }
};
