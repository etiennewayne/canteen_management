<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Product;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class VendorProductController extends Controller
{
    //

    public function __construct(){
        $this->middleware('auth');
        $this->middleware('vendor');
    }



    public function show($id){
        return Product::find($id);
    }

    public function getProducts(Request $req){
        //return $req;
        $sort = explode('.', $req->sort_by);

        $data = Product::where('product', 'like', $req->product . '%')
            ->where('store_id', $req->storeid)
            ->orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);

        return $data;
    }

    public function store(Request $req){
        //return $req;

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

        Product::create([
            'store_id' => $req->store_id,
            'product' => $req->product,
            'qty' => 0,
            'is_inv' => $req->is_inv,
            'product_price' => $req->product_price,
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

        $data = Product::find($id);
        $data->product = $req->product;
        $data->is_inv = $req->is_inv;
        $data->product_price = $req->product_price;

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
        $data = Product::find($id);
        Product::destroy($id);
        Storage::delete('public/products/' . $data->product_img_path);

        return response()->json([
            'status' => 'deleted'
        ]);
    }

}
