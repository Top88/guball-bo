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
        Schema::rename('user_coin_transaction_log', 'user_silver_coin_transaction_log');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('user_silver_coin_transaction_log', 'user_coin_transaction_log');
    }
};
