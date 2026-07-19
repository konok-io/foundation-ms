<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ledgers', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('voucher_no')->nullable();
            $table->string('voucher_type');
            $table->string('account_type');
            $table->unsignedBigInteger('account_id');
            $table->text('description')->nullable();
            $table->decimal('debit', 12, 2)->default(0);
            $table->decimal('credit', 12, 2)->default(0);
            $table->decimal('balance', 12, 2)->default(0);
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('date');
            $table->index('account_type');
            $table->index('account_id');
            $table->index(['account_type', 'account_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ledgers');
    }
};
