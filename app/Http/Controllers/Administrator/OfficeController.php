<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Office;

class OfficeController extends Controller
{
    //

    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        $services = Service::orderBy('service', 'asc')->get();

        return view('administrator.appointment')
            ->with('services', $services);
    }

    public function show($id){
        return Office::find($id);
    }

    public function store(Request $req){
        $req->validate([
            'office' => ['required', 'unique:offices']
        ]);

        Office::create([
            'office' => ucfirst($req->office)
        ]);

        return response()->json([
            'status' => 'saved'
        ], 200);
    }

    public function update(Request $req, $id){
        
        $req->validate([
            'office' => ['required', 'unique:offices,office,' .$id.',office_id']
        ]);

        $data = Office::find($id);
        $data->office = ucfirst($req->office);
        $data->save();

        return response()->json([
            'status' => 'saved'
        ], 200);
    }
}
