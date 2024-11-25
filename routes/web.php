<?php
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\LoginValidator;
use App\Http\Requests\ValidadorAdministradores;
use App\Http\Requests\validadorConfig;
use App\Http\Requests\validarEditarAdmin;
use App\Http\Requests\actualizarConfig;
use App\Http\Requests\validadormunicipio;
use App\Http\Requests\validadorPartidos;
use App\Http\Requests\validadorlocais;
use App\Http\Controllers\VotacaoController;
use App\Http\Controllers\municipio;
use App\administradores;
use App\provincias;
use App\distritos;
use App\localdevotacao;
use App\mesas;
use App\config;
use App\partidos;
use App\boletins;

Route::get('/get-mesas/{localId}', [VotacaoController::class, 'getMesas']);
Route::get('/get-districts/{idProvincias}', [municipio::class, 'getDistricts']);

Route::get('/', function () {
    return redirect('/ResultadosEleitoral');
});

// retornar Login de admins
Route::get('/adminsLogin', function () {
    return view('login');
});

Route::get('/classify', function(){

    return view('/classify');

});

Route::get('/relatorio/{id?}/{ano?}', function ($id = 0, $ano = 0) {

    $provincia = 0;
    $distrito = 0;
    $local = 0;
    $mesa = 0;
    $anoDeEleicao = $ano;
    $partes = explode('_', $id);
        // Verifique se count($partes) < 3


            $area = "";
            $idArea = "";
            $idAno = "";

        if (count($partes) < 3) {
            // O ano será passado como parâmetro, então pegue o valor do parâmetro
            // Agora aplicamos a lógica similar à função ResultadosEleitoral para esse ano específico
            $resultados = DB::table('votos')
                ->leftJoin('partidos', 'votos.Partidos_idPartidos', '=', 'partidos.idPartidos')
                ->select(
                    DB::raw('COALESCE(partidos.acronimo) AS nome_partido'),
                    DB::raw('SUM(votos.contador) AS total_votos')
                )
                ->whereYear('votos.dataCriado', $anoDeEleicao)
                ->whereNotIn('Partidos_idPartidos', [14, 16]) // Exclui votos em branco ou nulos
                ->groupBy('partidos.idPartidos', 'acronimo')
                ->orderBy('total_votos', 'desc')
                ->get();

            // Total de inscritos
            $inscritos = DB::table('mesas')
                ->whereYear('mesas.dataCriado', $anoDeEleicao)
                ->sum('eleitores');

            // Total de votos
            $totalVotos = DB::table('votos')
                ->whereYear('votos.dataCriado', $anoDeEleicao)
                ->sum('contador');

            // Total de votos válidos (excluindo nulos e brancos)
            $totalValidos = DB::table('votos')
                ->whereYear('votos.dataCriado', $anoDeEleicao)
                ->whereNotIn('Partidos_idPartidos', [14, 16])
                ->sum('contador');

            // Total de votos nulos
            $totalVotosNulo = DB::table('votos')
                ->whereYear('votos.dataCriado', $anoDeEleicao)
                ->where('Partidos_idPartidos', 14)
                ->sum('contador');

            // Total de votos em branco
            $totalVotosBranco = DB::table('votos')
                ->whereYear('votos.dataCriado', $anoDeEleicao)
                ->where('Partidos_idPartidos', 16)
                ->sum('contador');

        }

        if(count($partes) >2){
            $area = $partes[0];
            $idArea = $partes[1];
            $idAno = $partes[2];
        }

        if ($area == "provincias") {
            $provincia = DB::table('provincias')->where('provincias.idProvincias', $idArea)->first();
            $resultados = DB::table('votos')
            ->leftJoin('mesas', 'votos.mesas_idMesas', '=', 'mesas.idMesas')
            ->leftJoin('localdevotacao', 'mesas.localDeVotacao_idLocalDeVotacao', '=', 'localdevotacao.idLocalDeVotacao')
            ->leftJoin('distritos', 'localdevotacao.distritos_idDistritos', '=', 'distritos.idDistritos')
            ->leftJoin('provincias', 'distritos.provincias_idProvincias', '=', 'provincias.idProvincias')
            ->leftJoin('partidos', 'votos.Partidos_idPartidos', '=', 'partidos.idPartidos')
            ->select(
                DB::raw('COALESCE(partidos.acronimo) AS nome_partido'),
                DB::raw('SUM(votos.contador) AS total_votos')
            )
            ->where('provincias.idProvincias', $idArea)
            ->whereYear('votos.dataCriado', $anoDeEleicao)
            ->whereNotIn('Partidos_idPartidos', [14, 16])
            ->groupBy('partidos.idPartidos', 'acronimo')
            ->orderBy('total_votos', 'desc')
            ->get();
        //  total inscritos
        $inscritos = DB::table('mesas')
            ->leftJoin('localdevotacao', 'mesas.localDeVotacao_idLocalDeVotacao', '=', 'localdevotacao.idLocalDeVotacao')
            ->leftJoin('distritos', 'localdevotacao.distritos_idDistritos', '=', 'distritos.idDistritos')
            ->leftJoin('provincias', 'distritos.provincias_idProvincias', '=', 'provincias.idProvincias')
            ->where('provincias.idProvincias', $idArea)
            ->whereYear('mesas.dataCriado', $anoDeEleicao)
            ->sum('mesas.eleitores');


    // total de votos
        $totalVotos = DB::table('votos')
            ->leftJoin('mesas', 'votos.mesas_idMesas', '=', 'mesas.idMesas')
            ->leftJoin('localdevotacao', 'mesas.localDeVotacao_idLocalDeVotacao', '=', 'localdevotacao.idLocalDeVotacao')
            ->leftJoin('distritos', 'localdevotacao.distritos_idDistritos', '=', 'distritos.idDistritos')
            ->leftJoin('provincias', 'distritos.provincias_idProvincias', '=', 'provincias.idProvincias')
            ->where('provincias.idProvincias', $idArea)
            ->whereYear('votos.dataCriado', $anoDeEleicao)
            ->sum('votos.contador');

   // todos validos
    $totalValidos = DB::table('votos')
        ->leftJoin('mesas', 'votos.mesas_idMesas', '=', 'mesas.idMesas')
        ->leftJoin('localdevotacao', 'mesas.localDeVotacao_idLocalDeVotacao', '=', 'localdevotacao.idLocalDeVotacao')
        ->leftJoin('distritos', 'localdevotacao.distritos_idDistritos', '=', 'distritos.idDistritos')
        ->leftJoin('provincias', 'distritos.provincias_idProvincias', '=', 'provincias.idProvincias')
        ->where('provincias.idProvincias', $idArea)
        ->whereYear('votos.dataCriado', $anoDeEleicao)
        ->whereNotIn('Partidos_idPartidos', [14, 16])
        ->sum('votos.contador');

       // Total de votos nulos
    $totalVotosNulo = DB::table('votos')
        ->leftJoin('mesas', 'votos.mesas_idMesas', '=', 'mesas.idMesas')
        ->leftJoin('localdevotacao', 'mesas.localDeVotacao_idLocalDeVotacao', '=', 'localdevotacao.idLocalDeVotacao')
        ->leftJoin('distritos', 'localdevotacao.distritos_idDistritos', '=', 'distritos.idDistritos')
        ->leftJoin('provincias', 'distritos.provincias_idProvincias', '=', 'provincias.idProvincias')
        ->where('provincias.idProvincias', $idArea)
        ->whereYear('votos.dataCriado', $anoDeEleicao)
        ->where('Partidos_idPartidos', 14)
        ->sum('votos.contador');

       // Total de votos em branco

    $totalVotosBranco = DB::table('votos')
        ->leftJoin('mesas', 'votos.mesas_idMesas', '=', 'mesas.idMesas')
        ->leftJoin('localdevotacao', 'mesas.localDeVotacao_idLocalDeVotacao', '=', 'localdevotacao.idLocalDeVotacao')
        ->leftJoin('distritos', 'localdevotacao.distritos_idDistritos', '=', 'distritos.idDistritos')
        ->leftJoin('provincias', 'distritos.provincias_idProvincias', '=', 'provincias.idProvincias')
        ->where('provincias.idProvincias', $idArea)
        ->whereYear('votos.dataCriado', $anoDeEleicao)
        ->where('Partidos_idPartidos', 16)
        ->sum('votos.contador');
    $distritos = DB::table('distritos')
    ->where('provincias_idProvincias', $idArea)
    ->get();
        }

        if($area == "distritos") {

            $distrito = DB::table('distritos')->where('idDistritos', $idArea)->first();
            $provincia = DB::table('provincias')->where('idProvincias', $distrito->provincias_idProvincias)->first();
            $resultados = DB::table('votos')
                ->leftJoin('mesas', 'votos.mesas_idMesas', '=', 'mesas.idMesas')
                ->leftJoin('localdevotacao', 'mesas.localDeVotacao_idLocalDeVotacao', '=', 'localdevotacao.idLocalDeVotacao')
                ->leftJoin('distritos', 'localdevotacao.distritos_idDistritos', '=', 'distritos.idDistritos')
                ->leftJoin('partidos', 'votos.Partidos_idPartidos', '=', 'partidos.idPartidos')
                ->select(
                    DB::raw('COALESCE(partidos.acronimo) AS nome_partido'),
                    DB::raw('SUM(votos.contador) AS total_votos')
                )
                ->where('distritos.idDistritos', $idArea)
                ->whereYear('votos.dataCriado', $anoDeEleicao)
                ->whereNotIn('Partidos_idPartidos', [14, 16])
                ->groupBy('partidos.idPartidos', 'acronimo')
                ->orderBy('total_votos', 'desc')
                ->get();

            // Total inscritos
                $inscritos = DB::table('mesas')
                ->leftJoin('localdevotacao', 'mesas.localDeVotacao_idLocalDeVotacao', '=', 'localdevotacao.idLocalDeVotacao')
                ->leftJoin('distritos', 'localdevotacao.distritos_idDistritos', '=', 'distritos.idDistritos')
                ->where('distritos.idDistritos', $idArea)
                ->whereYear('mesas.dataCriado', $anoDeEleicao)
                ->sum('mesas.eleitores');

            // Total de votos
            $totalVotos = DB::table('votos')
                ->leftJoin('mesas', 'votos.mesas_idMesas', '=', 'mesas.idMesas')
                ->leftJoin('localdevotacao', 'mesas.localDeVotacao_idLocalDeVotacao', '=', 'localdevotacao.idLocalDeVotacao')
                ->leftJoin('distritos', 'localdevotacao.distritos_idDistritos', '=', 'distritos.idDistritos')
                ->where('distritos.idDistritos', $idArea)
                ->whereYear('votos.dataCriado', $anoDeEleicao)
                ->sum('votos.contador');

            // Total de votos válidos
            $totalValidos = DB::table('votos')
                ->leftJoin('mesas', 'votos.mesas_idMesas', '=', 'mesas.idMesas')
                ->leftJoin('localdevotacao', 'mesas.localDeVotacao_idLocalDeVotacao', '=', 'localdevotacao.idLocalDeVotacao')
                ->leftJoin('distritos', 'localdevotacao.distritos_idDistritos', '=', 'distritos.idDistritos')
                ->where('distritos.idDistritos', $idArea)
                ->whereYear('votos.dataCriado', $anoDeEleicao)
                ->whereNotIn('Partidos_idPartidos', [14, 16])
                ->sum('votos.contador');

            // Total de votos nulos
            $totalVotosNulo = DB::table('votos')
                ->leftJoin('mesas', 'votos.mesas_idMesas', '=', 'mesas.idMesas')
                ->leftJoin('localdevotacao', 'mesas.localDeVotacao_idLocalDeVotacao', '=', 'localdevotacao.idLocalDeVotacao')
                ->leftJoin('distritos', 'localdevotacao.distritos_idDistritos', '=', 'distritos.idDistritos')
                ->where('distritos.idDistritos', $idArea)
                ->whereYear('votos.dataCriado', $anoDeEleicao)
                ->where('Partidos_idPartidos', 14)
                ->sum('votos.contador');

            // Total de votos em branco
            $totalVotosBranco = DB::table('votos')
                ->leftJoin('mesas', 'votos.mesas_idMesas', '=', 'mesas.idMesas')
                ->leftJoin('localdevotacao', 'mesas.localDeVotacao_idLocalDeVotacao', '=', 'localdevotacao.idLocalDeVotacao')
                ->leftJoin('distritos', 'localdevotacao.distritos_idDistritos', '=', 'distritos.idDistritos')
                ->where('distritos.idDistritos', $idArea)
                ->whereYear('votos.dataCriado', $anoDeEleicao)
                ->where('Partidos_idPartidos', 16)
                ->sum('votos.contador');

                $distrito = DB::table('distritos')->where('idDistritos', $idArea)->first();
                // Obtém a província do distrito
                $idProvincia = $distrito->provincias_idProvincias;
                // Obtém todos os distritos da província
                $distritos = DB::table('distritos')->where('provincias_idProvincias', $idProvincia)->get();
               $locais = DB::table('localdevotacao')->where('distritos_idDistritos', $idArea) ->get();
            }

            if($area =="centros"){
                $local = DB::table('localdevotacao')->where('localdevotacao.idLocalDeVotacao', $idArea) ->first();
                $distrito = DB::table('distritos')->where('idDistritos', $local->distritos_idDistritos)->first();
                $provincia = DB::table('provincias')->where('provincias.idProvincias', $distrito->provincias_idProvincias)->first("nome");

                $resultados = DB::table('votos')
                ->leftJoin('mesas', 'votos.mesas_idMesas', '=', 'mesas.idMesas')
                ->leftJoin('localdevotacao', 'mesas.localDeVotacao_idLocalDeVotacao', '=', 'localdevotacao.idLocalDeVotacao')
                ->leftJoin('partidos', 'votos.Partidos_idPartidos', '=', 'partidos.idPartidos')
                ->select(
                    DB::raw('COALESCE(partidos.acronimo) AS nome_partido'),
                    DB::raw('SUM(votos.contador) AS total_votos')
                )
                ->where('localdevotacao.idLocalDeVotacao', $idArea)
                ->whereNotIn('Partidos_idPartidos', [14, 16])
                ->groupBy('partidos.idPartidos', 'acronimo')
                ->orderBy('total_votos', 'desc')
                ->get();

                $inscritos = DB::table('mesas')
                ->leftJoin('localdevotacao', 'mesas.localDeVotacao_idLocalDeVotacao', '=', 'localdevotacao.idLocalDeVotacao')
                ->where('localdevotacao.idLocalDeVotacao', $idArea)
                ->sum('mesas.eleitores');


            $totalVotos = DB::table('votos')
                ->leftJoin('mesas', 'votos.mesas_idMesas', '=', 'mesas.idMesas')
                ->leftJoin('localdevotacao', 'mesas.localDeVotacao_idLocalDeVotacao', '=', 'localdevotacao.idLocalDeVotacao')
                ->where('localdevotacao.idLocalDeVotacao', $idArea)
                ->sum('votos.contador');

            $totalValidos = DB::table('votos')
                ->leftJoin('mesas', 'votos.mesas_idMesas', '=', 'mesas.idMesas')
                ->leftJoin('localdevotacao', 'mesas.localDeVotacao_idLocalDeVotacao', '=', 'localdevotacao.idLocalDeVotacao')
                ->where('localdevotacao.idLocalDeVotacao', $idArea)
                ->whereYear('votos.dataCriado', $anoDeEleicao)
                ->whereNotIn('Partidos_idPartidos', [14, 16])
                ->sum('votos.contador');

            $totalVotosNulo = DB::table('votos')
                ->leftJoin('mesas', 'votos.mesas_idMesas', '=', 'mesas.idMesas')
                ->leftJoin('localdevotacao', 'mesas.localDeVotacao_idLocalDeVotacao', '=', 'localdevotacao.idLocalDeVotacao')
                ->where('localdevotacao.idLocalDeVotacao', $idArea)
                ->whereYear('votos.dataCriado', $anoDeEleicao)
                ->where('Partidos_idPartidos', 14)
                ->sum('votos.contador');

            $totalVotosBranco = DB::table('votos')
                ->leftJoin('mesas', 'votos.mesas_idMesas', '=', 'mesas.idMesas')
                ->leftJoin('localdevotacao', 'mesas.localDeVotacao_idLocalDeVotacao', '=', 'localdevotacao.idLocalDeVotacao')
                ->where('localdevotacao.idLocalDeVotacao', $idArea)
                ->whereYear('votos.dataCriado', $anoDeEleicao)
                ->where('Partidos_idPartidos', 16)
                ->sum('votos.contador');

            // Obter o local de votação atual
            $localAtual = DB::table('localdevotacao')
            ->where('idLocalDeVotacao', $idArea)
            ->first();

            // Obtém todos os locais
            $locais = DB::table('localdevotacao')
                ->where('distritos_idDistritos', $localAtual->distritos_idDistritos)
                ->get();

            // Obter o distrito actual
            $DistritoActual = DB::table('distritos')
            ->where('idDistritos', $localAtual->distritos_idDistritos)->first();

            // Obtém todos os Distritos
            $distritos = DB::table('distritos')
                ->where('provincias_idProvincias', $DistritoActual->provincias_idProvincias)
                ->get();
            $mesas = DB::table('mesas')
                -> where('localDeVotacao_idLocalDeVotacao', $idArea)
                ->get();
            }

            if($area=="mesas"){
            $mesa = DB::table('mesas')->where('mesas.idMesas', $idArea) ->first();
            $local = DB::table('localdevotacao')->where('localdevotacao.idLocalDeVotacao', $mesa->localDeVotacao_idLocalDeVotacao) ->first();
            $distrito = DB::table('distritos')->where('distritos.idDistritos', $local->distritos_idDistritos)->first();
            $provincia = DB::table('provincias')->where('provincias.idProvincias', $distrito->provincias_idProvincias)->first();
                // Selecionar resultados de votos por partido para a mesa específica

            $resultados = DB::table('votos')
            ->leftJoin('partidos', 'votos.Partidos_idPartidos', '=', 'partidos.idPartidos')
            ->select(
                DB::raw('COALESCE(partidos.acronimo) AS nome_partido'),
                DB::raw('SUM(votos.contador) AS total_votos')
            )
            ->where('votos.mesas_idMesas', $idArea)
            ->whereYear('votos.dataCriado', $anoDeEleicao)
            ->whereNotIn('Partidos_idPartidos', [14, 16])
            ->groupBy('partidos.idPartidos', 'acronimo')
            ->orderBy('total_votos', 'desc')
            ->get();

            // Contar o número de inscritos para o ano atual na mesa específica
            $inscritos = DB::table('mesas')
                ->where('mesas.idMesas', $idArea)
                ->sum('mesas.eleitores');
            // Somar o total de votos na mesa específica
            $totalVotos = DB::table('votos')
            ->where('votos.mesas_idMesas', $idArea)
            ->sum('votos.contador');

            // Somar o total de votos válidos na mesa específica
            $totalValidos = DB::table('votos')
            ->where('votos.mesas_idMesas', $idArea)
            ->whereNotIn('Partidos_idPartidos', [14, 16])
            ->sum('votos.contador');

            // Somar o total de votos nulos na mesa específica
            $totalVotosNulo = DB::table('votos')
            ->where('votos.mesas_idMesas', $idArea)
            ->where('Partidos_idPartidos', 14)
            ->sum('votos.contador');

            // Somar o total de votos em branco na mesa específica
            $totalVotosBranco = DB::table('votos')
            ->where('votos.mesas_idMesas', $idArea)
            ->where('Partidos_idPartidos', 16)
            ->sum('votos.contador');

            // dados anteiror
            // Obter a mesa de votação atual
            $mesaAtual = DB::table('mesas')
                ->where('idMesas', $idArea)
                ->first();

            // Obtém todas as mesas anteriores no mesmo local de votação
            $mesas = DB::table('mesas')
                ->where('localDeVotacao_idLocalDeVotacao', $mesaAtual->localDeVotacao_idLocalDeVotacao)
                ->get();

            // Obter o local de votação atual da mesa
            $localAtual = DB::table('localdevotacao')
                ->where('idLocalDeVotacao', $mesaAtual->localDeVotacao_idLocalDeVotacao)
                ->first();

            $locais = DB::table('localdevotacao')
             ->where('distritos_idDistritos', $localAtual->distritos_idDistritos)->get();

            // Obter o distrito atual do local de votação
            $distritoAtual = DB::table('distritos')
                ->where('idDistritos', $localAtual->distritos_idDistritos)
                ->first();
            // Obtém todos os distritos da mesma província do distrito atual
            $distritos = DB::table('distritos')
                ->where('provincias_idProvincias', $distritoAtual->provincias_idProvincias)
                ->get();
            }

        return view('/relatorio', [
            "resultados" => $resultados,
            "totalVotos" => $totalVotos,
            "totalValidos" => $totalValidos,
            "totalVotosBranco" => $totalVotosBranco,
            "totalVotosNulo" => $totalVotosNulo,
            "inscritos" => $inscritos,
            "anoDeEleicao" => $anoDeEleicao,
            "provincia" => $provincia,
            "distrito" => $distrito,
            "local" => $local,
            "mesa" => $mesa
        ]);

});


