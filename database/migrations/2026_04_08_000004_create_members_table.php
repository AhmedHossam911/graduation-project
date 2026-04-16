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
            $table->foreignId('person_id')->unique()->constrained('persons')->cascadeOnDelete();
            $table->string('member_number')->unique();
            $table->enum('status', ['active', 'suspended', 'terminated', 'deceased'])->default('active');
            $table->date('join_date');
            $table->date('termination_date')->nullable();
            $table->text('termination_reason')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('member_divisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
            $table->foreignId('division_id')->constrained('divisions')->cascadeOnDelete();
            $table->unique(['member_id', 'division_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_divisions');
        Schema::dropIfExists('members');
    }
};
