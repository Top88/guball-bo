<?php

namespace App\Listeners;

use App\Domain\Prediction\PredictionInterface;
use App\Exceptions\CostNotEnoughException;

class PayForPrediction
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PredictionInterface $event): void
    {
        $cost = $event->getPredictionCollection()->count() * config('settings.prediction_cost');
        if ($event->getUser()->point < $cost) {
            throw new CostNotEnoughException;
        }
        $event->getUser()->decrement('points', $cost);

    }
}
