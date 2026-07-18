<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('member_id')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            
            // Personal Information
            $table->string('photo')->nullable();
            $table->string('name');
            $table->string('name_bn')->nullable();
            $table->string('father_name');
            $table->string('father_name_bn')->nullable();
            $table->string('mother_name');
            $table->string('mother_name_bn')->nullable();
            $table->date('date_of_birth');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->string('blood_group')->nullable();
            
            // Contact Information
            $table->string('mobile', 20);
            $table->string('email')->nullable();
            
            // ID Numbers
            $table->string('national_id', 20)->nullable();
            $table->string('passport_number', 20)->nullable();
            $table->string('iqama_number', 20)->nullable();
            
            // Professional Information
            $table->string('occupation')->nullable();
            $table->string('occupation_bn')->nullable();
            $table->string('designation')->nullable();
            $table->string('company_name')->nullable();
            
            // Addresses
            $table->text('present_address');
            $table->text('present_address_bn')->nullable();
            $table->text('permanent_address')->nullable();
            $table->text('permanent_address_bn')->nullable();
            
            // Emergency Contact
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone', 20)->nullable();
            $table->string('emergency_contact_relation')->nullable();
            
            // Membership Information
            $table->date('join_date');
            $table->enum('member_type', ['general', 'life', 'honorary', 'founder', 'associate'])->default('general');
            $table->boolean('status')->default(true);
            $table->enum('position', ['member', 'executive', 'secretary', 'vice_president', 'president', 'advisor'])->default('member');
            
            // Nominee Information
            $table->string('nominee_name')->nullable();
            $table->string('nominee_relation')->nullable();
            $table->string('nominee_phone', 20)->nullable();
            
            // QR Code
            $table->text('qr_code')->nullable();
            
            // Reference
            $table->string('referrer_member_id')->nullable();
            
            $table->timestamps();

            $table->index('member_id');
            $table->index(['status', 'member_type']);
            $table->index('join_date');
            $table->index('blood_group');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
