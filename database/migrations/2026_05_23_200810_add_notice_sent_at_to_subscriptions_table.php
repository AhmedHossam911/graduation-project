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
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->timestamp('first_warning_sent_at')->nullable()->after('status');
            $table->timestamp('second_warning_sent_at')->nullable()->after('first_warning_sent_at');
            $table->timestamp('notice_sent_at')->nullable()->after('second_warning_sent_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn(['first_warning_sent_at', 'second_warning_sent_at', 'notice_sent_at']);
        });
    }
};
