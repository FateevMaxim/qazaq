<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Picqer\Barcode\BarcodeGeneratorPNG;

class StockController extends Controller
{
    public function generateBarCode(Request $request)
    {
        $generator = new BarcodeGeneratorPNG();
        $barcodeData = base64_encode($generator->getBarcode($request->input('track_code'), $generator::TYPE_CODE_128));
        return response()->json(['barcode' => $barcodeData]);

    }
}
