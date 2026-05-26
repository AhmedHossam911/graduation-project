<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('membership_id')->nullable()->after('id')
                  ->constrained('memberships')->nullOnDelete();

            $table->string('category')->nullable()->after('type');
            $table->text('description')->nullable()->after('category');
            $table->string('attachment_path')->nullable()->after('receipt_no');

            $table->foreignId('created_by')->nullable()->after('attachment_path')
                  ->constrained('users')->nullOnDelete();

            $table->index(['type', 'created_at']);
            $table->index('category');
        });

        // Widen method from ENUM('Cash','Bank') to support all 5 payment methods
        DB::statement("ALTER TABLE transactions MODIFY COLUMN method VARCHAR(255) NOT NULL DEFAULT 'cash'");

        // Normalise any existing rows to the new lowercase keys
        DB::table('transactions')->where('method', 'Cash')->update(['method' => 'cash']);
        DB::table('transactions')->where('method', 'Bank')->update(['method' => 'bank_transfer']);
    }

    public function down(): void
    {
        // Restore old enum values
        DB::table('transactions')->where('method', 'cash')->update(['method' => 'Cash']);
        DB::table('transactions')->where('method', 'bank_transfer')->update(['method' => 'Bank']);
        DB::statement("ALTER TABLE transactions MODIFY COLUMN method ENUM('Cash','Bank') NOT NULL DEFAULT 'Cash'");

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropIndex(['type', 'created_at']);
            $table->dropIndex(['category']);
            $table->dropForeign(['membership_id']);
            $table->dropColumn(['membership_id', 'category', 'description', 'attachment_path', 'created_by']);
        });
    }
};
