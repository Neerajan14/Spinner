<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Add 'role' to allow assignment
    protected $fillable = [
        'name',
        'email',
        'password',
        'number',
        'interested',
        'address',
        'resume_file_name',
        'role', // important for HR vs employee
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Casts should be a property, not a method
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

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
        return $this->hasManyThrough(
            Prize::class,
            UserWin::class,
            'user_id', // Foreign key on UserWin table
            'id',      // Foreign key on Prize table
            'id',      // Local key on User table
            'prize_id' // Local key on UserWin table
        );
    }

    // Optional: helper to check role easily
    public function isHR(): bool
    {
        return $this->role === 'HR';
    }
}