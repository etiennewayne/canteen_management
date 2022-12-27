<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAdjustment extends Model
{
    use HasFactory;

    protected $table = 'product_adjustments';
    protected $primaryKey = 'product_adjustment_id';


    protected $fillable = ['user_id', 'product_id', 'adjustment_type',
     'qty', 'current_qty', 'remarks', 'date_adjusted'];


    public function user(){
        return $this->hasOne(User::class, 'user_id', 'user_id');
    }

    public function product(){
        return $this->hasOne(Product::class, 'product_id', 'product_id');
    }

    public function store(){
        return $this->hasOne(Product::class, 'product_id', 'product_id')
            ->with(['store']);
    }

}
