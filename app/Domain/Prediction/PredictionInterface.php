<?php

namespace App\Domain\Prediction;

use App\Models\User;

interface PredictionInterface
{
    public function getUser(): User;

    public function getPredictionCollection(): PredictionCollection;
}
