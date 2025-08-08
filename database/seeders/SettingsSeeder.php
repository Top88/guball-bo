<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'web_name',
                'value' => 'GUBALL',
            ],
            [
                'key' => 'web_icon',
                'value' => null,
            ],
        ];

        Setting::insert($settings);
    }
}
