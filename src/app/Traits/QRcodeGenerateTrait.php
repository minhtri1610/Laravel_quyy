<?php

namespace App\Traits;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

trait QRcodeGenerateTrait
{
    public function createQR($data)
    {
        return QrCode::size(150)->generate($data);
    }

}
