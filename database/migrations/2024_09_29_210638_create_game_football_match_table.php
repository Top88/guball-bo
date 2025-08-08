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
        Schema::create('game_football_match', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('league_id')->nullable();
            $table->unsignedBigInteger('home_team_id');
            $table->unsignedBigInteger('away_team_id');
            $table->string('rate_type');
            $table->float('home_team_rate')->nullable()->comment('อัตราต่อรองเจ้าบ้าน');
            $table->float('away_team_rate')->nullable()->comment('อัตราต่อรองทีมเยือน');
            $table->dateTime('match_date');
            $table->string('status');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_football_match');
    }
};
