<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_date',
        'amount',
        'transaction_type',
        'payment_method',
        'installments',
        'payment_fee',
        'cart_id',
        'observations',
    ];
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }
    public function expense()
    {
        return $this->belongsTo(Expense::class);
    }
}
