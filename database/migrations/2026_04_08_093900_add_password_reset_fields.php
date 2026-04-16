<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dateTime('locked_until')->nullable()->after('last_login_at');
        });

        Schema::table('otp_codes', function (Blueprint $table) {
            $table->unsignedTinyInteger('attempts')->default(0)->after('is_used');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('locked_until');
        });

        Schema::table('otp_codes', function (Blueprint $table) {
            $table->dropColumn('attempts');
        });
    }
};
