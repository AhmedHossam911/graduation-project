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
        Schema::table('members', function (Blueprint $table) {
            $table->string('marital_status')->nullable()->after('address');
            $table->string('landline')->nullable()->after('phone');
        });

        Schema::table('employment_info', function (Blueprint $table) {
            $table->string('financial_category')->nullable()->after('job_title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn(['marital_status', 'landline']);
        });

        Schema::table('employment_info', function (Blueprint $table) {
            $table->dropColumn(['financial_category']);
        });
    }
};
