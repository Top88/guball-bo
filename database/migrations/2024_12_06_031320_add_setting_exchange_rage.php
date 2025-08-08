<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Setting::create([
            'key' => 'cost_for_point_to_coin',
            'value' => '3',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Setting::where('key', 'cost_for_point_to_coin')->delete();
    }
};