// Mostrar resultados quando entrar no sistema....

Route::get('ResultadosEleitoral/{id?}', function ($id=0) {
    $resultados = 0;
    $totalVotos = 0;
    $totalValidos = 0;
    $totalVotosBranco = 0;
    $totalVotosNulo = 0;
    $inscritos = 0;
    $distritos = [];
    $locais = [];
    $mesas = [];
    $relatorioID  = $id;

    $todasEleicoes = DB::table('Config')
    ->orderBy('dataFim', 'desc')
    ->select('Config.ano', 'Config.tipoEleicao')
    ->get();

    $anoDeEleicao = DB::table('Config')
    ->where('ano', $id)
    ->first('ano');

    //Nao existe eleicao no ano selecionado...?
    if(!$anoDeEleicao){
    $eleicao = DB::table('Config')
    ->orderBy('dataFim', 'desc')
    ->select('Config.dataInicio', 'Config.dataFim', 'Config.tipoEleicao')
    ->first();
    $anoDeEleicao = date('Y', strtotime($eleicao->dataFim));
    }
    else{
        $anoDeEleicao = $anoDeEleicao->ano;
    }

    $eleicao = DB::table('Config')
    ->orderBy('dataFim', 'desc')
    ->where('ano', $anoDeEleicao)
    ->select('Config.dataInicio', 'Config.dataFim', 'Config.tipoEleicao')
    ->first();

    $resultados = DB::table('votos')
    ->leftJoin('partidos', 'votos.Partidos_idPartidos', '=', 'partidos.idPartidos')
    ->select(
        DB::raw('COALESCE(partidos.acronimo) AS nome_partido'),
        DB::raw('SUM(votos.contador) AS total_votos')
    )
    ->whereYear('votos.dataCriado', $anoDeEleicao )
    ->whereNotIn('Partidos_idPartidos', [14, 16])
    ->groupBy('partidos.idPartidos', 'acronimo')
    ->orderBy('total_votos', 'desc')
    ->get();
     // provincias
     $provincias = DB::table('provincias')
     ->orderBy('idProvincias', 'asc')
     ->get();

    //  total inscritos

    $inscritos = DB::table('mesas')
    ->whereYear('mesas.dataCriado', $anoDeEleicao)
    ->sum('eleitores');

    // total de votos
    $totalVotos = DB::table('votos')
    ->whereYear('votos.dataCriado', $anoDeEleicao )
    ->sum('contador');
    // todos validos
     $totalValidos = DB::table('votos')
        ->whereYear('votos.dataCriado', $anoDeEleicao )
        ->whereNotIn('Partidos_idPartidos', [14, 16])
        ->sum('contador');

    $totalVotosNulo = DB::table('votos')
    ->whereYear('votos.dataCriado', $anoDeEleicao )
    ->where('Partidos_idPartidos', 14)
    ->sum('contador');

    // Total de votos em branco
    $totalVotosBranco = DB::table('votos')
    ->whereYear('votos.dataCriado', $anoDeEleicao )
    ->where('Partidos_idPartidos', 16)
    ->sum('contador');


    return view('/principal', [
        "resultados" => $resultados,
        "totalVotos" =>  $totalVotos,
        "totalValidos" => $totalValidos,
        "totalVotosBranco" => $totalVotosBranco,
        "totalVotosNulo" => $totalVotosNulo,
        "inscritos" => $inscritos,
        "provincias" => $provincias,
        "distritos" => $distritos,
        "locais" => $locais,
        "mesas" => $mesas,
        "eleicao" => $eleicao,
        "ano" => $anoDeEleicao,
        "todasEleicoes" =>$todasEleicoes,
        "relatorioID" => $relatorioID,
        "anoDeEleicao" => $anoDeEleicao
    ]);
});



