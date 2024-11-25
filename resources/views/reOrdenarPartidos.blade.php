@extends('layout.layout')
@section("titulo", "reOrdenarPartidos")
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

@if(session()->get('administradores.nivel') == 1)
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

        <form action="/partidos" id="form1" method="post" class="row g-3 needs-validation" novalidate style="margin-top: 2%;  margin-bottom: 2% !important; margin-right: 0.5% !important" enctype="multipart/form-data">
          <span class="tituloformulario"> Adicionar Novo Partido</span>
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          @if($errors->any())
            <input type="hidden" name="" value="{{ $errors->all()[0] }}" id="erro1">
          @endif
          @if(session("erro"))
            <input type="hidden" name="" value='{{ session("erro") }}' id="erro2">
          @endif
          <div class="row g-3 elementosFormularios" style="margin-bottom: 0% !important; margin-top: 0% !important">

            <div class="col-md-3">
                <div>
                    <input value="{{ old("nome")}}" name="nome" class="input"   type="text" placeholder="Nome do partido">
                </div>

            </div>

            <div class="col-md-3">
                    <div>
                        <input class="input" value="{{ old("acronimo")}}"  name="acronimo" type="text" placeholder="Acronimo do Partido">
                    </div>
            </div>

            <div class="col-md-3 file-input-wrapper">
                <button class="file-input-button" id="emblema">Logo do Partido &nbsp;&nbsp;<i style="margin-bottom: 1% !important" id="uploadIcon" class="bi bi-upload"></i></button>
                <input class="file-input file-input-button"  value="{{ old("logo")}}" type="file" id="fileInput" name="logo" onchange="updateFileName()">
            </div>
                <div >
                    <button id="btbt" class="btn btn-primary my-2 margin-top: 0% !important" type="submit">Registrar</button>
                </div>
          </div>
        </form>

        {{-- Mostrar partidos disponivel --}}

        <form action="/reOrdenar" id="form1" method="post" class="row g-3 needs-validation" novalidate style="margin-top: 2%; margin-bottom: 2% !important; margin-right: 0.5% !important" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            @if($errors->any())
              <input type="hidden" name="" value="{{ $errors->all()[0] }}" id="erro1">
            @endif
            @if(session("erro"))
              <input type="hidden" name="" value='{{ session("erro") }}' id="erro2">
            @endif

            <table style="margin-top: 0.5% !important">
                <thead>
                    <tr style="text-align: center; background: rgba(223, 219, 214, 0.4) !important;">
                        <th>Ordem</th>
                        <th>Nome</th>
                        <th>Acrônimo</th>
                        <th>Foto</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $contador = 1; ?>
                    @foreach ($partidos as $partido)
                    <tr>
                        <td style="text-align: center">
                            <input name="ordem[]" type="number" value="{{ $partido->ordem }}" min="1" required>
                        </td>
                        <td style="text-align: center;">{{ $partido->nome }}</td>
                        <td style="text-align: center;">{{ $partido->acronimo }}</td>
                        <td style="text-align: center;">
                            <img style="width: 50px" src="/storage/{{ $partido->logo }}">
                        </td>
                    </tr>
                    <?php $contador++; ?>
                    @endforeach
                </tbody>
            </table>
            <div style="text-align: left !important; margin-top: 1%;">
                <button class="btbt" type="submit">Registrar</button>
            </div>
        </form>






      {{-- Fim conteúdo --}}



    </div>
  </div>


{{-- Fim footer --}}

@endif

@section("js")
<link rel="stylesheet" href="{{ asset('assets/partidos.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/principal.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/config.css') }}">
  <script src="{{ asset('assets/config.js') }}"></script>
  <script src="{{ asset('assets/adminLogin.js') }}"></script>
@endsection
