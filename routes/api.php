<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\boletins;
use App\votos;
use App\mesas;
use App\partidos;
use App\config;
use App\administradores;
use App\Http\Requests\LoginValidator;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// verficar dados de login para retornar painel de admin....
Route::post('/adminsLogin', function (LoginValidator $request) {

    $email = $request->email;
    $pass = $request->password;
    $admins = administradores::where("email",$email)->first();



    if (!$admins || $admins->nivel != 4 || md5($pass) != $admins->senha) {
        $erro = "Dados Incorretos";
        return response()->json(["erro" =>$erro]);
    }


    if ($admins->email == $email && $admins->senha == md5($pass) ) {
        $otp = rand(1000,9999);

        $to = $admins->email;
        $subject = " OTP de Verificacao";
        $txt = "O seu OTP eh: ".$otp;
        $headers = "From: webmaster@example.com";

        //mail($to,$subject,$txt,$headers);
        // update
        $admins->update([
            'otp' => $otp,
        ]);

        return response()->json(["otp" =>$otp]);
    } else {
        $erro = "Dados Incorretos";
        return response()->json(["erro" =>$erro]);
    }
});

// verficar dados de OTP para retornar painel de admin....
Route::post('/otp', function (Request $request) {
    $otp = $request->otp;
    $email = $request->email;

    $admins = administradores::where("email",$email)->first();
    if (!$otp) {
        $erro = "Dados Incorretos";
        return response()->json(["erro" =>$erro]);
    }

    if ($admins->otp == $otp) {
        return response()->json([
            "admins" =>$admins
        ]);
    } else {
        $erro = "OTP invalido";
        return response()->json(["erro" =>$erro]);
    }
});

// dados Para Contar Votos....
Route::post('/menu', function (Request $request) {
    $idMesa = $request->mesas_idMesas;

    $boletins = boletins::where("mesas_idMesas", $idMesa)->get();
    $contados = boletins::where("estado", 1)
        ->where("mesas_idMesas", $idMesa)
        ->get();
    $nomeMesa = mesas::where("idMesas", $idMesa)->first();
    $emFalta = boletins::where("estado", 0)
        ->where("mesas_idMesas", $idMesa)
        ->get();

    return response()->json([
        "boletins" => count($boletins),
        "contados" => count($contados),
        "emFalta" => count($emFalta),
        "nomeMesa" => $nomeMesa
    ]);
});


Route::post('/contar', function (Request $request) {

    $votos = votos::where("mesas_idMesas",$request->idMesa)->get();
    $qrCode = $request->qrCode;
    $idMesa = $request->idMesa;
    $boletins = boletins::where("qrCode", $qrCode)->where("estado",0)->where("mesas_idMesas", $request->idMesa)
    ->first();

    $time = time();
    $lctime = localtime($time,true);

    $yearTime = config::where("ano",($lctime['tm_year'] + 1900))->first();

    if(!$yearTime){

        return response()->json([
            "mensagem" =>"Fora do tempo de voto"
        ]);
    }

    $timeEnd = strtotime($yearTime->dataFim . " " . $yearTime->horaFim);
    $timeStart = strtotime($yearTime->dataInicio . " " . $yearTime->horaInicio);

    // Create current date-time string using date function for readability
    $date = date("Y-m-d H:i:s", time());
    $today = strtotime($date);

    if ($today < $timeStart || $today > $timeEnd) {
        return response()->json([
            "mensagem" =>"Fora do tempo de voto",
        ]);
    }


    if ($boletins) {

        return response()->json([
            "mensagem" =>"Boletim Valido",
        ]);
    } else {
        return response()->json(["mensagem" =>"Boletim Invalido"]);
    }
});


Route::post('/votar', function (Request $request) {

    $partido = $request->partido;
    $mesa = $request->mesa;
    $partido = partidos::where("acronimo",$partido)->first();
    $time = time();
    $lctime = localtime($time,true);
    $voto = votos::where("mesas_idMesas", $mesa)->where("partidos_IdPartidos",$partido->idPartidos)->where("dataCriado","like",($lctime['tm_year'] + 1900).'%')
    ->first();

    $boletins = boletins::where("mesas_idMesas", $request->mesa)->where("qrCode",$request->qrcode)
    ->first();



    if(!$voto){
        $response = votos::create([
            "contador" => 1,
            "mesas_idMesas" => $mesa,
            "partidos_IdPartidos" => $partido->idPartidos,
        ]);

        return response()->json([
            "mensagem" =>"Voto com sucesso",
            "bolentins"=>$boletins
        ]);
    }



    $response = $voto->update([
        "contador" => intval($voto->contador) + 1,
        "mesas_idMesas" => $mesa,
        "partidos_IdPartidos" => $partido->idPartidos,
    ]);

    boletins::where("idBoletins", $boletins->idBoletins)->update([
        "estado" => 1
    ]);

    return response()->json([
        "mensagem" =>"Voto com sucesso",
        "bolentins"=>$boletins
    ]);
});




