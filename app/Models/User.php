<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'cardgame_identifiers',
        'birth_date',
        'balance',
        'admin_level',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'cardgame_identifiers' => 'array', // Cast to array
        'birth_date' => 'date',
        'balance' => 'decimal:2',
    ];

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function transactions()
    {
        return $this->hasManyThrough(Transaction::class, Cart::class, 'user_id', 'cart_id');
    }
}
