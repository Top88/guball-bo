<?php

namespace App\Providers;

use App\Events\GoldCoinsChangeHistoryEvent;
use App\Events\MatchPrediction;
use App\Events\SilverCoinChangeHistoryEvent;
use App\Listeners\CreateGoldCoinChangeHistoryLog;
use App\Listeners\CreatePrediction;
use App\Listeners\CreateSilverCoinChangeHistoryLog;
use App\Listeners\PayForPrediction;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Event::makeListener([
            GoldCoinsChangeHistoryEvent::class, [
                CreateGoldCoinChangeHistoryLog::class,
            ],
            SilverCoinChangeHistoryEvent::class, [
                CreateSilverCoinChangeHistoryLog::class,
            ],
            MatchPrediction::class => [
                PayForPrediction::class,
                CreatePrediction::class,
            ],
        ]);
    }
}