// mostrar resultado por selecao....

Route::get('/principal/{id?}', function ($id=100) {
$partes = explode('_', $id);
if (count($partes) < 3) {
    return redirect()->back();
}

$area = $partes[0];
$idArea = $partes[1];
$idAno = $partes[2];


$todasEleicoes = DB::table('Config')
    ->orderBy('dataFim', 'desc')
    ->select('Config.ano', 'Config.tipoEleicao')
    ->get();

    $anoDeEleicao = DB::table('Config')
    ->where('ano', $idAno)
    ->first('ano');

    //Nao existe eleicao no ano selecionado...?
    if(!$anoDeEleicao){
        return back();
    }
    else{
        $anoDeEleicao = $anoDeEleicao->ano;
    }

    $eleicao = DB::table('Config')
    ->orderBy('dataFim', 'desc')
    ->where('ano', $anoDeEleicao)
    ->select('Config.dataInicio', 'Config.dataFim', 'Config.tipoEleicao')
    ->first();

         // provincias
         $provincias = DB::table('provincias')
         ->orderBy('idProvincias', 'asc')
         ->get();
//variaveis...
    $resultados =0;
    $totalVotos =0;
    $totalValidos =0;
    $totalVotosBranco =0;
    $totalVotosNulo =0;
    $inscritos =0;
    $distritos = [];
    $locais = [];
    $mesas = [];
    $relatorioID = $id;

     $anoAtual = Carbon::now()->year;
       // considera que seja provincia
    if($area == "provincias"){
        $resultados = DB::table('votos')
            ->leftJoin('mesas', 'votos.mesas_idMesas', '=', 'mesas.idMesas')
            ->leftJoin('localdevotacao', 'mesas.localDeVotacao_idLocalDeVotacao', '=', 'localdevotacao.idLocalDeVotacao')
            ->leftJoin('distritos', 'localdevotacao.distritos_idDistritos', '=', 'distritos.idDistritos')
            ->leftJoin('provincias', 'distritos.provincias_idProvincias', '=', 'provincias.idProvincias')
            ->leftJoin('partidos', 'votos.Partidos_idPartidos', '=', 'partidos.idPartidos')
            ->select(
                DB::raw('COALESCE(partidos.acronimo) AS nome_partido'),
                DB::raw('SUM(votos.contador) AS total_votos')
            )
            ->where('provincias.idProvincias', $idArea)
            ->whereYear('votos.dataCriado', $anoDeEleicao)
            ->whereNotIn('Partidos_idPartidos', [14, 16])
            ->groupBy('partidos.idPartidos', 'acronimo')
            ->orderBy('total_votos', 'desc')
            ->get();
        //  total inscritos
        $inscritos = DB::table('mesas')
            ->leftJoin('localdevotacao', 'mesas.localDeVotacao_idLocalDeVotacao', '=', 'localdevotacao.idLocalDeVotacao')
            ->leftJoin('distritos', 'localdevotacao.distritos_idDistritos', '=', 'distritos.idDistritos')
            ->leftJoin('provincias', 'distritos.provincias_idProvincias', '=', 'provincias.idProvincias')
            ->where('provincias.idProvincias', $idArea)
            ->whereYear('mesas.dataCriado', $anoDeEleicao)
            ->sum('mesas.eleitores');


    // total de votos
        $totalVotos = DB::table('votos')
            ->leftJoin('mesas', 'votos.mesas_idMesas', '=', 'mesas.idMesas')
            ->leftJoin('localdevotacao', 'mesas.localDeVotacao_idLocalDeVotacao', '=', 'localdevotacao.idLocalDeVotacao')
            ->leftJoin('distritos', 'localdevotacao.distritos_idDistritos', '=', 'distritos.idDistritos')
            ->leftJoin('provincias', 'distritos.provincias_idProvincias', '=', 'provincias.idProvincias')
            ->where('provincias.idProvincias', $idArea)
            ->whereYear('votos.dataCriado', $anoDeEleicao)
            ->sum('votos.contador');

   // todos validos
    $totalValidos = DB::table('votos')
        ->leftJoin('mesas', 'votos.mesas_idMesas', '=', 'mesas.idMesas')
        ->leftJoin('localdevotacao', 'mesas.localDeVotacao_idLocalDeVotacao', '=', 'localdevotacao.idLocalDeVotacao')
        ->leftJoin('distritos', 'localdevotacao.distritos_idDistritos', '=', 'distritos.idDistritos')
        ->leftJoin('provincias', 'distritos.provincias_idProvincias', '=', 'provincias.idProvincias')
        ->where('provincias.idProvincias', $idArea)
        ->whereYear('votos.dataCriado', $anoDeEleicao)
        ->whereNotIn('Partidos_idPartidos', [14, 16])
        ->sum('votos.contador');

       // Total de votos nulos
    $totalVotosNulo = DB::table('votos')
        ->leftJoin('mesas', 'votos.mesas_idMesas', '=', 'mesas.idMesas')
        ->leftJoin('localdevotacao', 'mesas.localDeVotacao_idLocalDeVotacao', '=', 'localdevotacao.idLocalDeVotacao')
        ->leftJoin('distritos', 'localdevotacao.distritos_idDistritos', '=', 'distritos.idDistritos')
        ->leftJoin('provincias', 'distritos.provincias_idProvincias', '=', 'provincias.idProvincias')
        ->where('provincias.idProvincias', $idArea)
        ->whereYear('votos.dataCriado', $anoDeEleicao)
        ->where('Partidos_idPartidos', 14)
        ->sum('votos.contador');

       // Total de votos em branco

    $totalVotosBranco = DB::table('votos')
        ->leftJoin('mesas', 'votos.mesas_idMesas', '=', 'mesas.idMesas')
        ->leftJoin('localdevotacao', 'mesas.localDeVotacao_idLocalDeVotacao', '=', 'localdevotacao.idLocalDeVotacao')
        ->leftJoin('distritos', 'localdevotacao.distritos_idDistritos', '=', 'distritos.idDistritos')
        ->leftJoin('provincias', 'distritos.provincias_idProvincias', '=', 'provincias.idProvincias')
        ->where('provincias.idProvincias', $idArea)
        ->whereYear('votos.dataCriado', $anoDeEleicao)
        ->where('Partidos_idPartidos', 16)
        ->sum('votos.contador');
    $distritos = DB::table('distritos')
    ->where('provincias_idProvincias', $idArea)
    ->get();
    }
//Considerar Distritos....
if($area == "distritos") {

$resultados = DB::table('votos')
    ->leftJoin('mesas', 'votos.mesas_idMesas', '=', 'mesas.idMesas')
    ->leftJoin('localdevotacao', 'mesas.localDeVotacao_idLocalDeVotacao', '=', 'localdevotacao.idLocalDeVotacao')
    ->leftJoin('distritos', 'localdevotacao.distritos_idDistritos', '=', 'distritos.idDistritos')
    ->leftJoin('partidos', 'votos.Partidos_idPartidos', '=', 'partidos.idPartidos')
    ->select(
        DB::raw('COALESCE(partidos.acronimo) AS nome_partido'),
        DB::raw('SUM(votos.contador) AS total_votos')
    )
    ->where('distritos.idDistritos', $idArea)
    ->whereYear('votos.dataCriado', $anoDeEleicao)
    ->whereNotIn('Partidos_idPartidos', [14, 16])
    ->groupBy('partidos.idPartidos', 'acronimo')
    ->orderBy('total_votos', 'desc')
    ->get();

// Total inscritos
    $inscritos = DB::table('mesas')
    ->leftJoin('localdevotacao', 'mesas.localDeVotacao_idLocalDeVotacao', '=', 'localdevotacao.idLocalDeVotacao')
    ->leftJoin('distritos', 'localdevotacao.distritos_idDistritos', '=', 'distritos.idDistritos')
    ->where('distritos.idDistritos', $idArea)
    ->whereYear('mesas.dataCriado', $anoDeEleicao)
    ->sum('mesas.eleitores');

// Total de votos
$totalVotos = DB::table('votos')
    ->leftJoin('mesas', 'votos.mesas_idMesas', '=', 'mesas.idMesas')
    ->leftJoin('localdevotacao', 'mesas.localDeVotacao_idLocalDeVotacao', '=', 'localdevotacao.idLocalDeVotacao')
    ->leftJoin('distritos', 'localdevotacao.distritos_idDistritos', '=', 'distritos.idDistritos')
    ->where('distritos.idDistritos', $idArea)
    ->whereYear('votos.dataCriado', $anoDeEleicao)
    ->sum('votos.contador');

// Total de votos válidos
$totalValidos = DB::table('votos')
    ->leftJoin('mesas', 'votos.mesas_idMesas', '=', 'mesas.idMesas')
    ->leftJoin('localdevotacao', 'mesas.localDeVotacao_idLocalDeVotacao', '=', 'localdevotacao.idLocalDeVotacao')
    ->leftJoin('distritos', 'localdevotacao.distritos_idDistritos', '=', 'distritos.idDistritos')
    ->where('distritos.idDistritos', $idArea)
    ->whereYear('votos.dataCriado', $anoDeEleicao)
    ->whereNotIn('Partidos_idPartidos', [14, 16])
    ->sum('votos.contador');

// Total de votos nulos
$totalVotosNulo = DB::table('votos')
    ->leftJoin('mesas', 'votos.mesas_idMesas', '=', 'mesas.idMesas')
    ->leftJoin('localdevotacao', 'mesas.localDeVotacao_idLocalDeVotacao', '=', 'localdevotacao.idLocalDeVotacao')
    ->leftJoin('distritos', 'localdevotacao.distritos_idDistritos', '=', 'distritos.idDistritos')
    ->where('distritos.idDistritos', $idArea)
    ->whereYear('votos.dataCriado', $anoDeEleicao)
    ->where('Partidos_idPartidos', 14)
    ->sum('votos.contador');

// Total de votos em branco
$totalVotosBranco = DB::table('votos')
    ->leftJoin('mesas', 'votos.mesas_idMesas', '=', 'mesas.idMesas')
    ->leftJoin('localdevotacao', 'mesas.localDeVotacao_idLocalDeVotacao', '=', 'localdevotacao.idLocalDeVotacao')
    ->leftJoin('distritos', 'localdevotacao.distritos_idDistritos', '=', 'distritos.idDistritos')
    ->where('distritos.idDistritos', $idArea)
    ->whereYear('votos.dataCriado', $anoDeEleicao)
    ->where('Partidos_idPartidos', 16)
    ->sum('votos.contador');

    $distrito = DB::table('distritos')->where('idDistritos', $idArea)->first();
    // Obtém a província do distrito
    $idProvincia = $distrito->provincias_idProvincias;
    // Obtém todos os distritos da província
    $distritos = DB::table('distritos')->where('provincias_idProvincias', $idProvincia)->get();
    $locais = DB::table('localdevotacao')->where('distritos_idDistritos', $idArea) ->get();

}

if($area =="centros"){
    $resultados = DB::table('votos')
    ->leftJoin('mesas', 'votos.mesas_idMesas', '=', 'mesas.idMesas')
    ->leftJoin('localdevotacao', 'mesas.localDeVotacao_idLocalDeVotacao', '=', 'localdevotacao.idLocalDeVotacao')
    ->leftJoin('partidos', 'votos.Partidos_idPartidos', '=', 'partidos.idPartidos')
    ->select(
        DB::raw('COALESCE(partidos.acronimo) AS nome_partido'),
        DB::raw('SUM(votos.contador) AS total_votos')
    )
    ->where('localdevotacao.idLocalDeVotacao', $idArea)
    ->whereNotIn('Partidos_idPartidos', [14, 16])
    ->groupBy('partidos.idPartidos', 'acronimo')
    ->orderBy('total_votos', 'desc')
    ->get();

    $inscritos = DB::table('mesas')
    ->leftJoin('localdevotacao', 'mesas.localDeVotacao_idLocalDeVotacao', '=', 'localdevotacao.idLocalDeVotacao')
    ->where('localdevotacao.idLocalDeVotacao', $idArea)
    ->sum('mesas.eleitores');


$totalVotos = DB::table('votos')
    ->leftJoin('mesas', 'votos.mesas_idMesas', '=', 'mesas.idMesas')
    ->leftJoin('localdevotacao', 'mesas.localDeVotacao_idLocalDeVotacao', '=', 'localdevotacao.idLocalDeVotacao')
    ->where('localdevotacao.idLocalDeVotacao', $idArea)
    ->sum('votos.contador');

$totalValidos = DB::table('votos')
    ->leftJoin('mesas', 'votos.mesas_idMesas', '=', 'mesas.idMesas')
    ->leftJoin('localdevotacao', 'mesas.localDeVotacao_idLocalDeVotacao', '=', 'localdevotacao.idLocalDeVotacao')
    ->where('localdevotacao.idLocalDeVotacao', $idArea)
    ->whereYear('votos.dataCriado', $anoDeEleicao)
    ->whereNotIn('Partidos_idPartidos', [14, 16])
    ->sum('votos.contador');

$totalVotosNulo = DB::table('votos')
    ->leftJoin('mesas', 'votos.mesas_idMesas', '=', 'mesas.idMesas')
    ->leftJoin('localdevotacao', 'mesas.localDeVotacao_idLocalDeVotacao', '=', 'localdevotacao.idLocalDeVotacao')
    ->where('localdevotacao.idLocalDeVotacao', $idArea)
    ->whereYear('votos.dataCriado', $anoDeEleicao)
    ->where('Partidos_idPartidos', 14)
    ->sum('votos.contador');

$totalVotosBranco = DB::table('votos')
    ->leftJoin('mesas', 'votos.mesas_idMesas', '=', 'mesas.idMesas')
    ->leftJoin('localdevotacao', 'mesas.localDeVotacao_idLocalDeVotacao', '=', 'localdevotacao.idLocalDeVotacao')
    ->where('localdevotacao.idLocalDeVotacao', $idArea)
    ->whereYear('votos.dataCriado', $anoDeEleicao)
    ->where('Partidos_idPartidos', 16)
    ->sum('votos.contador');

// Obter o local de votação atual
$localAtual = DB::table('localdevotacao')
->where('idLocalDeVotacao', $idArea)
->first();

// Obtém todos os locais
$locais = DB::table('localdevotacao')
    ->where('distritos_idDistritos', $localAtual->distritos_idDistritos)
    ->get();

// Obter o distrito actual
$DistritoActual = DB::table('distritos')
->where('idDistritos', $localAtual->distritos_idDistritos)->first();

// Obtém todos os Distritos
$distritos = DB::table('distritos')
    ->where('provincias_idProvincias', $DistritoActual->provincias_idProvincias)
    ->get();
$mesas = DB::table('mesas')
    -> where('localDeVotacao_idLocalDeVotacao', $idArea)
    ->get();
}

if($area=="mesas"){
    // Selecionar resultados de votos por partido para a mesa específica
$resultados = DB::table('votos')
->leftJoin('partidos', 'votos.Partidos_idPartidos', '=', 'partidos.idPartidos')
->select(
    DB::raw('COALESCE(partidos.acronimo) AS nome_partido'),
    DB::raw('SUM(votos.contador) AS total_votos')
)
->where('votos.mesas_idMesas', $idArea)
->whereYear('votos.dataCriado', $anoDeEleicao)
->whereNotIn('Partidos_idPartidos', [14, 16])
->groupBy('partidos.idPartidos', 'acronimo')
->orderBy('total_votos', 'desc')
->get();

// Contar o número de inscritos para o ano atual na mesa específica
$inscritos = DB::table('mesas')
    ->where('mesas.idMesas', $idArea)
    ->sum('mesas.eleitores');
// Somar o total de votos na mesa específica
$totalVotos = DB::table('votos')
->where('votos.mesas_idMesas', $idArea)
->sum('votos.contador');

// Somar o total de votos válidos na mesa específica
$totalValidos = DB::table('votos')
->where('votos.mesas_idMesas', $idArea)
->whereNotIn('Partidos_idPartidos', [14, 16])
->sum('votos.contador');

// Somar o total de votos nulos na mesa específica
$totalVotosNulo = DB::table('votos')
->where('votos.mesas_idMesas', $idArea)
->where('Partidos_idPartidos', 14)
->sum('votos.contador');

// Somar o total de votos em branco na mesa específica
$totalVotosBranco = DB::table('votos')
->where('votos.mesas_idMesas', $idArea)
->where('Partidos_idPartidos', 16)
->sum('votos.contador');

// dados anteiror
// Obter a mesa de votação atual
$mesaAtual = DB::table('mesas')
    ->where('idMesas', $idArea)
    ->first();

// Obtém todas as mesas anteriores no mesmo local de votação
$mesas = DB::table('mesas')
    ->where('localDeVotacao_idLocalDeVotacao', $mesaAtual->localDeVotacao_idLocalDeVotacao)
    ->get();

// Obter o local de votação atual da mesa
$localAtual = DB::table('localdevotacao')
    ->where('idLocalDeVotacao', $mesaAtual->localDeVotacao_idLocalDeVotacao)
    ->first();

$locais = DB::table('localdevotacao')
 ->where('distritos_idDistritos', $localAtual->distritos_idDistritos)->get();

// Obter o distrito atual do local de votação
$distritoAtual = DB::table('distritos')
    ->where('idDistritos', $localAtual->distritos_idDistritos)
    ->first();
// Obtém todos os distritos da mesma província do distrito atual
$distritos = DB::table('distritos')
    ->where('provincias_idProvincias', $distritoAtual->provincias_idProvincias)
    ->get();
}
if($area != "provincias" && $area != "distritos" && $area != "centros" && $area != "mesas"){
  return back();
}
    return view('/principal', [
     "resultados" => $resultados,
        "totalVotos" =>  $totalVotos,
        "totalValidos" => $totalValidos,
        "totalVotosBranco" => $totalVotosBranco,
        "totalVotosNulo" => $totalVotosNulo,
        "inscritos" => $inscritos,
        "provincias" => $provincias,
        "distritos" => $distritos,
        "locais" => $locais,
        "mesas" => $mesas,
        "todasEleicoes" => $todasEleicoes,
        "eleicao"=>$eleicao,
        "ano" => $anoDeEleicao,
        "anoDeEleicao" => $anoDeEleicao,
        "relatorioID" => $relatorioID
    ]);
});



