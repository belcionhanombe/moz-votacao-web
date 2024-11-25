@extends('layout.layout')
@section('titulo', 'imprimirBoletins')
@section('conteudo')

@if (session()->get('administradores.nivel') == 3)
@foreach ($boletins as $boletim)
<div class="imprimir page-break">
    <table id="tabalaCab">
        <tr>
            <td style="width: 30% !important">
                <div class="d-flex justify-content-center">
                    <img src="{{ asset('assets/fotos/cne.png') }}" class="card-img-top" alt="cne" style="width: 35%;">
                </div>
                <div class="card-body" style=" padding:0% !important;">
                    <p class="card-text" style="margin-top:0% !important;  font-size:12px !important;">
                        {{$provincia->nome}} - {{$distrito->nome}}
                       <br> {{ $local->nome }}
                        <br>{{$mesa->nome}}
                    </p>
                </div>
            </td>

<td style="width: 60% !important">
                <div class="d-flex justify-content-center">
                    <img src="{{ asset('assets/fotos/emblema.png') }}" class="card-img-top" alt="cne"
                        style="width: 20%;">
                </div>
                <div class="card-body" style=" padding:0% !important; ">
                    <p class="card-text" style="margin-top:0% !important;">
                        <b>
                            República de Moçambique
                            <br>Comissão Nacional de Eleições
                            <br>Eleições Presidenciais
                        </b>
                    </p>
                </div>
            </td>
        </tr>
    </table>
    <br>
    <div class="col-md-2">
        <div class="qrcode"></div>
        <input type="hidden" name="" class="codigo"
            value="JHSJsmsjsjsj">
    </div>

    <div style="margin-top: 4% !important; text-alin: center !important">
        <table id="boletins">

            @foreach ($partidos as $partido)
            <tr>
                <b>
                    <td class="col-70">
                        <h1>{{$partido->nome }}</h1>
                    </td>
                    <td class="col-10">
                        <h1>{{$partido->acronimo}}</h1>
                    </td>
                    <td class="col-10"><img src="{{ asset('storage/' . $partido->logo) }}" class="card-img-top" style="width: 100%;  height: 50%;"></td>
                    <td></td>
                </b>
            </tr>
            @endforeach


        </table>
    </div>

</div>
@endforeach
@endif

@section('js')
<script src="{{ asset('assets/jquery.min.js') }}"></script>
<script src="{{ asset('assets/qrcode.min.js') }}"></script>

<script>
    var qrcodes = document.querySelectorAll(".qrcode");
    var elementos = document.querySelectorAll(".codigo");
    for (var i = 0; i < qrcodes.length; i++) {
        new QRCode(qrcodes[i], elementos[i].value);
    }
</script>

<link rel="stylesheet" href="{{ asset('assets/principal.css') }}">
<link rel="stylesheet" href="{{ asset('assets/boletins.css') }}">
<script src="{{ asset('assets/adminLogin.js') }}"></script>
@endsection
