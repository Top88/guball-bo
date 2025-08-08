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
        Schema::create('game_football_prediction', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->integer('match_id');
            $table->integer('selected_team_id');
            $table->string('cost_type')->nullable();
            $table->float('cost_amount', 4)->nullable();
            $table->string('result')->nullable();
            $table->string('gain_type')->nullable();
            $table->float('gain_amount', 4)->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_football_prediction');
    }
};
