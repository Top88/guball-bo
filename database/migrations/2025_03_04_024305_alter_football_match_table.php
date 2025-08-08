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
        Schema::table('game_football_match', function (Blueprint $table) {
            $table->string('rate')->nullable();
            $table->string('favorite_team')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('game_football_match', function (Blueprint $table) {
            $table->dropColumn('rate');
            $table->dropColumn('favorite_team');
        });
    }
};
