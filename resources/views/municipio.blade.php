@extends('layout.layout')
@section("titulo","Configurar Municipios")
@section('conteudo')

  {{-- Início Cabeçalho --}}
  @include('layout.menu')
  {{-- Fim Cabeçalho --}}

  @section('conteudo')
    <div class="col p-0">
      {{-- Perfil --}}
      @include('layout.perfil')
      {{-- Fim Perfil --}}

      @if(session()->get('administradores.nivel') == 1)
        <div class="sidebar-content">
          <div class="content container-fluid">
            <!-- Conteúdo do formulário -->
            <form action="/novoM" id="form1" method="post" class="row g-3 needs-validation" novalidate style="margin-top: 2%;" enctype="multipart/form-data">
              <span class="tituloformulario" style="margin-top: 0; padding-to: 0%;">Adicionar Novo Município</span>
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              @if($errors->any())
                <input type="hidden" name="" value="{{ $errors->all()[0] }}" id="erro1">
              @endif
              @if(session("erro"))
                <input type="hidden" name="" value='{{ session("erro") }}' id="erro2">
              @endif
              <div id="elementosFormularios" class="row g-3" style="margin-bottom: 1% !important; margin-top: 1% !important">
                <div class="col-md-2" style="padding-top: 0% !important; margin-top: 1% !important">
                  <select id="provincia-select" class="form-select" aria-label="Select Provincia" name="provincia" required>
                    <option disabled selected>Provincia</option>
                    @foreach ($provincias as $provincia)
                      <option value="{{$provincia->idProvincias}}">{{$provincia->nome }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-2" style="padding-top: 0% !important; margin-top: 1% !important">
                  <select id="distrito-select" class="form-select" aria-label="Select Distrito" name="distrito" required>
                    <option disabled selected>Distrito</option>
                    <!-- Os distritos serão preenchidos dinamicamente -->
                  </select>
                </div>
                <div class="col-md-2" style="padding-top: 0% !important; margin-top: 1% !important">
                  <input type="hidden" name="_token" value="{{ csrf_token() }}">
                  {{ method_field("PUT") }}
                  <button type="submit" class="btn btn-primary" id="btbt">Registar</button>
                </div>
              </div>
            </form>

            @foreach ($provincias as $provincia)
              <table style="margin-top: 3% !important">
                <tr style="text-align: center; background: rgba(223, 219, 214, 0.4) !important; ">
                  <th id="provincia" style="width: 260px" rowspan="{{  count($distritosM) }}">Provincia de {{ $provincia->nome }}</th>
                  <th id="provinciaMobile">Provincia de {{ $provincia->nome }}</th>
                </tr>
                <tr style="text-align: center; background: rgba(223, 219, 214, 0.4) !important;">
                  <th>Municípios</th>
                </tr>

                <?php $contador = 1; ?>
                @foreach ($distritosM as $distritoM)
                  @if ($distritoM->provincias_idProvincias == $provincia->idProvincias)
                    <tr>
                      <td style="text-align: center">{{ $distritoM->nome }}</td>
                    </tr>
                    <?php $contador++; ?>
                  @endif
                @endforeach
              </table>
            @endforeach
            <!-- Fim do conteúdo -->

          </div>
          <!-- Footer -->
          <div class="container">
            <footer class="py-5">

            </footer>
          </div>
          <!-- Fim do footer -->
        </div>
      @endif
    </div>

    @section("js")
      <link rel="stylesheet" href="{{ asset('assets/principal.css') }}">
      <link rel="stylesheet" href="{{ asset('assets/municipio.css') }}">
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      <script src="{{ asset('assets/jqueryDistritos.js') }}"></script>
      <script src="{{ asset('assets/adminLogin.js') }}"></script>
    @endsection
@endsection
