<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'number',
        'interested',
        'address',
        'resume_file_name',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
        ];
    }

    public function spins()
    {
        return $this->hasMany(Spin::class);
    }

    public function wins()
    {
        return $this->hasMany(UserWin::class);
    }

    public function prizes()
    {
        return $this->hasManyThrough(Prize::class, UserWin::class, 'user_id', 'id', 'id', 'prize_id');
    }
}