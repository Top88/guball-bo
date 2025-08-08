<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class MaxPredictionSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::create([
            'key' => 'max_prediction_per_time',
            'value' => '3',
        ]);
        Setting::create([
            'key' => 'max_prediction_per_day',
            'value' => '3',
        ]);
    }
}
