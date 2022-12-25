<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;


class CartController extends Controller
{
    //

    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        return view('user.my-cart');
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


}
