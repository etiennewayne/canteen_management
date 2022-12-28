<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VendorDashboardController extends Controller
{
    //

    public function __construct(){
        $this->middleware('auth');
        $this->middleware('vendor');
        //$this->middleware('verifiedaccount');

    }

    public function index()
    {
        return view('vendor.vendor-dashboard');
    }


}
