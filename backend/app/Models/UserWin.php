<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserWin extends Model
{
    protected $fillable = [
        'user_id',
        'prize_id',
        'user_name',
        'user_email',
        'user_number',
        'user_interested',
        'user_address',
        'user_resume_file_name',
        'prize_label',
        'prize_weight',
        'prize_price',
        'prize_active',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function prize()
    {
        return $this->belongsTo(Prize::class);
    }
}