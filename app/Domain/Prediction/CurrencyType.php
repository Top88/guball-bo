<?php

namespace App\Domain\Prediction;

enum CurrencyType: string
{
    case POINT = 'point';
    case COIN = 'coin';
    case SILVER_COIN = 'silver_coins';
    case GOLD_COIN = 'gold_coins';
}
