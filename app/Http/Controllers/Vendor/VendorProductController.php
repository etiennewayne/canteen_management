<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class VendorProductController extends Controller
{
    //

    public function __construct(){
        $this->middleware('auth');
        $this->middleware('vendor');
    }

    public function store(Request $req, $storeId){
        $validat = $req->validate([
            'product' => ['required', 'unique:products']
        ]);

        Product::create([
            'store_id' => $storeId,
            'product' => $req->product,
            'qty' => $req->qty,
            'price' => $req->price,
        ]);

        return response()->json([
            'status' => 'saved'
        ],200);
    }

    public function update(Request $req, $id){

    }

}
