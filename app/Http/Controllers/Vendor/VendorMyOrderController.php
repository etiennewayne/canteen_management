<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function getVendorMyOrders(Request $req){
        $user = Auth::user();
        return Store::where('user_id', $user->user_id)
            ->get();
    }

    public function show($id){
        return Store::find($id);
    }

}
