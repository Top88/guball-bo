<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameFootballMatchResult extends Model
{
    use HasFactory;

    protected $table = 'game_football_match_result';

    protected $fillable = [
        'match_id',
        'home_team_goal',
        'away_team_goal',
        'team_match_result',
        'team_win_id',
        'game_time_minute',
        'win_type',
    ];

    public function match()
    {
        return $this->belongsTo(GameFootballMatch::class, 'match_id');
    }
}
