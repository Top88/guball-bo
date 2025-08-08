<?php

namespace App\Console\Commands;

use App\Models\Setting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class InitialSettings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:initial-settings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load website setting from database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $allSetting = Cache::rememberForever('settings', function () {
            return Setting::all()->pluck('value', 'key');
        });
        config(['settings' => $allSetting]);
        Artisan::call('config:cache');
        $this->info('Settings loaded successfully');
    }
}
