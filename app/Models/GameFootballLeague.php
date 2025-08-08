<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GameFootballLeague extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'game_football_league';

    protected $fillable = [
        'name',
        'country',
        'logo',
        'flag',
        'description',
        'status',
    ];

    public function scopeSearch(Builder $builder, ?string $search): Builder
    {
        return $builder->when($search, function (Builder $builder, string $search) {
            $builder->where(function (Builder $builder) use ($search) {
                $builder
                    ->where('id', 'like', "%$search%")
                    ->orWhere('name', 'like', "%$search%")
                    ->orWhere('country', 'like', "%$search%")
                    ->orWhere('description', 'like', "%$search%")
                    ->orWhere('status', 'like', "%$search%");
            });
        });
    }
}
