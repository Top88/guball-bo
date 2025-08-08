<?php

namespace App\Domain\Prediction;

use App\Infrastructure\Collection\TypeCollection;

class PredictionCollection extends TypeCollection
{
    protected string $type = Prediction::class;
}
