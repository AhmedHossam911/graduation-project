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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
            $table->string('payable_type');
            $table->unsignedBigInteger('payable_id');
            $table->decimal('amount', 12, 2);
            $table->enum('payment_method', ['bank_transfer', 'cash', 'cheque']);
            $table->string('reference_code')->nullable();
            $table->string('receipt_number')->nullable();
            $table->string('bank_statement_path')->nullable();
            $table->enum('status', ['pending', 'verified', 'rejected']);
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->date('verification_date')->nullable();
            $table->text('notes')->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            
            $table->index(['payable_type', 'payable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
