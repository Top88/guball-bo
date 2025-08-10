<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserViewPrediction extends Model
{
    use HasFactory;

    // ตารางใน DB ของคุณชื่อ user_view_prediction
    protected $table = 'user_view_prediction';

    protected $fillable = [
        'asking_user_id',
        'target_user_id',
        'type',         // ✅ แยกสิทธิ์ตามประเภท single | step
        'expired_date',
    ];

    public function askingUser()
    {
        return $this->belongsTo(User::class, 'asking_user_id');
    }

    public function targetUser()
    {
        return $this->belongsTo(User::class, 'target_user_id');
    }
}
