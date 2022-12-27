<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductLog extends Model
{
    use HasFactory;


    protected $table = 'product_logs';
    protected $primaryKey = 'product_log_id';


    protected $fillable = ['user_id', 'product_id', 'current_qty', 'qty' ,'remarks'];

    public function product(){
        return $this->hasOne(Product::class, 'product_id', 'product_id');
    }

}
