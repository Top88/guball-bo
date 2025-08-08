<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Setting::query()->create([
            'key' => 'show_rank_week_day',
            'value' => '0',
            'editable' => 1,
            'value_type' => 'integer'
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Setting::query()->where('key', 'show_rank_week_day')->delete();
    }
};
