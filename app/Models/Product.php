<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'quantity',
        'cost_acquisition',
        'sale_value',
        'minimum_quantity',
        'product_image',
        'barcode',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
