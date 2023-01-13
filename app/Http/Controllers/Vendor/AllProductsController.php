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
            ->addSelect(['total_rating' => function ($q){
                $q->selectRaw('sum(rating)')
                ->from('product_ratings')
                ->whereColumn('product_id', 'products.product_id');
            }])
            ->addSelect(['total_raters' => function ($q){
                $q->selectRaw('count(*)')
                ->from('product_ratings')
                ->whereColumn('product_id', 'products.product_id');
            }])
            ->selectRaw('(select(total_rating) / (select count(*) from product_ratings where product_id = products.product_id)) as product_total_rating')
            ->where('product', 'like', $req->product . '%')
            ->whereHas('store', function($q) use ($req){
                $q->where('store',  $req->store);
            })
            //->toSql();
            ->orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);

        return $data;
    }
}
