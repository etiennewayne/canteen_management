<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class WelcomePageAllProductsController extends Controller
{
    //


    public function getProducts(Request $req){
        $sort = explode('.', $req->sort_by);
        
        // $data = Product::selectRaw('(select * from products_rating)')
        // ->where('product', 'like', $req->product . '%');
        // ->orderBy($sort[0], $sort[1])
        // ->paginate($req->perpage);

        $data = DB::table('products')
            ->select('product_id', 'store_id', 'product', 'product_price', 'product_img_path',
                DB::raw('(select sum(rating) from product_ratings where product_id = products.product_id) as total_rating'),
                DB::raw('(select count(*) from product_ratings where product_id = products.product_id) as total_raters'),
                DB::raw('((select total_rating) / (select total_raters)) as total_rates'),
            );
        return $data->where('product', 'like', $req->product . '%')
            ->orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);
            
    }
}
