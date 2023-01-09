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

        $prod = Product::find($req->product_id);
        $cur_qty = $prod->qty;


        if($cur_qty < $req->qty){
            return response()->json([
                'errors' => [
                    'stock_over' => ['Remaining quantity is not enough for the quantity of the order.']
                ]
            ], 422);
        }

        if($cur_qty <= 0){
            return response()->json([
                'errors' => [
                    'stock_out' => ['Out of stock.']
                ]
            ], 422);
        }


        $user =  Auth::user();
        $time = date('H:i:s');

        $addtime = $prod->time_consume.' minute';


        $data = ProductOrder::create([
            'user_id' => $req->owner_id,
            'customer_id' => $user->user_id, //serve as customer id
            'product_id' => $req->product_id,
            'price' => ($req->price * $req->qty),
            'qty' => $req->qty,
            'delivery_type' => $req->delivery_type,
            'date_order' =>date('Y-m-d'),
            'office' => $req->delivery_type == 'DELIVER' ? $req->office : '',
            'est_delivery' => date("H:i:s", strtotime($time . ' + ' . $addtime))
        ]);


        $prod->qty = $prod->qty - $req->qty;
        $prod->save();


        return response()->json([
            'status' => 'saved'
        ], 200);
    }
}
