<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->morphs('reference');
            $table->decimal('amount', 12, 2);
            $table->enum('type', ['IN', 'OUT']);
            $table->enum('method', ['Cash', 'Bank']);
            $table->string('receipt_no')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
