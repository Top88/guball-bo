<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('user_gold_coin_transaction_log', function (Blueprint $table) {
            // เพิ่มคอลัมน์สำหรับกันซ้ำ
            if (!Schema::hasColumn('user_gold_coin_transaction_log', 'unique_key')) {
                $table->string('unique_key', 191)->nullable()->unique()->after('note');
            }
        });
    }

    public function down(): void
    {
        Schema::table('user_gold_coin_transaction_log', function (Blueprint $table) {
            if (Schema::hasColumn('user_gold_coin_transaction_log', 'unique_key')) {
                $table->dropUnique(['unique_key']);
                $table->dropColumn('unique_key');
            }
        });
    }
};
