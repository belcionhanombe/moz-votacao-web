
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
      @if(session()->has('administradores'))

        <form action="/registar" id="form1" method="post" class="row g-3 needs-validation" novalidate style="margin-left: 2%; margin-right: 2%; margin-top: 2%;" enctype="multipart/form-data">
          <input type="hidden" name="_token" value="{{csrf_token()}}">
          @if($errors->any())
              <input type="hidden" name="" value="{{ $errors->all()[0]}}" id="erro1">
          @endif
          @if(session("erro"))
              <input type="hidden" name="" value='{{ session("erro")}}' id="erro2">
          @endif
          @if(session()->get('administradores.nivel') == 1)
          <span class="tituloformulario" style="text-align: center;    background-color: rgba(223, 219, 214, 0.5) !important;" > Registar administradores Provincial  </span>
        @elseif(session()->get('administradores.nivel') == 2)
          <span class="tituloformulario" style="text-align: center   background-color: rgba(223, 219, 214, 0.5) !important;" > Registar administradores Distrital - {{"$nomeProvincia->nome"}}   </span>
        @elseif(session()->get('administradores.nivel') == 3)
          <span class="tituloformulario" style="text-align: center; background-color:  rgb(255, 165, 0,.5) !important;" >Centro Eleitoral - {{"$local->nome"}}  </span>

        @endif

        @if(session()->get('administradores.nivel') == 3)
        <input type="number" style="display: none !important" name="localDeVotacao_idLocalDeVotacao" value="{{ $id }}">
         <div  class="row" style=" display: flex; justify-content: center; margin_top: 2% !important">
            <div class="col-md-4">
                <label for="validationCustom06" class="form-label"></label>
                <input type="text" name="codigoDaMesa" value="{{ old("codigoDaMesa")}}"  class="form-control" id="validationCustom06" placeholder="Código/Nome da Mesa" required>
                <div class="valid-feedback">
                Looks good!
                </div>
            </div>
                <div class="col-md-4" style="margin_top: 1% !important">
                <label for="validationCustom06" class="form-label"></label>
                <input type="number" name="nrDeEleitores" value="{{ old("nrDeEleitores")}}"  class="form-control" id="validationCustom06" placeholder="Número de Elitores" required>
                <div class="valid-feedback">
                Looks good!
                </div>
            </div>
        </div>
            <span style="text-align: center; background-color: rgba(223, 219, 214, 0.2) !important; margin-top: 2% !important">Dados do Presidente da Mesa</span>
         @endif
          <div class="col-md-4">
              <label for="validationCustom01" class="form-label"></label>
              <input type="text" name="primeiroNome" value="{{ old("primeiroNome")}}"  class="form-control" id="validationCustom01" placeholder="Primeiro Nome" required>
              <div class="valid-feedback">
                Looks good!
              </div>
            </div>

            <div class="col-md-4">
              <label for="validationCustom01" class="form-label"></label>
              <input type="text" name="segundoNome" value="{{ old("segundoNome")}}"  class="form-control" id="validationCustom001" placeholder="Segundo Nome" required>
              <div class="valid-feedback">
                Looks good!
              </div>
            </div>
            <div class="col-md-4">
                <label for="validationCustom03" class="form-label"></label>
                <input type="text" name="bi" value="{{ old("bi")}}"  class="form-control" id="validationCustom03" placeholder="Numero de BI" required>
                <div class="valid-feedback">
                  Looks good!
                </div>
            </div>

            <div class="col-md-4">
                <label for="validationCustom04" class="form-label"></label>
                <input type="text" name="contacto" value="{{ old("contacto")}}"  class="form-control" id="validationCustom04" placeholder="Contacto" required>
                <div class="valid-feedback">
                  Looks good!
                </div>
            </div>

            @if(session()->get('administradores.nivel') == 1 && count($provincias)>0 )
            <div style="margin-top: 0.6% !important" class="col-md-4">
              <select class="form-select" aria-label=" select example" name="provincia" required>
                <option disabled selected>Seleciona a Provincia</option>
                @foreach($provincias as $provincia)
                <option value="{{ $provincia->idProvincias}}">{{ $provincia->nome }}</option>
                @endforeach
              </select>
            </div>
            @endif

            @if(session()->get('administradores.nivel') == 2 && count($distritos)>0)
            <div class="col-md-4">
              <select class="form-select" aria-label=" select example" name="distrito" required>
                <option disabled selected>Seleciona o Distrito</option>
                @foreach($distritos as $distrito)
                <option value="{{ $distrito->idDistritos}}">{{ $distrito->nome }}</option>
                @endforeach
              </select>
            </div>
            @endif
            <div class="col-md-4">
                <label for="validationCustom06" class="form-label"></label>
                <input type="email" name="email" value="{{ old("email")}}"  class="form-control" id="validationCustom06" placeholder="email" required>
                <div class="valid-feedback">
                  Looks good!
                </div>
            </div>
          <div class="col-md-4">
              <label for="validationCustom06" class="form-label"></label>
              <input type="password" name="senha" value="{{ old("senha")}}"  class="form-control" id="validationCustom06" placeholder="Digita uma Senha" required>
              <div class="valid-feedback">
                Looks good!
              </div>
          </div>

            <div class="col-md-4">
                <label for="validationCustom06" class="form-label"></label>
                <input type="password" name="senha1" value="{{ old("senha1")}}"  class="form-control" id="validationCustom07" placeholder="Confirma a Senha" required>
                <div class="valid-feedback">
                  Looks good!
                </div>
            </div>

            <div >
                <button id="btbt" class="btn btbt my-2" style=" margin-top: 0.4% !important; margin-right: -90% !important" type="submit">Registrar</button>
            </div>
          </form>

          {{-- Presidentes de uma mesa --}}

          @if(session()->get('administradores.nivel') == 3)
          @if(count($dados) > 0)
          <table class="table table-bordered" style="margin-top: 1% !important">
            <tbody>
                <tr style="background: rgba(223, 219, 214, 0.4) !important; text-align: center;">
                    <th style="background: rgba(223, 219, 214, 0.4) !important;">local</th>
                    <th style="background: rgba(223, 219, 214, 0.4) !important;">Mesa</th>
                    <th style="background: rgba(223, 219, 214, 0.4) !important;">Eleitores</th>
                    <th style="background: rgba(223, 219, 214, 0.4) !important;">Presidente</th>
                    <th style="background: rgba(223, 219, 214, 0.4) !important;">Boletins</th>
                </tr>

                @foreach ($dados as $dado)
                <tr>
                    <td>{{ $dado->nomeLocal }}</td>
                    <td>{{ $dado->nomeMesa }}</td>
                    <td>{{ $dado->eleitores }}</td>
                    <td>{{ $dado->nomeAdmin }} {{ $dado->nomeApelido }}</td>
                    <td style="text-align: center;">
                        <button id="{{$dado->idMesas}}" onclick="imprimir({{$dado->idMesas}})" class="btbt" style="border-radius:none; background: rgba(255, 165, 0, 0.5) !important;">Imprimir</button>

                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
          @endif
          {{-- Fim de presidentes de mesa --}}
    @endif

    </div>
  </div>
