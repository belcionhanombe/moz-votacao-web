@extends('layout.layout')
@section("titulo", "Mostrar Admins")
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


@if(session()->get('administradores.nivel') == 3)
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
@if(count($presidentes) > 0)


<table class="table table-bordered" style="margin-top: 1% !important">
    <tbody>
        <tr style="background: rgba(223, 219, 214, 0.4) !important;">
            <th style="background: rgba(223, 219, 214, 0.4) !important;">Local</th>
            <th style="background: rgba(223, 219, 214, 0.4) !important;">Mesa</th>
            <th style="background: rgba(223, 219, 214, 0.4) !important;">Presidente</th>
            <td style="background: rgba(223, 219, 214, 0.4) !important;">Estado</td>
        </tr>
        @foreach ($presidentes as $presidente)
        <tr>
                <td>{{ $presidente->nomeArea }}</td>
                <td>{{ $presidente->idMesa }}</td>
                <td>{{ $presidente->primeiroNome }} {{ $presidente->segundoNome }} </td>



                    <td>
                        <button class="btbt">
                            <a href="/editar/{{$presidente->contacto}}">Actualizar</a>
                        </button>
                    </td>
        </tr>
        @endforeach
    </tbody>
</table>


        @else
        <div style="text-align: center; background: rgba(223, 219, 214, 0.4) !important; width: 100%;">
            <span>Nenhum presidente Registado</span>
        </div>
        @endif
      {{-- Fim conteúdo --}}



    </div>
  </div>


{{-- Fim footer --}}

@endif

@section("js")
<link rel="stylesheet" href="{{ asset('assets/partidos.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/principal.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/config.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/presidentes.css') }}">
  <script src="{{ asset('assets/config.js') }}"></script>
  <script src="{{ asset('assets/adminLogin.js') }}"></script>
@endsection
