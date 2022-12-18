<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class WelcomePageAllProductsController extends Controller
{
    //


    public function getProducts(Request $req){
        $sort = explode('.', $req->sort_by);
        
        $data = Product::where('product', 'like', $req->product . '%')
        ->orderBy($sort[0], $sort[1])
        ->paginate($req->perpage);

        return $data;

    }
}
