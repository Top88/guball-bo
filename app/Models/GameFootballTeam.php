<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class GameFootballTeam extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'game_football_team';

    protected $fillable = [
        'name',
        'league_id',
    ];

    public function league(): BelongsTo
    {
        return $this->belongsTo(GameFootballLeague::class, 'league_id');
    }

    public function prediction(): HasMany
    {
        return $this->hasMany(GameFootballPrediction::class, 'selected_team_id');
    }

    public function scopeSearch(Builder $builder, ?string $search): Builder
    {
        return $builder->when($search, function (Builder $builder, string $search) {
            $builder->where(function (Builder $builder) use ($search) {
                $builder
                    ->where('id', 'like', "%$search%")
                    ->orWhere('name', 'like', "%$search%")
                    ->orWhereHas('league', function (Builder $query) use ($search) {
                        $query->where('name', 'like', "%$search%");
                    });
            });
        });
    }
}
