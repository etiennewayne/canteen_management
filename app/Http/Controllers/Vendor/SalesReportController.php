<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SalesReportController extends Controller
{
    //

    public function __construct(){
        $this->middleware('auth');
    }


    public function index(){
        $user = Auth::user();
        $stores = Store::where('user_id', $user->user_id)
            ->get();
        return view('vendor.sales-report')
            ->with('stores', $stores);
    }

    public function getSalesReport(Request $req){
        $user = Auth::user();

        $store = Store::where('user_id', $user->user_id)->first();
        //return $store;


        $from = date("Y-m-d", strtotime($req->from)); //convert to date format UNIX
        $to = date("Y-m-d", strtotime($req->to)); //convert to date format UNIX


        $data = DB::table('sales_orders as a')
            ->join('sales_order_details as b', 'a.sales_order_id', 'b.sales_order_id')
            ->join('products as c', 'b.product_id', 'c.product_id')
            ->join('stores as d', 'c.store_id', 'd.store_id')
            ->select('a.customer_name', 'a.order_type', 'a.date_order', 'a.tendered_cash', 'a.change',
                'b.product_id',  'b.qty', 'b.price', 'c.product', 'c.product_price',
                'd.store_id', 'd.store')
            ->whereBetween('a.date_order', [$from, $to])
            ->where('d.store_id',  $store->store_id)
            ->orderBy('a.sales_order_id', 'desc')
            ->get();
        return $data;
    }
}
