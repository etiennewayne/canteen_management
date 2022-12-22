<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Http;

class SignUpController extends Controller
{
    //
    public function index(){
        return view('sign-up');
    }


    public function store(Request $req){

        $validate = $req->validate([
            'username' => ['required', 'string', 'unique:users'],
            'lname' => ['required', 'string', 'max:100'],
            'fname' => ['required', 'string', 'max:100'],
            'sex' => ['required', 'string', 'max:20'],
            'role' => ['required'],
            'email' => ['required', 'unique:users'],
            'password' => ['required', 'string', 'confirmed'],
            'province' => ['required', 'string'],
            'city' => ['required', 'string'],
            'barangay' => ['required', 'string'],
        ], $message = [
            'role' => 'Account type is required.'
        ]);

        //$qr_code = substr(md5(time() . $req->lname . $req->fname), -8);

        User::create([
            //'qr_ref' => $qr_code,
            'username' => $req->username,
            'password' => Hash::make($req->password),
            'student_id' => $req->role == 'STUDENT' ? $req->student_id : '',
            'email' => $req->email,
            'lname' => strtoupper($req->lname),
            'fname' => strtoupper($req->fname),
            'mname' => strtoupper($req->mname),
            'suffix' => strtoupper($req->suffix),
            'sex' => $req->sex,
            'contact_no' => $req->contact_no,
            'role' => strtoupper($req->role),
            'province' => $req->province,
            'city' => $req->city,
            'barangay' => $req->barangay,
            'street' => strtoupper($req->street)
        ]);

        return response()->json([
            'status' => 'saved'
        ],200);
    }
}
