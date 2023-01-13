<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductLog;
use App\Models\ProductOrder;
use App\Models\SalesOrder;
use App\Models\SalesOrderDetail;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VendorMyOrderController extends Controller
{
    //

    public function __construct(){
        $this->middleware('auth');
        $this->middleware('vendor');
    }

    public function index()
    {
        $user = Auth::user();
        $stores = Store::where('user_id', $user->user_id)->get();

        return view('vendor.vendor-my-orders')
            ->with('stores', $stores);
    }

    public function getVendorMyOrders(Request $req)
    {
        $sort = explode('.', $req->sort_by);

        $user = Auth::user();
        $store = Store::where('user_id', $user->user_id)->first();

        $data = DB::table('product_orders as a')
            ->join('products as b', 'a.product_id', 'b.product_id')
            ->join('stores as c', 'b.store_id', 'c.store_id')
            ->select('a.*', 'b.*', 'c.*', 'a.qty as purchase_qty')
            ->where('b.store_id', $store->store_id)
            ->where('a.product_order_id', 'like',  $req->product_order_id . '%')
            ->orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);


        return $data;
    }

    public function show($id){
        return Store::find($id);
    }


    public function storeOrder(Request $req){
        //mini POS
        //return $req;

        if(!$req->tendered_cash > 0 || $req->change < 0){
            return response()->json([
                'errors' => [
                    'tendered_cash' => 'Invalid cash input.'
                ]
            ], 422);
        }


        $prod = Product::find($req->product_id);
        $cur_qty = $prod->qty;
//
//        if($cur_qty < $req->purchase_qty){
//            return response()->json([
//                'errors' => [
//                    'stock_over' => ['Remaining quantity is not enough for the quantity of the order.']
//                ]
//            ], 422);
//        }
//
//        if($cur_qty <= 0){
//            return response()->json([
//                'errors' => [
//                    'stock_out' => ['Out of stock.']
//                ]
//            ], 422);
//        }


        $user = Auth::user();
        $sales = SalesOrder::create([
            'customer_name' => $req->customer_name,
            'order_type' => $req->order_type,
            'date_order' => date('Y-m-d'),
            'tendered_cash' => $req->tendered_cash,
            'change' => $req->change,
        ]);

        SalesOrderDetail::create([
            'sales_order_id' => $sales->sales_order_id,
            'product_id' => $req->product_id,
            'qty' => $req->purchase_qty,
            'price' => $req->price,
        ]);


        $prodOrder = ProductOrder::find($req->product_order_id);
        $prodOrder->is_delivered = 1;
        $prodOrder->save();

        //logs every movement of the product
        ProductLog::create([
            'user_id' => $user->user_id,
            'product_id' => $req->product_id,
            'current_qty' => $cur_qty,
            'qty' => $req->purchase_qty,
            'remarks' => $req->purchase_qty . ' ' . $req->product . ' sold. Transaction from Mini POS. Order method is: ' . $req->order_type .'.'
        ]);
        return response()->json([
            'status' => 'saved'
        ], 200);

        //return $req;

    }

}
