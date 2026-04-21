<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('membership_id')->constrained('memberships')->cascadeOnDelete();
            $table->decimal('total_amount', 10, 2);
            $table->integer('months');
            $table->decimal('installment_amount', 10, 2);
            $table->enum('status', ['pending', 'approved', 'active', 'completed', 'rejected'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
