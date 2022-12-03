<?php

namespace Larashop\Http\Controllers;

use PDF;
use Illuminate\Http\Request;

class PdfController extends Controller
{
    public function index(){
            $params = [
                 'from_date' => 'karunarathne',
                'to_date' => '12345',
            ];
        $pdf = PDF::loadView('invoice',$params);
        return $pdf->download('invoice.pdf');
    }

}
