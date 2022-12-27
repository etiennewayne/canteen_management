<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Office;
use App\Models\Product;
use App\Models\ProductLog;
use App\Models\ProductOrder;
use Illuminate\Http\Request;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class CartController extends Controller
{
    //

    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        $offices = Office::orderBy('office',  'asc')->get();
        $role = Auth::user()->role;

        return view('user.my-cart')
            ->with('role', $role)
            ->with('offices', $offices);
    }

    public function getCountCartItems (){
        $user = Auth::user();
        return Cart::where('user_id', $user->user_id)->count();
    }

    public function getCartItems(){

        $user = Auth::user();
        $data = Cart::with(['product'])->where('user_id', $user->user_id)
            ->where('is_place_order', 0)
            ->get();
        return $data;
    }

    public function store(Request $req){

        $user = Auth::user();

        Cart::create([
            'user_id' => $user->user_id,
            'product_id' => $req->product_id,
            'qty' => $req->qty,
            'price' => $req->price,
            'date_added' => date('Y-m-d')
        ]);

        return response()->json([
            'status' => 'saved'
        ], 200);
    }

    public function placeCartOrder(Request $req){
        //return $req;

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

        try{
            DB::transaction(function () use($req)  {

                $user =  Auth::user();

                $cart = Cart::find($req->cart_id);
                $cart->is_place_order = 1;
                $cart->save();

                $data = ProductOrder::create([
                    'user_id' => $req->store_user_id, //serve as owner of the product
                    'customer_id' => $user->user_id, //serve as customer id
                    'product_id' => $req->product_id,
                    'price' => ($req->price * $req->qty),
                    'qty' => $req->qty,
                    'delivery_type' => $req->delivery_type,
                    'date_order' =>date('Y-m-d'),
                    'office' => $req->delivery_type == 'DELIVER' ? $req->office : ''
                ]);

                $prod = Product::find($req->product_id);
                $cur_qty = $prod->qty;
                $prod->qty = $prod->qty - $req->qty;
                $prod->save();

                ProductLog::create([
                    'user_id' => $user->user_id,
                    'product_id' => $req->product_id,
                    'current_qty' => $cur_qty,
                    'qty' => $req->qty,
                    'remarks' => 'Order ' . $req->product['product']  . ' was placed. Delivery type is .' . $req->delivery_type . '.' . $req->delivery_type == 'DELIVER' ? 'Deliver at ' . $req->office : ''
                ]);

            });

            return response()->json([
                'status' => 'saved'
            ], 200);

        } catch(\Exception $e){
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }

    }


}
