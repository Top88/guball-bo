<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserFootballPredictionStatistics extends Model
{
    use HasFactory;

    protected $table = 'user_football_prediction_statistics';

    protected $fillable = [
        'user_id',
        'win',
        'win_half',
        'draw',
        'lose',
        'points',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
