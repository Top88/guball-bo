<?php

namespace App\Providers;

use App\Infrastructure\Relation\ModelMorphMap;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
        App::setLocale(config('app.locale'));
        try {
            if (Schema::hasTable('settings')) {
                $allSetting = Cache::rememberForever('settings', function () {
                    if (Schema::hasTable('settings')) {
                        return Setting::all()->pluck('value', 'key');
                    }

                    return [];
                });
                foreach ($allSetting as $key => $value) {
                    Config::set("settings.$key", $value);
                }
            }
        } catch (\Exception) {

        }
        Relation::enforceMorphMap(ModelMorphMap::allCasesInArray());
    }
}
