<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductOrder;
use Auth;


class MyOrderController extends Controller
{
    //


    public function __construct(){

    }
    
    public function index(){
        return view('user.my-order');
    }

    public function getMyOrders(Request $req){
        $sort = explode('.', $req->sort_by);
        $user = Auth::user();

        $data = ProductOrder::with(['product'])
            ->where('customer_id', $user->user_id)
            ->orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);

        return $data;
        // 
        //     
    }


}
