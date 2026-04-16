<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
            $table->decimal('principal_amount', 12, 2);
            $table->decimal('interest_rate', 5, 2);
            $table->integer('duration_months');
            $table->decimal('monthly_installment', 12, 2);
            $table->enum('status', ['pending', 'approved', 'active', 'completed', 'defaulted', 'rejected']);
            $table->text('purpose')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->date('approval_date')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->decimal('remaining_balance', 12, 2);
            $table->foreignId('guarantor_id')->nullable()->constrained('members')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
