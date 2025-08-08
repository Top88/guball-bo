<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('settings')) {
            Setting::create([
                'key' => 'win_get_coin',
                'value' => '1.5',
            ]);
            Setting::create([
                'key' => 'win_half_get_coin',
                'value' => '1.0',
            ]);
            Setting::create([
                'key' => 'draw_get_coin',
                'value' => '0.5',
            ]);
            Setting::create([
                'key' => 'lose_get_coin',
                'value' => '0.0',
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('settings')) {
            Setting::whereIn('key', ['win_get_coin', 'win_half_get_coin', 'draw_get_coin', 'lose_get_coin'])->delete();
        }
    }
};
