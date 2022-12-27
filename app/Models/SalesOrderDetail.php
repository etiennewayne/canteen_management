<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class SalesOrderDetail extends Model
{
    use HasFactory;

    protected $table = 'sales_order_details';
    protected $primaryKey = 'sales_order_detail_id';


    protected $fillable = ['sales_order_id', 'product_id', 'qty', 'price', 'delivery_type', 'date_purchase'];


    public function product(){
        return $this->hasOne(Product::class, 'product_id', 'product_id');
    }

}
