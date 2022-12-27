<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrder extends Model
{
    use HasFactory;


    protected $table = 'sales_orders';
    protected $primaryKey = 'sales_order_id';

    protected $fillable = ['customer_name', 'order_type', 'date_order',
            'tendered_cash', 'change'
        ];

    public function sales_order_details(){
        return $this->hasMany(SalesOrderDetail::class, 'sales_order_id', 'sales_order_id');
    }
}
