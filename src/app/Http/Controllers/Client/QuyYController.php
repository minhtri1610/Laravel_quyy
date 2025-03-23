<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class QuyYController extends Controller
{
    public function search()
    {
        return view('client.quyy.search');
    }

}
