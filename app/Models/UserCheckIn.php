<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCheckIn extends Model
{
    use HasFactory;

    protected $table = 'user_check_in';

    protected $fillable = [
        'user_id',
        'checked_date',
    ];
}
