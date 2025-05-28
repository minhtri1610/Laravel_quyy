<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QRcodeGenerateController extends Controller
{
    public function qrcode(
        Request $request
    ){
        $qrCodes = [];
        $qrCodes['simple'] = QrCode::size(150)->generate('https://minhazulmin.github.io/');
    
        return view('qrcode',$qrCodes);
    }
}
