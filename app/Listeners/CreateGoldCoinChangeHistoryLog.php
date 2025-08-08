<?php

namespace App\Listeners;

use App\Domain\Transactions\UserGoldCoinTransactionLog;
use App\Events\GoldCoinsChangeHistoryEvent;
use App\Infrastructure\Points\PointHistoryRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateGoldCoinChangeHistoryLog implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct(private readonly PointHistoryRepository $pointHistoryRepository)
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(GoldCoinsChangeHistoryEvent $event): void
    {
        $collection = $event->getTransactionLog();
        $this->pointHistoryRepository->create(
            $collection->getUserId(),
            $collection->getAction(),
            $collection->getCurrent(),
            $collection->getChange(),
            $collection->getBalance(),
            $collection->getUpdatedBy(),
            $collection->getGameType(),
            $collection->getGameId(),
            $collection->getNote()
        );
    }
}
