<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->string('blood_group')->nullable()->after('gender');
            $table->date('last_donation_date')->nullable()->after('join_date');
            $table->boolean('is_blood_donor')->default(false)->after('last_donation_date');
            $table->enum('donation_availability', ['available', 'unavailable', 'temporarily_unavailable'])->default('available')->after('is_blood_donor');
        });
    }

    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn(['blood_group', 'last_donation_date', 'is_blood_donor', 'donation_availability']);
        });
    }
};
