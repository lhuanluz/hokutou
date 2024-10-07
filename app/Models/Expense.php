<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'expense_date', 'description', 'amount', 'payment_method', 'installments', 'user_id'
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_expense');
    }
}