</div>

{{-- ghostwhite --}}




@section("js")

<link rel="stylesheet" href="{{ asset('assets/principal.css') }}">
<link rel="stylesheet" href="{{ asset('assets/registo1.css') }}">
<script src="{{ asset('assets/adminLogin.js') }}"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('assets/jquery.js')}}"></script>
<script>

function imprimir(id) {
    // Fetch para obter o conteúdo da página
    fetch('/imprimirBoletins/' + id)
        .then(response => response.text())
        .then(html => {
            // Cria um iframe invisível e coloca o conteúdo nele
            const iframe = document.createElement('iframe');
            iframe.style.display = 'none';
            document.body.appendChild(iframe);
            const doc = iframe.contentWindow.document;
            doc.open();
            doc.write(html);
            doc.close();

            // Espera até que o iframe esteja pronto
            iframe.onload = function() {
                // Foca no iframe e chama a função de impressão
                iframe.contentWindow.focus();
                iframe.contentWindow.print();

                // Remove o iframe após a impressão
                setTimeout(function() {
                    document.body.removeChild(iframe);
                }, 500); // Aguarda 1 segundo para garantir que a impressão seja concluída
            };
        })
        .catch(error => {
            console.error('Erro ao carregar página para impressão:', error);
        });
}
</script>
@endsection


