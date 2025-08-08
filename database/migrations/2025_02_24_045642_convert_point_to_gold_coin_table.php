<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::rename('user_point_transaction_log', 'user_gold_coin_transaction_log');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('user_gold_coin_transaction_log', 'user_point_transaction_log');
    }
};
