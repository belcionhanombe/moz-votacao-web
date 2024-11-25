@extends('layout.layout')
@section("titulo", "Config Eleicao")
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


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
      <h1>Reconhecimento de Partido Votado</h1>
      <input type="file" id="upload" accept="image/*">
      <button onclick="classifyImage()">Reconhecer Partido</button>
      <p id="result"></p>
    </div>
  </div>
</div>


@endsection

@section("js")
<script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@latest/dist/tf.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@teachablemachine/image@latest/dist/teachablemachine-image.min.js"></script>
<script type="text/javascript">
<script src="{{ asset('assets/classify.js') }}"></script>
@endsection
