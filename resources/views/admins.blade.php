
@extends('layout.layout')
@section("titulo","Admins")
@section('conteudo')
<a class="btn btn-primary" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample">
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <label for="menu-toggle" class="menu-icon">&#9776;</label>
  </button>
</a>

<div class="offcanvas offcanvas-start"  id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="offcanvasExampleLabel"><b>Painel de Administrador</b></h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>

  <div class="offcanvas-body">
    <div class="dropdown mt-3">
      <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown">
          <b>Gerir Administradores</b>
      </button>
      @if($admins->nivel == 3)
      <div class="offcanvas-body">
        <div class="dropdown mt-3">
          <button class="btn  dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown">
           <b>Adicionar</b>
          </button>
          <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <li><a class="dropdown-item" href="/registar/2">Presidente de Mesa</a></li>
            <li><a class="dropdown-item" href="/registar/3">Registante</a></li>
          </ul>
        </div>
      </div>
    @endif
      <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        @if($admins->nivel != 3)
        <li><a class="dropdown-item" href="/registar/1">Adicionar</a></li>
        @endif
        <li><a class="dropdown-item" href="#">Mostrar</a></li>
        <li><a class="dropdown-item" href="#">Remover</a></li>
        <li><a class="dropdown-item" href="#">Actualizar</a></li>
      </ul>
    </div>
  </div>


  <div class="offcanvas-body">
    <div class="dropdown mt-3">
      <button class="btn  dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown">

       <b>Relatorios</b>

      </button>
      <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        <li><a class="dropdown-item" href="#">Provinciais</a></li>
        <li><a class="dropdown-item" href="#">Distrital</a></li>
        <li><a class="dropdown-item" href="#">Local</a></li>
        <li><a class="dropdown-item" href="#">Mesa</a></li>
      </ul>
    </div>

  </div>

  <div class="offcanvas-body">
    <div class="dropdown mt-3">
      <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown">
        <b>Configura&ccedil;&otilde;es</b>
      </button>
      <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        <li><a class="dropdown-item" href="#">Tipo de Elei&ccedil;&otilde;es </a></li>
        <li><a class="dropdown-item" href="#">Adicionar Novo Municipio</a></li>
        <li><a class="dropdown-item" href="#">Adicionar novo partido</a></li>
        <li><a class="dropdown-item" href="#">Remover Partidor</a></li>
        <li><a class="dropdown-item" href="#">Maximo de Eleitores por Mesa</a></li>
      </ul>
    </div>
  </div>

  <div>

  </div>

</div>

<div>

</div>

@section("js")
<link rel="stylesheet" href="{{('assets/admins.css')}}">
@endsection
