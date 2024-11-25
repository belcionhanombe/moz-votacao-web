@extends('layout.layout')
@section("titulo", "Config Eleicao")
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

{{-- Início Cabeçalho --}}
@include('layout.menu')
{{-- Fim Cabeçalho --}}

@section('conteudo')
<div class="col p-0">
  {{-- Perfil --}}
  @include('layout.perfil')
  {{-- Fim Perfil --}}

  <div class="sidebar-content">
    <div class="content container-fluid">
      {{-- Conteúdo --}}

      @if(session()->get('administradores.nivel') == 1)
        <form  action="/configTipoEleicao" id="form1" method="post" class="row g-3 needs-validation" novalidate style="margin-top: 2%; margin-right: 0.5% !important" enctype="multipart/form-data">
          <span class="tituloformulario">Configurar Contagem de Votos</span>
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          @if($errors->any())
            <input type="hidden" name="" value="{{ $errors->all()[0] }}" id="erro1">
          @endif
          @if(session("erro"))
            <input type="hidden" name="" value='{{ session("erro") }}' id="erro2">
          @endif
          <div class="row g-3 elementosFormularios" style="margin-bottom: 0% !important; margin-top: 0% !important">
            
            <div class="col-md-2">
              <select class="form-select" aria-label="select example" name="tipoEleicoes" required>
                <option disabled selected>Tipo de Eleições</option>
                <option value="0" {{ old('tipoEleicoes') == 0 ? 'selected' : '' }}>Gerais</option>
                <option value="1" {{ old('tipoEleicoes') == 1 ? 'selected' : '' }}>Municipais</option>
              </select>
            </div>

            <div class="col-md-2">
              <select class="form-select" id="distritosConfig" name="distrito">
                <option disabled selected>Alcance</option>
                @foreach($distritos as $distrito)
                  <option value="{{ $distrito->idDistritos }}" data-municipio="{{ $distrito->municipio }}">{{ $distrito->nome }}</option>
                @endforeach
              </select>
            </div>

            {{-- Contagem de votos --}}
            <div class="row g-3 elementosFormularios" id="elemento2" style="margin-bottom: 0% !important;  margin-top: 2% !important">

              <div class="row g-3  elementosFormularios" id="elemento3" style="margin-bottom: 0% !important;  margin-top: 0% !important">
                <div class="col-md-2 ">
                  <label class="input2">início</label>
                  <input class="input2 form-control" value="{{ old('inicio')}}" type="datetime-local" min="{{ $dataActual }} 00:00" max="{{$ultimoDiaAnoAtual}} 00:00" name="inicio">
                </div>

                <div class="col-md-1"></div>
                <div class="col-md-2 input2">
                  <label class="input2">Fim</label>
                  <input class="input2 form-control" value="{{old('fim')}}" type="datetime-local" min="{{ $dataActual }} 00:00" max="{{$ultimoDiaAnoAtual}} 00:00" name="fim">
                </div>
              </div>
              <div>
                <button id="btbt" class="btn btn-primary my-2" type="submit">Registrar</button>
              </div>
            </div>
          </div>
        </form>
        <div>
          @if(count($config) > 0)
            <br>
            <table>
              <tr style="text-align: center; background: rgba(223, 219, 214, 0.3) !important;">
                <th>Ano</th>
                <th>Tipo de Eleição</th>
                <th>Inicio</th>
                <th>Fim</th>
                <th>Estado</th>
              </tr>
              <?php $contador = 0; ?>
              @foreach ($config as $conf)
                  <tr>
                      <td>{{ $conf->ano }}</td>
                      <td>
                          @if($conf->tipoEleicao == 0)
                              Geral
                              @if($conf->nomeDistrito)
                                  - {{ $conf->nomeDistrito }}
                              @endif
                          @elseif($conf->tipoEleicao == 1)
                              Municipais -
                              @if($conf->nomeDistrito)
                                  {{ $conf->nomeDistrito }}
                              @endif
                          @endif
                      </td>
                      <td class="dataInicio">{{ $conf->dataInicio }} : {{ $conf->horaInicio }}</td>

                      <td class="dataFim">{{ $conf->dataFim }} : {{ $conf->horaFim }}</td>
                      <td style="text-align: center !important">
                        @if($estado[$contador]== "Decorrendo")
                        {{ $estado[$contador++] }}
                        <button class="btn" style="background-color: rgb(255, 165, 0, .5) !important; padding-top: 0% !important; padding-bottom: 0% !important"
                        onclick="mostrarForm({{ $conf->idConfig }})">Prologar</button>
                        @else
                        {{ $estado[$contador++] }}
                        @endif
                      </td>
                  </tr>
              @endforeach
          </table>
          @endif
        </div>
      @endif
    </div>
  </div>
</div>

{{-- Para atualizar --}}
<div id="elementosFormularios" style="margin-bottom: 1% !important; margin-top: 1% !important; display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: white; padding: 20px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.5); z-index: 1000;">
    <button onclick="document.getElementById('elementosFormularios').style.display='none'" style="position: absolute; top: 10px; right: 10px; background: red; color: white; border: none; font-size: 30px; cursor: pointer; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">&times;</button>
    <form action="/actualizarContagem/" id="formAtualizar" method="post" novalidate style="margin-top: 2%;" enctype="multipart/form-data">

        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        @if($errors->any())
          <input type="hidden" name="" value="{{ $errors->all()[0] }}" id="erro1">
        @endif
        @if(session("erro"))
          <input type="hidden" name="" value='{{ session("erro") }}' id="erro2">
        @endif
        <div style="padding-top: 10% !important; margin-top: 1% !important">
            <label style="margin: 1% !important" class="input2">Nova data</label>

            <input class="input2 form-control" value="{{old('data')}}" type="datetime-local" min="{{ $dataActual }} 00:00" max="{{ $dataDoisDiasDepois }} 00:00" name="data">
        </div>
        <div class="col-md-2" style="padding-top: 0% !important; margin-top: 1% !important">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            {{ method_field("PUT") }}
            <button type="submit" class="btn" style="background-color: rgb(255, 165, 0, .5) !important; margin: 10% !important">Actualizar</button>
        </div>
    </form>
  </div>


{{-- Fim atualizar --}}

@endsection

@section("js")
  <link rel="stylesheet" href="{{ asset('assets/principal.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/config.css') }}">
  <script src="{{ asset('assets/config.js') }}"></script>
  <script src="{{ asset('assets/adminLogin.js') }}"></script>
  <script>
    function mostrarForm(id) {
        document.getElementById('elementosFormularios').style.display = 'block';
        document.getElementById('formAtualizar').action = "/actualizarContagem/" + id;
    }
  </script>
@endsection
