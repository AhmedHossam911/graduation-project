<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('memberships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
            $table->string('membership_number')->unique();
            $table->enum('status', [
                'active',
                'pending_registration',
                'loaned',
                'pension_eligible',
                'withdrawn',
                'dismissed',
                'unpaid_leave',
                'membership_expired',
                'suspended'
            ])->default('pending_registration');
            $table->boolean('declaration_accepted')->default(false);
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('memberships');
    }
};
