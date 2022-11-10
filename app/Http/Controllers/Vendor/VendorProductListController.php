<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\ProductList;
use Auth;


use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


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

        return ProductList::find($id);
    }

    public function store(Request $req){

        $user = Auth::user();

        $validate = $req->validate([
            'product' => ['required'],
            'product_img_path' => ['required', 'mimes:jpg,png,bmp'],
        ], $message = [
            'product_img_path.mimes' => 'Type of the file must be jpg, png or bmp.'
        ]);

        $imgPath = $req->file('product_img_path');
        if($imgPath){
            $pathFile = $imgPath->store('public/products'); //get path of the file
            $imgPath = explode('/', $pathFile); //split into array using /
        }

        ProductList::create([
            'user_id' => $user->user_id,
            'product' => $req->product,
            'product_img_path' => $imgPath[2] != null ? $imgPath[2]: '',
        ]);

        return response()->json([
            'status' => 'saved'
        ],200);
    }



    public function update(Request $req, $id){

        $imgPath = $req->file('product_img_path');
        $product_img_path = null;

        if($imgPath){
            $validate = $req->validate([
                'product' => ['required'],
                'product_img_path' => ['mimes:jpg,png,bmp'],
            ], $message = [
                'product_img_path.mimes' => 'Type of the file must be jpg, png or bmp.'
            ]);
        }else{
            $validate = $req->validate([
                'product' => ['required'],
            ]);
        }

        $data = ProductList::find($id);
        $data->product = $req->product;

        if($imgPath){
            //check the file and delete to update
            if(Storage::exists('public/products/' .$data->product_img_path)) {
                Storage::delete('public/products/' . $data->product_img_path);
            }
            $pathFile = $imgPath->store('public/products'); //get path of the file
            $product_img_path = explode('/', $pathFile); //split into array using /
            $data->product_img_path = $product_img_path[2];
        }

        $data->save();

        return response()->json([
            'status' => 'updated'
        ],200);
    }


    public function destroy($id){
        $data = ProductList::find($id);
        ProductList::destroy($id);
        Storage::delete('public/products/' . $data->product_img_path);

        return response()->json([
            'status' => 'deleted'
        ]);
    }
}
