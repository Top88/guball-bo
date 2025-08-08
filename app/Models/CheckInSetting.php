<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckInSetting extends Model
{
    use HasFactory;

    protected $table = 'check_in_setting';

    protected $fillable = [
        'day',
        'reward_category',
        'reward_amount',
    ];
}
