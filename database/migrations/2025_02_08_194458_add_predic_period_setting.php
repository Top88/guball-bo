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
        $data = [
            [
                'key' => 'predic_time_period_start',
                'value' => '10:00',
            ],
            [
                'key' => 'predic_time_period_end',
                'value' => '17:00',
            ],
        ];
        foreach ($data as $value) {
            Setting::create($value);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Setting::whereIn('key', ['predic_time_period_start', 'predic_time_period_end'])->delete();
    }
};
