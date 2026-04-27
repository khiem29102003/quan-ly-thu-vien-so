<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'role',
        'is_active',
        'wallet_balance',
        'outstanding_debt',
    ];
    
    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'wallet_balance' => 'float',
        'outstanding_debt' => 'float',
    ];

    public function borrows()
    {
        return $this->hasMany(Borrow::class);
    }

    public function reservations()
    {
        return $this->hasMany(BookReservation::class);
    }

    public function recommendations()
    {
        return $this->hasMany(Recommendation::class);
    }

    public function getActiveLoansCount()
    {
        return $this->borrows()->where('status', 'borrowed')->count();
    }
}
