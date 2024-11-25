<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\distritos;

class municipio extends Controller
{
    public function getDistricts($idProvincias)
    {
        $distritos = distritos::where('provincias_idProvincias', $idProvincias)
        ->where('municipio', '0')
        ->get();
        error_log('Distrito data: ' . json_encode($distritos)); // Adicione este log
        return response()->json($distritos);
    }
}