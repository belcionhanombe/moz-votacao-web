@extends('layout.layout')
@section("titulo", "Configurar Partidos")
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
          <div class="row g-3 elementosFormularios" style="margin-bottom: 0% !important; margin-top: 1% !important">

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
                    <button id="btbt" class="btn btn-primary my-2 margin-top: 6% !important" type="submit">Registrar</button>
                </div>
          </div>
        </form>

        {{-- Mostrar partidos disponivel --}}

        <button  class="btbt">
            <a href="/reordenar" >Reodenar</a>
        </button>

        <table style="margin-top: 0.5% !important">
            <tr style="text-align: center; background: rgba(223, 219, 214, 0.4) !important; ">
                <th>ordem</th>
                <th>Nome</th>
                <th>Acronimo</th>
                <th>Foto</th>
            </tr>

            <?php $contador = 1; ?> <!-- Inicialize o contador fora do loop -->

            @foreach ($partidos as $partido)
            <tr>
                <td>{{ $contador }}</td>
                <td id="nome" style="text-align: center;">{{$partido->nome}}</td>
                <td id="acronimo" style="text-align: center;">{{$partido->acronimo}}</td>
                <td  id="foto" style="text-align: center;">
                    <img style="width: 50px" src="/storage/{{$partido->logo}}">
                </td>
            </tr>
            <?php $contador++; ?> <!-- Incrementa o contador a cada iteração -->
            @endforeach
        </table>


      {{-- Fim conteúdo --}}



    </div>
  </div>


{{-- Fim footer --}}

@endif

@section("js")
<link rel="stylesheet" href="{{ asset('assets/partidos.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/principal.css') }}">

  <script src="{{ asset('assets/config.js') }}"></script>
  <script src="{{ asset('assets/adminLogin.js') }}"></script>
@endsection
