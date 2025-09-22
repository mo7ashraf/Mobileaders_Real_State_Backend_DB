<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class MapWebController extends Controller
{
    public function index()
    {
        return view('web.map');
    }
}

