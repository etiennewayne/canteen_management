<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductList extends Model
{
    use HasFactory;

    protected $table = 'product_lists';
    protected $primaryKey = 'product_list_id';


    protected $fillable = ['user_id', 'product', 'product_img_path'];

}
