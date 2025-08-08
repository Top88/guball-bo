<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SettingsSeeder::class,
            RolePermissionSeeder::class,
            PermissionsSeeder::class,
            SuperAdminSeeder::class,
            PredictionCostSettingSeeder::class,
            MaxPredictionSettingSeeder::class,
            // TestSeeder::class,
        ]);
    }
}
