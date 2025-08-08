<?php

namespace App\Domain\Coin;

enum GoldCoinHistoryAction: string
{
    case CREATE_USER = 'create_user';
    case UPDATE_USER = 'update_user';
    case MANUAL_INCREASE = 'manual_increase';
    case MANUAL_DECREASE = 'manual_decrease';
    case WIN_FOOTBALL_PREDICTION = 'win_football_prediction';
    case LOSE_FOOTBALL_PREDICTION = 'lose_football_prediction';
    case DRAW_FOOTBALL_PREDICTION = 'draw_football_prediction';
    case FOOTBALL_PREDICTION = 'football_prediction';
    case EXCHANGE_TO_COIN = 'exchange_to_coin';
    case EXCHANGE_TO_CREDIT = 'exchange_to_credit';
}
