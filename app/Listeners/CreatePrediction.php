<?php

namespace App\Listeners;

use App\Domain\Prediction\PredictionInterface;
use App\Models\GameFootballPrediction;

class CreatePrediction
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
        foreach ($event->getPredictionCollection()->toArray() as $value) {
            GameFootballPrediction::create($value);
        }
    }
}
