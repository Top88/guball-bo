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
        Schema::create('game_football_match_result', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('match_id');
            $table->integer('home_team_goal')->default(0);
            $table->integer('away_team_goal')->default(0);
            $table->string('team_match_result')->comment("['home','away','draw']");
            $table->bigInteger('team_win_id')->nullable();
            $table->integer('game_time_minute')->default('90');
            $table->string('win_type')->comment("['full', 'half']");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_football_match_result');
    }
};
