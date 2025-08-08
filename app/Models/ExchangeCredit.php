<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExchangeCredit extends Model
{
    use HasFactory;

    protected $table = 'exchange_credit';
    protected $fillable = [
        'user_id',
        'cost_type',
        'cost_amount',
        'credit_amount',
        'exchange_status',
        'updated_by'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeFilter(Builder $query, array $filters)
    {
        if (isset($filters['search'])) {
            $query->orWhere('exchange_status', 'LIKE', '%' . $filters['search'] . '%');
            $query->whereHas('user', function (Builder $query) use ($filters) {
                $query->orWhere('id', 'LIKE', '%' . $filters['search'] . '%');
                $query->orWhere('full_name', 'LIKE', '%' . $filters['search'] . '%');
            });
        }
    }
}
