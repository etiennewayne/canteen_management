<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Auth;
use App\Models\ProductOrder;


class BuyNowController extends Controller
{
    //

    public function __construct(){
        $this->middleware('auth');
    }

    public function  index($id){
        $role = Auth::user()->role;

        $product = Product::with(['store'])
            ->where('product_id', $id)->first();
        return view('buy-now')
            ->with('product_id', $id)
            ->with('product', $product)
            ->with('role', $role);
    }


    public function store(Request $req){
       
        $req->validate([
            'qty' => ['min:1'],
            'delivery_type' => ['required'],
        ],[
            'qty.min' => 'Quantity must have atleast 1.'
        ]);

        if($req->delivery_type == 'DELIVER'){
            $req->validate([
                'qty' => ['min:1'],
                'delivery_type' => ['required'],
                'office' => ['required'],
            ],[
                'qty.min' => 'Quantity must have atleast 1.'
            ]);
        }
            

        return $req;

        $user =  Auth::user();

        $data = ProductOrder::create([
            'user_id' => $req->owner_id,
            'customer_id' => $user->user_id, //serve as customer id
            'prpoduct_id' => $req->product_id,
            'price' => $req->price,
            'qty' => $req->qty,
            'delivery_type' => $req->delivery_type,
            'office' => $req->delivery_type == 'DELIVER' ? $req->office : ''
        ]);

        return $req;
    }
}
