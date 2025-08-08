<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserGoldCoinTransactionLog extends Model
{
    use HasFactory;

    protected $table = 'user_gold_coin_transaction_log';

    protected $fillable = [
        'user_id',
        'action',
        'game_type',
        'game_id',
        'current',
        'change',
        'balance',
        'note',
        'updated_by',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
