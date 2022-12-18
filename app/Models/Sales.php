<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Sale extends Model
{
    use HasFactory;

    protected $table = 'sales';
    protected $primaryKey = 'sales_id';


    protected $fillable = ['user_id', 'customer_id', 'product_id', 'qty', 'price', 'delivery_type', 'date_purchase'];

    public function customer(){
        return $this->hasOne(User::class, 'user_id', 'customer_id');
    }


    public function owner(){
        return $this->hasOne(User::class, 'user_id', 'user_id');
    }

    public function product(){
        return $this->hasOne(Product::class, 'product_id', 'product_id');
    }

}
