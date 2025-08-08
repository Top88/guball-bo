<?php

namespace App\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property float $coins_silver
 * @property float $coins_gold
 * @property string $nick_name
 * @property string $id
 * @property GameFootballPrediction $gameFootBallPrediction
 * @property UserFootballPredictionStatistics[]|Collection $footballPredictionStatistic
 * @property string $status
 */
class User extends Authenticatable
{
    use HasFactory;
    use HasRoles;
    use HasUuids;
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'full_name',
        'nick_name',
        'email',
        'password',
        'phone',
        'status',
        'coins_silver',
        'coins_gold',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'coins_silver' => 'float',
            'coins_gold' => 'float',
        ];
    }

    public function scopeSearch(Builder $builder, ?string $search): Builder
    {
        return $builder->when($search, function (Builder $builder, string $search) {
            $builder->where(function (Builder $builder) use ($search) {
                $builder
                    ->where('id', 'like', "%$search%")
                    ->orWhere('full_name', 'like', "%$search%")
                    ->orWhere('nick_name', 'like', "%$search%")
                    ->orWhere('phone', 'like', "%$search%")
                    ->orWhere('status', 'like', "%$search%")
                    ->orWhere('coins_silver', 'like', "%$search%")
                    ->orWhere('coins_gold', 'like', "%$search%");
            });
        });
    }

    public function gameFootBallPrediction()
    {
        return $this->hasMany(GameFootballPrediction::class);
    }

    public function predicToday()
    {
        $now = Carbon::now();
        return $this->hasMany(GameFootballPrediction::class)
            ->whereBetween('created_at', [
                $now->startOfDay()->toDateTimeString(),
                $now->endOfDay()->toDateTimeString()
            ]);
    }

    public function askingViewPrediction()
    {
        return $this->hasMany(UserViewPrediction::class, 'asking_user_id');
    }

    public function targetViewPrediction()
    {
        return $this->hasMany(UserViewPrediction::class, 'target_user_id');
    }

    public function footballPredictionStatistic(): HasMany
    {
        return $this->hasMany(UserFootballPredictionStatistics::class, 'user_id');
    }
}
