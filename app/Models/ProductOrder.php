<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOrder extends Model
{
    use HasFactory;

    protected $table = 'product_orders';
    protected $primaryKey = 'product_order_id';

    protected $fillable = ['user_id', 
        'customer_id', 
        'product_id', 'qty', 
        'price', 
        'delivery_type', 
        'date_order', 
        'office'
    ];

    public function customer(){
        return $this->hasOne(User::class, 'user_id', 'customer_id');
    }

    public function owner(){
        return $this->hasOne(User::class, 'user_id', 'user_id');
    }

    public function product(){
        return $this->hasOne(Product::class, 'product_id', 'product_id')
            ->with(['store']);
    }

}
