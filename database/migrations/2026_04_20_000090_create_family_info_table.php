<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('family_info', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->unique()->constrained('members')->cascadeOnDelete();
            $table->integer('children_count')->default(0);
            $table->string('spouse_name')->nullable();
            $table->string('spouse_phone')->nullable();
            $table->string('child_name')->nullable();
            $table->string('spouse_workplace')->nullable();
            $table->string('child_workplace')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('family_info');
    }
};
