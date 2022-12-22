<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductRating;

class ProductRatingController extends Controller
{
    //

    public function store(Request $req){
        $user = Auth::user();
    }
}
