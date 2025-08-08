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
        Schema::create('user_football_prediction_statistics', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->integer('win')->default(0);
            $table->integer('win_half')->default(0);
            $table->integer('draw')->default(0);
            $table->integer('lose')->default(0);
            $table->float('points')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_football_prediction_statistics');
    }
};
