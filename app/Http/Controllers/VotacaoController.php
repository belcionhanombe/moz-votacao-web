<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\mesas;

class VotacaoController extends Controller
{

        public function getMesas($localId)
        {
            $mesas = mesas::where('localDeVotacao_idLocalDeVotacao', $localId)->get(['idMesas as id', 'idMesas as nome']);
            
            if($mesas->isEmpty()) {
                return response()->json(['message' => 'Este local não possui mesas.'], 404);
            }
    
            return response()->json($mesas);
        }
}



