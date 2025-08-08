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
        Schema::create('check_in_setting', function (Blueprint $table) {
            $table->id();
            $table->integer('day');
            $table->string('reward_category');
            $table->double('reward_amount');
            $table->timestamps();
        });

        DB::table('check_in_setting')->insert([
            [
                'day' => 1,
                'reward_category' => 'silver_coin',
                'reward_amount' => 100,
            ],
            [
                'day' => 2,
                'reward_category' => 'silver_coin',
                'reward_amount' => 100,
            ],
            [
                'day' => 3,
                'reward_category' => 'silver_coin',
                'reward_amount' => 100,
            ],
            [
                'day' => 4,
                'reward_category' => 'silver_coin',
                'reward_amount' => 100,
            ],
            [
                'day' => 5,
                'reward_category' => 'silver_coin',
                'reward_amount' => 100,
            ],
            [
                'day' => 6,
                'reward_category' => 'silver_coin',
                'reward_amount' => 100,
            ],
            [
                'day' => 7,
                'reward_category' => 'silver_coin',
                'reward_amount' => 100,
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('check_in_setting');
    }
};
