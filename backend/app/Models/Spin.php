<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Spin extends Model
{
    protected $fillable = [
        'user_id',
        'prize_id',
        'user_ip',
        'user_name',
        'user_email',
        'user_number',
        'user_address',
    ];
}