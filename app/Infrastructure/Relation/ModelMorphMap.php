<?php

namespace App\Infrastructure\Relation;

use App\Models\GameFootballLeague;
use App\Models\GameFootballMatch;
use App\Models\GameFootballPrediction;
use App\Models\GameFootballTeam;
use App\Models\Setting;
use App\Models\User;
use App\Models\UserGoldCoinTransactionLog;

enum ModelMorphMap: string
{
    case football_match = GameFootballMatch::class;
    case football_league = GameFootballLeague::class;
    case football_team = GameFootballTeam::class;
    case football_prediction = GameFootballPrediction::class;
    case user = User::class;
    case setting = Setting::class;
    case point_history = UserGoldCoinTransactionLog::class;

    public static function allCasesInArray()
    {
        $array = [];
        foreach (self::cases() as $case) {
            $array[$case->name] = $case->value;
        }

        return $array;
    }
}
