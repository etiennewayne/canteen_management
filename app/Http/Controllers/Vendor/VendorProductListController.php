<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\ProductList;
use Auth;

class VendorProductListController extends Controller
{
    //

    public function __construct(){
        $this->middleware('auth');
        $this->middleware('vendor');
    }

    public function index(){
        return view('vendor.vendor-product-list');
    }

    public function getProductLists(Request $req){
        //return ProductList::all();
        
        $user = Auth::user();
       
        $sort = explode('.', $req->sort_by);

        $data = ProductList::where('product', 'like', $req->product . '%')
            ->where('user_id', $user->user_id) //bogo ko!!
            ->orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);
        return $data;
    }

    public function show($id){
        $user = Auth::user();

        return ProductList::where('user_id', $user_id)
            ->where('product_list_id', $id)->first();
    }

    public function store(Request $req){

        $user = Auth::user();

        $validate = $req->validate([
            'product' => ['required']
        ]);

        ProductList::create([
            'user_id' => $user->user_id,
            'product' => $req->product
        ]);
        return response()->json([
            'status' => 'saved'
        ],200);
    }



    public function update(Request $req, $id){

    }
}
