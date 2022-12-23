<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductRating;
use Auth;

class ProductRatingController extends Controller
{
    //

    public function __construct(){
        $this->middleware('auth');
    }

    public function store(Request $req){

        $user = Auth::user();
        
        $exist = ProductRating::where('user_id', $user->user_id)
            ->where('product_id', $req->product_id)
            ->exists();


        if($exist){
            return response()->json([
                'status' => 'exist'
            ], 422);
        }
        
        $data = ProductRating::create([
            'product_id' => $req->product_id,
            'user_id' => $user->user_id,
            'rating' => $req->rating
        ]);


        return response()->json([
            'status' => 'submitted'
        ], 200);
    }
}
