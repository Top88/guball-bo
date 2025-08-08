<?php

namespace App\Domain\Coin;

enum SilverCoinHistroryAction: string
{
    case CREATE_USER = 'create_user';
    case UPDATE_USER = 'update_user';
    case MANUAL_INCREMENT = 'manual_increase';
    case MANUAL_DECREASE = 'manual_decrease';
    case WIN_FOOTBALL_PREDICTION = 'win_football_prediction';
    case LOSE_FOOTBALL_PREDICTION = 'lose_football_prediction';
    case DRAW_FOOTBALL_PREDICTION = 'draw_football_prediction';
    case FOOTBALL_PREDICTION = 'football_prediction';
    case EXCHANGE_FROM_POINT = 'exchange_from_point';
    case DAILY_CHECK_IN = 'daily_check_in';
    case VIEW_FOOTBALL_PREDICTION = 'view_football_prediction';
    case ADMIN_RESET = 'admin_reset';
}
