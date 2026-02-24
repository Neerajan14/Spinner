<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prize extends Model
{
    protected $fillable = [
        'label',
        'weight',
        'price',
        'active',
    ];

    public function wins()
    {
        return $this->hasMany(UserWin::class, 'prize_id');
    }
}