// verficar dados de login para retornar painel de admin....
Route::post('/adminsLogin', function (LoginValidator $request) {
    $email = $request->email;
    $pass = $request->password;
    $admins = administradores::where("email", $email)->first();

    if (!$admins) {
        $erro = "Dados Incorretos";
        return back()->with('erro', $erro);
    }

    if ($admins->email == $email && $admins->senha == md5($pass)) {

        session()->put("administradores", $admins);
        $erro = "Dados Aceite...";
        $categoriaDeAdmin = categoriaDeAdmin($admins);
        // Dados da provincia
        if($admins->nivel == 1){
            $pontosDeRelatorio = provincias::all();
        }
        // Dados dos distritos
        $local =0;

        if($admins->nivel == 2){
            $pontosDeRelatorio = distritos::where('provincias_idProvincias', $admins->provincias_idProvincias)->get();
            $local = Provincias::where('idProvincias', $admins->provincias_idProvincias)->first();

        }
        if($admins->nivel == 3){
            $pontosDeRelatorio = localdevotacao::where('distritos_idDistritos', $admins->distritos_idDistritos)->get();
            $local = distritos::where('idDistritos', $admins->distritos_idDistritos)->first();
        }

        $ultimoAno = Config::orderBy('ano', 'desc')->first();
        return view('/telaAdminis', [
            "admins" => $admins,
            "administradores" => [],
            "categoriaDeAdmin" => $categoriaDeAdmin,
            "pontosDeRelatorio" => $pontosDeRelatorio,
            "anoEleicoes" => $ultimoAno,
            "local" => $local
        ]);


    } else {
        $erro = "Dados Incorretos";
        return back()->with('erro', $erro);
    }
});

