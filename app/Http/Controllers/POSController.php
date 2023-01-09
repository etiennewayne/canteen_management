<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductLog;
use App\Models\SalesOrder;
use App\Models\SalesOrderDetail;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class POSController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $user = Auth::user();

        $stores = Store::where('user_id', $user->user_id)
            ->get();
        return view('vendor.point-of-sale')
            ->with('stores', $stores);
    }

    public function store(Request $req){

        if(!$req->purchases){
            return response()->json([
                'errors' => [
                    'product' => 'Please select a product.'
                ]
            ], 422);
        }

        if(!$req->tendered_cash > 0 || $req->change < 0){
            return response()->json([
                'errors' => [
                    'tendered_cash' => 'Invalid cash input.'
                ]
            ], 422);
        }



        $user = Auth::user();
        //return $req;

        $sales = SalesOrder::create([
            'customer_name' => $req->customer_name,
            'order_type' => 'WALK IN',
            'date_order' => date('Y-m-d'),
            'tendered_cash' => $req->tendered_cash,
            'change' => $req->change,
        ]);

        foreach($req->purchases as $p){

            $prod = Product::find($p['product_id']);
            $cur_qty = $prod->qty;


            if($cur_qty < $p['qty']){
                return response()->json([
                    'errors' => [
                        'stock_over' => ['Remaining quantity is not enough for the quantity of the order.']
                    ]
                ], 422);
            }

            if($cur_qty <= 0){
                return response()->json([
                    'errors' => [
                        'stock_out' => ['Out of stock.']
                    ]
                ], 422);
            }


            SalesOrderDetail::create([
                'sales_order_id' => $sales->sales_order_id,
                'product_id' => $p['product_id'],
                'qty' => $p['qty'],
                'price' => ($p['qty'] * $p['price']),
            ]);


            $prod->qty = $prod->qty - $p['qty'];
            $prod->save();

            //logs every movement of the product
            ProductLog::create([
                'user_id' => $user->user_id,
                'product_id' => $p['product_id'],
                'current_qty' => $cur_qty,
                'qty' => $p['qty'],
                'remarks' => $p['qty'] . ' ' . $p['product'] . ' sold. Transaction from POS.'
            ]);
        }

        return response()->json([
            'status' => 'saved'
        ], 200);

    }
}
