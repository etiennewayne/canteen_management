<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MyOrderController extends Controller
{
    //


    public function __construct(){

    }
    
    public function index(){
        return view('user.my-order');
    }


}