//redirecionar novos Resultados

Route::get('/RelatorioSelecionado/{local?}/{ano?}', function ($local =0, $ano = 0) {

    $admins = session()->get("administradores");
        $categoriaDeAdmin = categoriaDeAdmin($admins);
        // Dados da provincia
        if($admins->nivel == 1){
            $pontosDeRelatorio = provincias::all();
        }
        // Dados dos distritos
        $local =0;

        if($admins->nivel == 2){
            $pontosDeRelatorio = distritos::where('provincias_idProvincias', $admins->provincias_idProvincias)->get();
            $local = Provincias::where('idProvincias', $admins->provincias_idProvincias)->first();
        }

        if($admins->nivel == 3){
            $pontosDeRelatorio = localdevotacao::where('distritos_idDistritos', $admins->distritos_idDistritos)->get();
            $local = distritos::where('idDistritos', $admins->distritos_idDistritos)->first();
        }

        $ultimoAno = Config::orderBy('ano', 'desc')->first();
        return view('/telaAdminis', [
            "admins" => $admins,
            "administradores" => [],
            "categoriaDeAdmin" => $categoriaDeAdmin,
            "pontosDeRelatorio" => $pontosDeRelatorio,
            "anoEleicoes" => $ultimoAno,
            "local" => $local
        ]);
});


