<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class VerifyAccountController extends Controller
{
    //
    public function __construct(){
        $this->middleware('auth');
        //$this->middleware('verifyaccount');

    }
    public function index(){
        return view('verify-account');
    }

    public function verify($id){
        $user = User::find($id);

        $user->is_approve = 1;
        $user->save();

        return response()->json([
            'status' => 'updated'
        ]);
    }
}
