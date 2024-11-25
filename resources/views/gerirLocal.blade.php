@extends('layout.layout')
@section("titulo", "Centro Eleitoral")
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
        {{-- Mostrar partidos disponivel --}}
        @if($existeEleicao)
        <form action="/local" id="form1" method="post" class="row g-3 needs-validation" novalidate style="margin-top: 2%; margin-right: 0.5% !important" enctype="multipart/form-data">
            <span class="tituloformulario">Registar Locais</span>
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
                      <input value="{{ old("nome")}}" name="nome" class="input"   type="text" placeholder="Nome do Local">
                  </div>

              </div>
              <div class="col-md-3">
                  <div>
                      <input value="{{ old("outroNome")}}" name="outroNome" class="input"   type="text" placeholder="Outra Designação">
                  </div>
              </div>
              {{-- Contagem de votos --}}
              <div class="row g-3 elementosFormularios" id="elemento2" style="margin-bottom: 0% !important;  margin-top: 2% !important">
                <div>
                  <button id="btbt" class="btn btn-primary my-2" type="submit">Registrar</button>
                </div>
              </div>
            </div>
          </form>
    @endif

@if(count($locais) > 0)
@php
// Ordena a coleção $locais
    $locaisOrdenados = $locais->sortBy(function($local) {
    return $local->nrDeMesas == $local->adminsMesas ? 1 : 0;
    });
@endphp

<table class="table table-bordered" style="margin-top: 1% !important">
    <tbody>
        <tr style="background: rgba(223, 219, 214, 0.4) !important;">
            <th style="background: rgba(223, 219, 214, 0.4) !important;">Nome</th>
            <th style="background: rgba(223, 219, 214, 0.4) !important;">Outra Designação</th>
            <th style="background: rgba(223, 219, 214, 0.4) !important;">Mesas/Presidentes</th>
            <th style="background: rgba(223, 219, 214, 0.4) !important;">Eleitores</th>
            <th  style="background: rgba(223, 219, 214, 0.4) !important;">Estado</th>
        </tr>

        @foreach ($locaisOrdenados as $local)
        <tr>
            <td>{{ $local->nome }}</td>
            <td>{{ $local->outroNome }}</td>
            <td>{{ $local->adminsMesas }}</td>
            <td>{{ $local->eleitores }}</td>
            <td>
                @if($existeEleicao)
                <button class="btbt">
                    <a href="/registar/{{ $local->idLocalDeVotacao }}">Registar/Ver</a>
                </button>
                @else
                Finalizado
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>


        @else
        <div style="text-align: center; background: rgba(223, 219, 214, 0.4) !important; width: 100%;">
            <span>Nenhum centro Registado</span>
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
  <link rel="stylesheet" href="{{ asset('assets/local.css') }}">
  <script src="{{ asset('assets/config.js') }}"></script>
  <script src="{{ asset('assets/adminLogin.js') }}"></script>
@endsection
