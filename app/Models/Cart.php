<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'carts';
    protected $primaryKey = 'cart_id';


    protected $fillable = ['user_id', 'product_id', 'qty', 'price', 'date_added'];


    public function product(){
        return $this->hasOne(Product::class, 'product_id', 'product_id')
            ->with(['store']);
    }

    public function user(){
        return $this->hasOne(User::class, 'user_id', 'user_id');
    }




}
