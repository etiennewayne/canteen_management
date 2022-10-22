<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;


class VendorStoreInfoController extends Controller
{
    //
    public function __construct(){
        $this->middleware('auth');
        $this->middleware('vendor');
    }

    public function index($id)
    {
        $data = Store::with('owner_info')
            ->where('store_id', $id)->first();
        return view('vendor.vendor-store-info')
            ->with('data', $data);
    }


}
