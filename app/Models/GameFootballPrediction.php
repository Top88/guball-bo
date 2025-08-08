<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $user_id
 * @property string $match_id
 * @property mixed $name
 * @property int $selected_team_id
 */
class GameFootballPrediction extends Model
{
    use HasFactory;

    protected $table = 'game_football_prediction';

    protected $fillable = [
        'user_id',
        'match_id',
        'selected_team_id',
        'cost_type',
        'cost_amount',
        'result',
        'gain_type',
        'gain_amount',
        'updated_by',
        'type', // ✅ เพิ่มตรงนี้
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function team()
    {
        return $this->belongsTo(GameFootballTeam::class, 'selected_team_id', 'id');
    }

    public function match()
    {
        return $this->belongsTo(GameFootballMatch::class, 'match_id', 'id');
    }
}