//...Destrruir a sessao...
Route::get("/sair", function () {
    session()->forget("administradores");
    return redirect("/adminsLogin");
});


//...Trazer categoria de admin de acordo com nivel sessao (Terminado)...
function categoriaDeAdmin($admins)
{
    $categoriaDeAdmin = '0';
    // Configurar Tipo de eleicoes e ano.....
    if ($admins->nivel == 1) {
        if (session()->get("administradores.nivel") == 1) {
            return $categoriaDeAdmin = "Administrador Provincial";
        }

        if (session()->get("administradores.nivel") == 2) {
            return  $categoriaDeAdmin = "Administrador Distrital";
        }

        if (session()->get("administradores.nivel") == 3) {
            return  $categoriaDeAdmin = "Presidente da Mesa";
        }

        return "Null";
    }
}


//.... Retornar Viem Principal....
Route::get(
    '/principalAdmin',
    function () {
        $admins = session()->get("administradores");
        $categoriaDeAdmin = categoriaDeAdmin($admins);
        return view('/principal', [
            "admins" => $admins,
            "administradores" => [],
            "categoriaDeAdmin" => $categoriaDeAdmin
        ]);
    }
)->middleware(["auteticarAdminis"]);


// Entrar no campo de registar ADMIN..... (...Ainda nao terminou...)
Route::get('/registar/{id?}', function ($id = 1) {

    $admins = session()->get("administradores");
    if ($admins->nivel == '1') {
        $res = provincias::orderBy('idProvincias', 'asc')->get();

        return view('/registo', ["provincias" => $res]);
    }
    if ($admins->nivel == '2') {
        $res = distritos::where('provincias_idProvincias',  $admins['provincias_idProvincias'])
            ->get();
        $res1 = provincias::where('idProvincias', $admins['provincias_idProvincias'])->first();
        return view('/registo', ["distritos" => $res], ["nomeProvincia" => $res1]);
    }

    if ($admins->nivel == '3') {
        //...Presidente de mesa....
        $res = administradores::join('mesas', 'administradores.mesas_IdMesas', '=', 'mesas.idMesas')
        ->leftJoin('localdevotacao', 'mesas.localDeVotacao_idLocalDeVotacao', '=', 'localdevotacao.idLocalDeVotacao')
        ->whereNotNull('administradores.mesas_IdMesas')
        ->where('localdevotacao.idLocalDevotacao', $id)
        ->orderBy('localdevotacao.nome', 'asc')
        ->get(['administradores.primeiroNome as nomeAdmin', 'administradores.segundoNome as apelidoAdmin',
         'localdevotacao.nome as nomeLocal', 'mesas.nome as nomeMesa', 'mesas.idMesas as idMesas', 'mesas.eleitores as eleitores']);
            $local = localdevotacao::where('idLocalDeVotacao', $id) -> first();
            return view('/registo', [
                "dados" => $res,
                "id" => $id,
                "local" => $local
            ]);
    }
})->middleware(["auteticarAdminis"]);

//Erros de Registar admins
function ERROS($ibExiste, $contactoExiste, $senha, $senha1)
{
    if ($ibExiste) {
        return "Administrador com BI igual Registado";
    }
    if ($contactoExiste) {
        return  "Administrador com contacto igual Registado";
    }
    if ($senha != $senha1) {
        return "Erro na confirmação da senha. As senhas fornecidas não correspondem";
    }
}

// Registar administradores...
Route::post('/registar', function (ValidadorAdministradores $request) {
    $admins = session()->get("administradores");
    $primeiroNome = $request->primeiroNome;
    $segundoNome = $request->segundoNome;
    $senha = $request->senha;
    $contacto = $request->contacto;
    $senha1 = $request->senha1;
    $bi = $request->bi;
    $email = $request ->email;

    $emailExiste = administradores::where('email', $email)->first();
    $ibExiste = administradores::where('bi', $bi)->first();
    $contactoExiste = administradores::where('contacto', $contacto)->first();

        if($emailExiste){
            $erro = "Tem administrador com esse email";
            return back()->with('erro', $erro);
        }
    //Registar admin Provincial....
    if ($admins->nivel == 1) {
        $provincia = $request->provincia;
        $adminExiste = administradores::where('provincias_idProvincias', $provincia)->first();
        if($adminExiste) {
            $erro = "Esta provincia tem administrador";
            return back()->with('erro', $erro);
        }
        if (!$provincia) {
            $erro = "Selecione o campo de provincia";
            return back()->with('erro', $erro);
        }

        $erro = ERROS($ibExiste, $contactoExiste, $senha, $senha1);
        if ($erro) {
            return back()->with('erro', $erro);
        }
       administradores::create([
            "primeiroNome" => $primeiroNome,
            "segundoNome" => $segundoNome,
            "senha" =>    md5($senha),
            "contacto" => $contacto,
            "bi" => $bi,
            "provincias_idProvincias" => $provincia,
            "email" => $email,
            "nivel" => "2"
        ]);
        $erro = "Registado com Sucesso";
        return redirect()->back()->with("erro", $erro);
    }

    //Registar admin Distrital.....
    if ($admins->nivel == 2) {
        $distrito = $request->distrito;
        $adminExiste = administradores::where('distritos_idDistritos', $distrito)->first();

        if($adminExiste) {
            $erro = "Este distrito tem administrador";
            return back()->with('erro', $erro);
        }

        if (!$distrito) {
            $erro = "Selecione o campo o distrito";
            return back()->with('erro', $erro);
        }

        $erro = ERROS($ibExiste, $contactoExiste, $senha, $senha1);
        if ($erro) {
            return back()->with('erro', $erro);
        }
        administradores::create([
            "primeiroNome" => $primeiroNome,
            "segundoNome" => $segundoNome,
            "senha" =>    md5($senha),
            "contacto" => $contacto,
            "bi" => $bi,
            "provincias_idProvincias" => $admins->provincias_idProvincias,
            "nivel" => "3",
            "distritos_idDistritos" =>  $distrito,
            "email" => $email
        ]);
        $erro = "Registado com Sucesso";
        return redirect()->back()->with("erro", $erro);
    }

    //Registar presidente da  mesa......
    if ($admins->nivel == 3) {
        // Registar presidente.....
        $mesa = $request->codigoDaMesa;
        $nrDeEleitores = $request->nrDeEleitores;
        $localDeVotacao_idLocalDeVotacao = $request->localDeVotacao_idLocalDeVotacao;

        if (!$mesa) {
            $erro = "O campo de Código/nome da mesa é obrigatório";
            return back()->with('erro', $erro);
        }

        if(!$nrDeEleitores) {
            $erro = "O campo número de eleitores é obrigatório";
            return back()->with('erro', $erro);
        }

        if($nrDeEleitores <0) {
            $erro = "O campo de número de eleitores deve ser maior que 0";
            return back()->with('erro', $erro);
        }

        if(!$localDeVotacao_idLocalDeVotacao){
            $erro = "Erro... Contacta administradores do Sistema";
            return back()->with('erro', $erro);
        }

        $mesa = $mesa . '-' . Carbon::now()->year ;
        $erro = ERROS($ibExiste, $contactoExiste, $senha, $senha1);
        if ($erro) {
            return back()->with('erro', $erro);
        }

        mesas::create([
            "nome" => $mesa,
            "contador" => 0,
            "eleitores" => $nrDeEleitores,
            "localDeVotacao_idLocalDeVotacao" => $localDeVotacao_idLocalDeVotacao
        ]);
        $mesaCriada = mesas::where('nome', $mesa) -> first();
        administradores::create([
            "primeiroNome" => $primeiroNome,
            "segundoNome" => $segundoNome,
            "senha" =>    md5($senha),
            "contacto" => $contacto,
            "bi" => $bi,
            "provincias_idProvincias" => $admins->provincias_idProvincias,
            "distritos_idDistritos" => $admins->distritos_idDistritos,
            "nivel" => 4,
            "mesas_idMesas" => $mesaCriada->idMesas,
            "email" => $email
        ]);
        $erro = "Registado com Sucesso";
        return redirect()->back()->with("erro", $erro);
    }
})->middleware(["auteticarAdminis"]);

