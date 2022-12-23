<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductRating extends Model
{
    use HasFactory;

    protected $table = 'product_ratings';
    protected $primaryKey = 'product_rating_id';

    protected $fillable = ['user_id', 
        'product_id', 'rating'
    ];

    public function product(){
        return $this->hasOne(Product::class, 'product_id', 'product_id');
    }
}
