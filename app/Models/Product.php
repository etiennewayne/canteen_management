<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;


    protected $table = 'products';
    protected $primaryKey = 'product_id';


    protected $fillable = ['store_id', 'product', 'qty', 'is_inv', 'is_available', 'product_price', 'product_img_path', 'critical_level'];

    public function store(){
        return $this->belongsTo(Store::class, 'store_id', 'store_id');
    }


}
