<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use Auth;


class CartController extends Controller
{
    //

    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        $role = Auth::user()->role;

        return view('user.my-cart')
            ->with('role', $role);
    }

    public function getCountCartItems (){
        $user = Auth::user();
        return Cart::where('user_id', $user->user_id)->count();
    }

    public function getCartItems(){

        $user = Auth::user();
        $data = Cart::with(['product'])->where('user_id', $user->user_id)
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
            
        $user =  Auth::user();

        $data = ProductOrder::create([
            'user_id' => $req->owner_id,
            'customer_id' => $user->user_id, //serve as customer id
            'product_id' => $req->product_id,
            'price' => ($req->price * $req->qty),
            'qty' => $req->qty,
            'delivery_type' => $req->delivery_type,
            'date_order' =>date('Y-m-d'),
            'office' => $req->delivery_type == 'DELIVER' ? $req->office : ''
        ]);

        return response()->json([
            'status' => 'saved'
        ], 200);
        
    }


}
