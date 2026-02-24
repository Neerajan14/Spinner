<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserWin extends Model
{
    protected $fillable = ['user_id', 'prize_id'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function prize()
    {
        return $this->belongsTo(Prize::class);
    }
}