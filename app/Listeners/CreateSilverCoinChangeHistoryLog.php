<?php

namespace App\Listeners;

use App\Events\SilverCoinChangeHistoryEvent;
use App\Models\UserSilverCoinTransactionLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateSilverCoinChangeHistoryLog implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(SilverCoinChangeHistoryEvent $event): void
    {
        /**
         * @var UserSilverCoinTransactionLog $coinModel
         */
        $coinModel = $event->getTransactionLog();
        $coinModel->create();
    }
}