// .......Filtrar Administradores......
Route::get('/filtroAdmin', function () {
    $admins = session()->get("administradores");
    $categoriaDeAdmin = categoriaDeAdmin($admins);
    // Pegar Adminis Provincial
    if ($admins->nivel == '1') {
        $res = administradores::join('provincias', 'provincias_IdProvincias', '=', 'idProvincias')
            ->whereNull('administradores.distritos_IdDistritos')
            ->whereNotNull('administradores.provincias_IdProvincias')
            ->orderBy('administradores.provincias_IdProvincias', 'asc')
            ->get(['administradores.*', 'provincias.nome as nomeArea']);
        return view('/mostrarAdmins', [
            "administradores" => $res,
            "admins" => $admins,
            "categoriaDeAdmin" => $categoriaDeAdmin,
            "area" => "Provincia",
        ]);
    }
    //pegar Admins Distrital
    if ($admins->nivel == '2') {

            //Todos presidentes do distrito
            $res = administradores::join('distritos', 'distritos_IdDistritos', '=', 'idDistritos')
            ->where('administradores.provincias_idProvincias', $admins->provincias_idProvincias)
            ->whereNull('administradores.mesas_idMesas')
            ->whereNotNull('administradores.distritos_IdDistritos')
            ->orderBy('administradores.distritos_idDistritos', 'asc')
            ->get(['administradores.*', 'distritos.nome as nomeArea']);
        return view('/mostrarAdmins', [
            "administradores" => $res,
            "admins" => $admins,
            "categoriaDeAdmin" => $categoriaDeAdmin,
            "area" => "Distrito",
        ]);


    }
    // Mostrar presidentes
    if ($admins->nivel == '3') {
        // Pegar todos presidentes do distrito
        $res = administradores::join('mesas', 'administradores.mesas_IdMesas', '=', 'mesas.idMesas')
        ->leftJoin('localdevotacao', 'mesas.localDeVotacao_idLocalDeVotacao', '=', 'localdevotacao.idLocalDeVotacao')
        ->whereNotNull('administradores.mesas_IdMesas')
        ->where('localdevotacao.distritos_idDistritos', $admins->distritos_idDistritos)
        ->orderBy('localdevotacao.nome', 'asc')
        ->get(['administradores.*', 'localdevotacao.nome as nomeArea', 'mesas.nome as idMesa']);
        return view('/mostrarPresidente', [
            "presidentes" => $res,
            "admins" => $admins,
            "catergoriaAdmin" => $categoriaDeAdmin
        ]);

    }
})->middleware(["auteticarAdminis"]);


// ....Apagar Admin (Terminado)....

Route::delete('/apagarAdmin/{id?}', function ($id = 10) {
    administradores::find($id)->delete();
    return back();
})->middleware(["auteticarAdminis"]);


//... Entrar na pagnia de actualizar Dados (Terminado).....
Route::get('editar/{id?}', function ($id = 10) {
    $admin =  administradores::where('contacto', '=', $id)->first();
    $admins = session()->get("administradores");
        if ($admins->nivel <4 && $admins->nivel >0) {
            return view('/editeAdmin', [
                "admin" => $admin,
            ]);
    }
})->middleware(["auteticarAdminis"]);

//....Actualizar dados de  Admin (Terminado)...
Route::put('/editarAdmin/{id?}', function ($id = 10, validarEditarAdmin $request) {
    $admins = session()->get("administradores");
    $registo = administradores::find($id);
    if (!$registo) {
        return back();
    }
    // Tentar actualizar
    $primeiroNome = $request->primeiroNome;
    $segundoNome = $request->segundoNome;
    $contacto = $request->contacto;
    $bi = $request->bi;

    $ibExiste = administradores::where('bi', $bi)
    ->where('idAdministradores', '!=', $registo->idAdministradores)
        ->first();
    $contactoExiste = administradores::where('contacto', $contacto)
    ->where('idAdministradores', '!=', $registo->idAdministradores)
        ->first();

        if ($ibExiste) {

            return redirect()->back()->with("erro", "Administrador com BI igual Registado");
         }
        if ($contactoExiste) {
            return redirect()->back()->with("erro", "Administrador com contacto igual Registado");
        }
        $registo->update([
            'primeiroNome' => $primeiroNome,
            'segundoNome' => $segundoNome,
            'contacto' => $contacto,
            'bi' => $bi,
        ]);
    $erro = "Dados Editados Com Sucesso...";
    return redirect()->back()->with("erro", $erro);
})->middleware(["auteticarAdminis"]);

//...Entrar nas configuracoes de Eleicoes  (Terminado).....
Route::get('/configE', function () {

    $admins = session()->get("administradores");
    if ($admins->nivel == 1) {
        $dataActual = Carbon::now()->format('Y-m-d');
        $ultimoDiaAnoAtual = Carbon::now()->endOfYear()->format('Y-m-d');
        $dataAtual = Carbon::now()->format('Y-m-d'); // Data atual
        $dataDoisDiasDepois = Carbon::now()->addDays(2)->format('Y-m-d'); // Data dois dias depois da data atual
    $distritos = distritos::orderBy('provincias_idProvincias', 'asc')->get();
    $config = config::leftJoin('distritos', 'distritos_idDistritos', '=', 'idDistritos')
        ->orderBy('ano', 'desc')
        ->get(['config.*', 'distritos.nome as nomeDistrito' ]);

        // $dataHoje = Carbon::now();
        $dataHoje = Carbon::now();

        $estados = [];

        foreach ($config as $config1) {
            // Combina dataInicio com horaInicio e dataFim com horaFim
            $dataInicio = Carbon::createFromFormat('Y-m-d H:i:s', $config1->dataInicio . ' ' . $config1->horaInicio);
            $dataFim = Carbon::createFromFormat('Y-m-d H:i:s', $config1->dataFim . ' ' . $config1->horaFim);

            $estado = "Por Iniciar";


            if ($dataFim < $dataHoje) {
                $estado = "Terminado";
            } elseif ($dataHoje >= $dataInicio && $dataHoje <= $dataFim) {
                $estado = "Decorrendo";
            }
            // Adiciona o estado ao array
            $estados[] = $estado;
        }
        $categoriaDeAdmin = categoriaDeAdmin($admins);
        return view('/config', [
            "admins" => $admins,
            "administradores" => [],
            "categoriaDeAdmin" => $categoriaDeAdmin,
            "distritos" => $distritos,
            "anoActual" => Carbon::now()->year,
            "config" => $config,
            "estado" => $estados,
            "dataActual" =>$dataActual,
            "ultimoDiaAnoAtual" => $ultimoDiaAnoAtual,
            "dataDoisDiasDepois" => $dataDoisDiasDepois
        ]);
    } else {
        return redirect()->back();
    }
})->middleware(["auteticarAdminis"]);

//....Registar Tipo de eleicoes (Terminado).....

Route::post('/configTipoEleicao', function (validadorConfig $request) {

        $admins = session()->get("administradores");
        if ($admins->nivel == 1) {
            $ano =  Carbon::now()->year;
            $tipoEleicoes = $request->tipoEleicoes;
            $distrito = $request->distrito;
            $inicio = $request -> inicio;
            $fim = $request -> fim;

            $hoje = Carbon::today();
            $anoActual = Carbon::now()->year;
            $TipoEleicaoExiste = config:: where('dataFim', '>=', $hoje )
            ->first();

            if ($TipoEleicaoExiste) {
                return redirect()->back()->with("erro", "Ja Existe eleição por acontecer este ano");
            }
            if ($TipoEleicaoExiste) {
                return redirect()->back()->with("erro", "Esta eleição já foi registrada para este ano");
            }
            $inicioDate = Carbon::createFromFormat('Y-m-d\TH:i', $inicio);
            $fimDate = Carbon::createFromFormat('Y-m-d\TH:i', $fim);

            if (!$fimDate->greaterThan($inicioDate)) {
                return redirect()->back()->with("erro", "A data e hora de fim deve ser posterior à data e hora de início");
            }

            $diffInDays = $fimDate->diffInDays($inicioDate);

            if ($diffInDays > 1) {
                return redirect()->back()->with("erro", "A diferença entre a data de fim e a data de início deve ser de no máximo 1 dia");
            }
            $inicio = explode('T', $inicio);
            $diaInicio = $inicio[0];
            $horaInicio = $inicio[1];

            $fim = explode('T', $fim);
            $diaFim = $fim[0];
            $horaFim = $fim[1];

            $resultado = config::create([
                "ano" => $ano,
                "tipoEleicao" => $tipoEleicoes,
                "distritos_idDistritos" => $distrito,
                "administradores_idAdministradores" => $admins->idAdministradores,
                "dataInicio" => $diaInicio,
                "dataFim" => $diaFim,
                "horaInicio"=> $horaInicio,
                "horaFim" => $horaFim
            ]);
            return redirect()->back();
        }
    }
);

// Actualizar data de Contagem de Votos (.....Terminado.....)
Route::put('actualizarContagem/{id?}', function ($id = 10, actualizarConfig $request) {
    $admins = session()->get("administradores");

    if ($admins->nivel == 1) {
        $dataActualizar = $request->data;
        // Selecionar a eleicao desejada....
        $config = config::where('idConfig', $id)
            ->first();
            $dbDataFim = $config->dataFim;
            $dbHoraFim = $config ->horaFim;

            $dataFim = Carbon::createFromFormat('Y-m-d H:i:s', $dbDataFim . ' ' . $dbHoraFim);
            $data = Carbon::createFromFormat('Y-m-d\TH:i', $dataActualizar);

            if (!$data->greaterThan($dataFim)) {
                return redirect()->back()->with("erro", "A data e hora por actualizar deve ser posterior à data termino");
            }
            $diffInDays = $dataFim->diffInDays($dataActualizar);
            IF($diffInDays > 1){
                return redirect()->back()->with("erro", "A data e hora por actualizar nao pode ultrapassar um dia");
            }

            $diffInDays = $data->diffInDays($dataFim);
            $dataActualizar = explode('T', $dataActualizar);
            $dataActual = $dataActualizar[0];
            $horaActual = $dataActualizar[1];
            $anoActual = Carbon::now()->year;

        //Actualizar contagem de votos ....
        $config->update([
            'dataFim' => $dataActual,
            'horaFim' => $horaActual
        ]);

        return redirect()->back()->with("erro", "Município Adcionado Com Sucesso...");
    } else {
        return redirect()->back();
    }
})->middleware(["auteticarAdminis"]);

