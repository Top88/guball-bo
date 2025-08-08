<?php

namespace App\Events;

use App\Domain\Prediction\PredictionCollection;
use App\Domain\Prediction\PredictionInterface;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MatchPrediction implements PredictionInterface
{
    use Dispatchable, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(private User $user, private PredictionCollection $predictionCollection)
    {
        //
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getPredictionCollection(): PredictionCollection
    {
        return $this->predictionCollection;
    }
}
