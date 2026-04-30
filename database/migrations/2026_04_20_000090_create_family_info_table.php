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
            $table->string('spouse_name')->default('لا يوجد');
            $table->string('spouse_phone')->default('لا يوجد');
            $table->string('child_name')->default('لا يوجد');
            $table->string('spouse_workplace')->default('لا يوجد');
            $table->string('child_workplace')->default('لا يوجد');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('family_info');
    }
};