//...Entrar nas configuracoes de Municipio (Terminado).....

Route::get('/configM', function () {
    $admins = session()->get("administradores");
    $distritosM = distritos::where('municipio', '1')
        ->orderBy('provincias_idProvincias', 'asc')->get();

        $distritosM = distritos::where('municipio', '1')
        ->join('provincias', 'distritos.provincias_idProvincias', '=', 'provincias.idProvincias')
        ->orderBy('provincias_idProvincias', 'asc')
        ->select('distritos.*', 'provincias.nome as provinciaNome')
        ->get();

    $provincia = provincias::orderBy('idProvincias', 'asc')->get();

    if ($admins->nivel == 1) {
        $categoriaDeAdmin = categoriaDeAdmin($admins);
        return view('/municipio', [
            "admins" => $admins,
            "administradores" => [],
            "categoriaDeAdmin" => $categoriaDeAdmin,
            "distritosM" => $distritosM,
            "provincias" => $provincia
        ]);
    } else {
        return redirect()->back();
    }
})->middleware(["auteticarAdminis"]);



// ..Adcionar novo municio (Terminado)...

Route::put('/novoM', function (validadormunicipio $request) {
    $admins = session()->get("administradores");
    if ($admins->nivel == 1) {
        // Tentar actualizar
        $provincia = $request->provincia;
        $distrito = $request->distrito;
        $distrito = distritos::where('idDistritos', $distrito)
            ->where('provincias_idProvincias', $provincia)
            ->first();

        //Actualizar admin Provincial....
        $distrito->update([
            'municipio' => 1
        ]);
        $erro = "Município Adcionado Com Sucesso...";
        return redirect()->back()->with("erro", $erro);
    } else {
        return redirect()->back();
    }
})->middleware(["auteticarAdminis"]);

// Retornar Partidos......

Route::get('/partidos', function (){
    $admins = session()->get("administradores");
    if ($admins->nivel == '1') {
        $res = partidos::whereNotNull('logo')
        ->orderBy('ordem', 'asc')
         -> get();
        return view('/partidos', [
            "partidos" => $res,
        ]);
    }
}) ->middleware(["auteticarAdminis"]);


// Adcionar novos partidos....
Route::POST('/partidos', function (validadorPartidos $request) {
    $admins = session()->get("administradores");
    if ($admins->nivel == 1) {
        $nome = $request->input('nome');
        $acronimo = $request->input('acronimo');

        $partidoExiste = partidos::where('acronimo', $acronimo)
            ->orWhere('nome', $nome)
            ->first();

        if ($partidoExiste) {
            return redirect()->back()->with("erro", "Este Partido já está registado");
        }

        // Lidar com o upload do logo
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo')->store('logos', 'public');
        } else {
            return redirect()->back()->with("erro", "O upload do logo é obrigatório");
        }

        // Criar o novo partido
        partidos::create([
            "nome" => $nome,
            "acronimo" => $acronimo,
            "logo" => $logo
        ]);

        return redirect()->back()->with("erro", "Partido registado com sucesso");
    } else {
        return redirect()->back();
    }
})->middleware(["auteticarAdminis"]);


// Ordenar Partidos (Terminado)...

Route::get('/reordenar', function (){
    $admins = session()->get("administradores");
    if ($admins->nivel == '1') {
        $res = partidos::whereNotNull('logo')
        ->orderBy('ordem', 'asc')
         -> get();
        return view('/reOrdenarPartidos', [
            "partidos" => $res,
        ]);
    }
}) ->middleware(["auteticarAdminis"]);


Route::put('/reOrdenar', function (Request $request) {
    $admins = session()->get("administradores");

    if ($admins && $admins->nivel == 1) {
        $request->validate([
            'ordem.*' => 'required|integer|distinct|min:1',
        ], [
            'ordem.*.required' => 'Todos os campos de ordem devem ser preenchidos.',
            'ordem.*.integer' => 'Todos os campos de ordem devem ser números inteiros.',
            'ordem.*.distinct' => 'Os valores de ordem devem ser distintos.',
            'ordem.*.min' => 'Os valores de ordem devem ser maiores que 0.',
        ]);

        DB::beginTransaction();

        try {
            $ordens = $request->input('ordem');
            $partidos = partidos::all();

            foreach ($partidos as $index => $partido) {
                // Adicionar a condição para não atualizar partidos com ordem 500, 501 ou logo null
                if (in_array($partido->ordem, [500, 501]) || is_null($partido->logo)) {
                    continue; // Pular para o próximo partido
                }

                if (isset($ordens[$index])) {
                    $partido->ordem = $ordens[$index];
                    $partido->save();
                } else {
                    DB::rollBack();
                    return redirect()->back()->with("erro", "Todos campos devem ser preenchidos corretamente");
                }
            }

            DB::commit();
            return redirect("/partidos")->with("sucesso", "Ordem dos partidos atualizada com sucesso...");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with("erro", "Erro ao atualizar a ordem dos partidos. Tente novamente.");
        }
    } else {
        return redirect()->back()->with("erro", "Permissão negada.");
    }
})->middleware(["auteticarAdminis"]);

//Imprimir Boletins de voto...

Route::get('/imprimirBoletins/{id?}', function ($id=0) {
    $admins = session()->get("administradores");

    $provincia = provincias::where('idProvincias', $admins->provincias_idProvincias)->first("provincias.nome");
    $distrito = distritos::where('idDistritos', $admins->distritos_idDistritos)->first("distritos.nome");
    $mesa = mesas::where('idMesas', $id)->first();
    $local = localdevotacao::where('idLocalDeVotacao', $mesa->localDeVotacao_idLocalDeVotacao)
    ->first("localdevotacao.nome");
    $boletins = boletins::where('mesas_idMesas', $id)->get();

    $partidos = Partidos::whereNotNull('logo')
    ->orderBy('ordem', 'ASC')->get();
    if ($admins->nivel == 3) {
        return view('/imprimirBoletins', [
            "local" => $local,
            "provincia" => $provincia,
            "distrito" => $distrito,
            "mesa" => $mesa,
            "boletins" => $boletins,
            "partidos" => $partidos
        ]
        );
    }
})->middleware(["auteticarAdminis"]);

// Gerir Locais de votacao  (Terminado)...
    Route::get("/gerirLocal", function () {
        $admins = session()->get("administradores");
        $categoriaDeAdmin = categoriaDeAdmin($admins);
        $dataHoje = Carbon::now()->format('Y-m-d');
            $existeEleicao = config::where('dataInicio', '>', $dataHoje)
                ->whereNull('distritos_idDistritos')
                ->orWhere('distritos_idDistritos', $admins->distritos_idDistritos)
               ->first();

        $anoActual = Carbon::now()->year;

        $locais = localdevotacao::select(
                    'localdevotacao.*',
                    DB::raw('(SELECT SUM(eleitores)
                              FROM mesas
                              WHERE mesas.LocalDeVotacao_idLocalDeVotacao = localdevotacao.idLocalDeVotacao) as eleitores'),
                    DB::raw('(SELECT COUNT(*)
                              FROM administradores
                              WHERE administradores.mesas_idMesas IN (
                                SELECT idMesas
                                FROM mesas
                                WHERE mesas.LocalDeVotacao_idLocalDeVotacao = localdevotacao.idLocalDeVotacao
                              )) as adminsMesas')
                    )
                    ->where('distritos_idDistritos', $admins->distritos_idDistritos)
                    ->whereYear('datacriado', $anoActual)
                    ->get();

        return view('/gerirLocal', [
            "locais" => $locais,
            "existeEleicao" => $existeEleicao
        ]);
    })->middleware(["auteticarAdminis"]);

Route::post('/local', function (validadorlocais $request) {
    // Validação dos dados do formulário    $validated = $request->validated();
    // Criação de uma nova instância do modelo e atribuição dos dados validados;
    $admins = session()->get("administradores");
    if($admins->nivel == 3){

        $local = new LocalDeVotacao();
        $local->nome = $request['nome'];
        $local->outroNome = $request['outroNome'];
        $existelocal = localdevotacao::where('nome', $local->nome)
        ->orWhere('outroNome', $local->outroNome)
        ->first();
        if($existelocal){
            return redirect()->back()->with('erro', 'Local com dados iguais Registado');
        }
        $local->distritos_idDistritos = $admins->distritos_idDistritos;
        $local->save();
    } else {
        return redirect()->back();
    }
    // Redirecionamento de volta com uma mensagem de sucesso
    return redirect()->back()->with('erro', 'Local registrado com sucesso!');
})->middleware(["auteticarAdminis"]);


