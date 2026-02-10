<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FrontController extends Controller
{
    /**
     * Show under construction page
     */
    public function underConstruction()
    {
        return view('under-construction');
    }
}
