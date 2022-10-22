<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorMyStoreController extends Controller
{
    //

    public function __construct(){
        $this->middleware('auth');
        $this->middleware('vendor');
    }

    public function index()
    {
        return view('vendor.vendor-my-store');
    }

    public function getMyStores(Request $req){
        return Store::all();
    }

    public function show($id){
        return Store::find($id);
    }


    public function store(Request $req){
        $validate = $req->validate([
            'store' => ['required'],
            'owner' => ['required'],
        ]);
        $id = Auth::user()->user_id;
        Store::create([
            'user_id' => $id,
            'store' => strtoupper($req->store),
            'owner' => strtoupper($req->owner),
            'contact_no' => strtoupper($req->contact_no),
        ]);

        return response()->json([
            'status' => 'saved'
        ], 200);
    }

    public function update(Request $req, $id){
        $validate = $req->validate([
            'store' => ['required', 'unique:stores,store,'. $id . ',store_id'],
            'owner' => ['required'],
        ]);

        $data = Store::find($id);

        $data->store = strtoupper($req->store);
        $data->owner = strtoupper($req->owner);
        $data->contact_no = strtoupper($req->contact_no);
        $data->save();
        return response()->json([
            'status' => 'updated'
        ], 200);
    }

    public function destroy($id){
        Store::destroy($id);
        return response()->json([
            'status' => 'deleted'
        ], 200);
    }

}
