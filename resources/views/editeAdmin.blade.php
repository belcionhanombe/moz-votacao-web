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

      @if(session()->get('administradores.nivel') < 4)
        <div class="sidebar-content">
          <div class="content container-fluid">
            <!-- Conteúdo do formulário -->

            @if(session()->has('administradores'))
    <form action="/editarAdmin/{{$admin->contacto}}" id="form1" method="post" class="row g-3 needs-validation" novalidate style="margin-left: 2%; margin-right: 2%; margin-top: 2%;" enctype="multipart/form-data">
    <span class="tituloformulario" style=" text-align: center; background-color:  rgba(223, 219, 214, 0.4) !important;" >
         Actualizar dados</span>
    <input type="hidden" name="_token" value="{{csrf_token()}}">
      @if($errors->any())
          <input type="hidden" name="" value="{{ $errors->all()[0]}}" id="erro1">
      @endif
      @if(session("erro"))
          <input type="hidden" name="" value='{{ session("erro")}}' id="erro2">
      @endif

      <div class="col-md-3">
          <label for="validationCustom01" class="form-label"></label>
            <input type="text" name="primeiroNome" value="{{$admin->primeiroNome}}"  class="form-control" id="validationCustom01" placeholder="Primeiro Nome" required>
          <div class="valid-feedback">
            Looks good!
          </div>
        </div>

        <div class="col-md-3">
          <label for="validationCustom01" class="form-label"></label>
          <input type="text" name="segundoNome" value="{{ $admin->segundoNome}}"  class="form-control" id="validationCustom001" placeholder="Segundo Nome" required>
          <div class="valid-feedback">
            Looks good!
          </div>
        </div>

        <div class="col-md-3">
            <label for="validationCustom03" class="form-label"></label>
            <input type="text" name="bi" value="{{$admin->bi}}"  class="form-control" id="validationCustom03" placeholder="Numero de BI" required>
            <div class="valid-feedback">
              Looks good!
            </div>
        </div>

        <div class="col-md-3">
            <label for="validationCustom04" class="form-label"></label>
           {{$admin->nome}}
            <input type="text" name="contacto" value="{{$admin->contacto}}"  class="form-control" id="validationCustom04" placeholder="Contacto" required>
            <div class="valid-feedback">
              Looks good!
            </div>
        </div>

        <style>
            form button:hover{
                background-color: #fcbc4c;
                color: aliceblue;
            }
        </style>

          <div class="col-12">
            <form action="/editarAdmin/{{$admin->contacto}}" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                {{method_field("PUT")}}
                <button type="submit"   style="text-align: center; margin-bottom:1%; margin-top:1% !important;  background: rgb(255, 165, 0,.5) !important; border: solid 1px #fcbc4c;  !important; margin-right: -87% !important; width: 120px; padding: 0.4%; border-radius: 5% !important" id="bloquearId">
                    Actualizar
                </button>
            </form>
          </div>

      </form>
@endif


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
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      <script src="{{ asset('assets/jqueryDistritos.js') }}"></script>
      <script src="{{ asset('assets/adminLogin.js') }}"></script>

    @endsection
@endsection
