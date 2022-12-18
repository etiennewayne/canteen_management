<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Office;


class OpenOfficeController extends Controller
{
    //

    public function getOffices(){
        return Office::orderBy('office', 'asc')
            ->get();
    }
}
