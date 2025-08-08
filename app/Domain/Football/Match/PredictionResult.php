<?php

namespace App\Domain\Football\Match;

use App\Traits\PredictionResultBadge;

enum PredictionResult: string
{
    use PredictionResultBadge;

    case WIN = 'win';
    case HALF = 'half';
    case LOSE = 'lose';
    case DRAW = 'draw';
}
