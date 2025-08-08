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
        Schema::create('exchange_credit', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('cost_type');
            $table->float('cost_amount');
            $table->float('credit_amount');
            $table->string('exchange_status');
            $table->string('updated_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exchange_credit');
    }
};
