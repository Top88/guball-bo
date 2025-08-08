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
        if (Schema::hasColumn('game_football_match_result', 'win_type')) {
            Schema::table('game_football_match_result', function (Blueprint $table) {
                $table->string('win_type')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('game_football_match_result', 'win_type')) {
            Schema::table('game_football_match_result', function (Blueprint $table) {
                $table->string('win_type')->change();
            });
        }
    }
};
