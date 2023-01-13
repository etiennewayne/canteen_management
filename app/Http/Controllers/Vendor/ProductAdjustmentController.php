<?php

namespace App\Http\Controllers\Vendor;


use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductLog;
use App\Models\ProductAdjustment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;



class ProductAdjustmentController extends Controller
{
    //

    public function __construct(){
        $this->middleware('auth');
    }


    public function index(){
        $user = Auth::user();
        $stores = Store::where('user_id', $user->user_id)
            ->get();
        return view('vendor.product-adjustment')
            ->with('stores', $stores);
    }

    public function getAdjustmentProducts(Request $req){

        $user = Auth::user();
        $store = Store::where('user_id', $user->user_id)->first();

        $from = date("Y-m-d", strtotime($req->from)); //convert to date format UNIX
        $to = date("Y-m-d", strtotime($req->to)); //convert to date format UNIX

//        $data = ProductAdjustment::with(['user', 'product', 'store'])
//            ->whereBetween('date_adjusted', [$from, $to])
//            ->whereHas('store', function($q) use ($req){
//                $q->where('store', 'like', $req->store);
//            })
//            ->get();

        $data = DB::table('product_adjustments as a')
            ->join('users as b', 'a.user_id', 'b.user_id')
            ->join('products as c', 'a.product_id', 'c.product_id')
            ->join('stores as d', 'c.store_id', 'd.store_id')
            ->select('a.product_adjustment_id', 'a.user_id', 'b.lname', 'b.fname', 'b.mname', 'b.sex',
                'a.product_id',  'a.current_qty', 'a.adjustment_type', 'a.remarks', 'a.date_adjusted',
                'a.qty as adjusted_qty',
                'c.product', 'c.product_price', 'c.qty',
                'd.store')
            ->whereBetween('a.date_adjusted', [$from, $to])
            ->where('d.store_id',  $store->store_id)
            ->orderBy('a.product_adjustment_id', 'desc')
            ->get();
        return $data;
    }

    public function adjustItem(Request $req, $id){
        $user = Auth::user();
        try{

            DB::transaction(function () use($req, $user, $id)  {
                $remark = '';
                $cur_qty = 0;

                $product = Product::find($id);
                $cur_qty = $product->qty;

                if($req->adjustment_type == 'ADD'){
                    $product->qty = $product->qty + $req->qty;
                    $remark = 'Add quantity of ' . $req->qty;
                }else{
                    $product->qty = $product->qty - $req->qty;
                    $remark = 'Subtract quantity of ' . $req->qty;
                }

                $product->save();

                ProductAdjustment::create([
                    'user_id' => $user->user_id,
                    'product_id' => $req->product_id,
                    'adjustment_type' => $req->adjustment_type,
                    'qty' => $req->qty,
                    'current_qty' => $cur_qty,
                    'remarks' => $req->remarks,
                    'date_adjusted' => date('Y-m-d')
                ]);

                ProductLog::create([
                    'user_id' => $user->user_id,
                    'product_id' => $req->product_id,
                    'qty' => $req->qty,
                    'remarks' => 'Adjustment of product ' . $req->product . '. ' . $remark .'. Adjusted by ' . $user->username
                ]);

            });

            return response()->json([
                'status' => 'adjusted'
            ], 200);

        } catch(\Exception $e){
            return $e->getMessage();
        }

    }
}
