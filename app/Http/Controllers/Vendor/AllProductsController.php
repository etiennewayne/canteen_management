<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Support\Facades\Auth;

class AllProductsController extends Controller
{
    //

    public function __construct(){
        $this->middleware('auth');
    }



    public function index(){
        $user = Auth::user();
        $stores = Store::where('user_id', $user->user_id)->get();

        return view('vendor.all-products')
            ->with('stores', $stores);
    }


    public function show($id){
        return Product::find($id);
    }

    public function getAllProducts(Request $req){
        $sort = explode('.', $req->sort_by);

        $data = Product::with(['store'])
            ->where('product', 'like', $req->product . '%')
            ->whereHas('store', function($q) use ($req){
                $q->where('store', 'like', $req->store . '%');
            })
            ->orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);

        return $data;
    }
}