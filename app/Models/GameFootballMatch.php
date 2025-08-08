<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class GameFootballMatch extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'game_football_match';

    protected $fillable = [
        'league_id',
        'home_team_id',
        'away_team_id',
        'rate_type',
        'home_team_rate',
        'away_team_rate',
        'match_date',
        'status',
        'result',
        'rate',
        'favorite_team',
    ];

    public function league(): BelongsTo
    {
        return $this->belongsTo(GameFootballLeague::class, 'league_id');
    }

    public function homeTeam(): BelongsTo
    {
        return $this->belongsTo(GameFootballTeam::class, 'home_team_id');
    }

    public function awayTeam(): BelongsTo
    {
        return $this->belongsTo(GameFootballTeam::class, 'away_team_id');
    }

    public function scopeSearch(Builder $builder, ?string $search): Builder
    {
        return $builder->when($search, function (Builder $builder, string $search) {
            $builder->where(function (Builder $builder) use ($search) {
                $builder
                    ->where('id', 'like', "%$search%")
                    ->orWhereHas('league', function (Builder $query) use ($search) {
                        $query->where('name', 'like', "%$search%");
                    })
                    ->orWhereHas('homeTeam', function (Builder $query) use ($search) {
                        $query->where('name', 'like', "%$search%");
                    })
                    ->orWhereHas('awayTeam', function (Builder $query) use ($search) {
                        $query->where('name', 'like', "%$search%");
                    })
                    ->orWhere('rate_type', 'like', "%$search%")
                    ->orWhere('home_team_rate', 'like', "%$search%")
                    ->orWhere('away_team_rate', 'like', "%$search%")
                    ->orWhere('match_date', 'like', "%$search%")
                    ->orWhere('status', 'like', "%$search%")
                    ->orWhere('result', 'like', "%$search%");
            });
        });
    }

    public function prediction(): HasMany
    {
        return $this->hasMany(GameFootballPrediction::class, 'match_id');
    }

    public function result(): HasOne
    {
        return $this->hasOne(GameFootballMatchResult::class, 'match_id');
    }
}
