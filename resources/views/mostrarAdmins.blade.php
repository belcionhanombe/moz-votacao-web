@extends('layout.layout')
@section("titulo", "Mostrar Admins")

<!-- CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>

<!-- JavaScript -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

@if(session()->get('administradores.nivel') < 4)
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
                <style>
                    .btbt {
                        background-color: rgba(255, 165, 0, 0.5) !important;
                        border: none !important;
                        padding: 1.5% 3% !important;
                        border-radius: 6% !important;
                        min-width: 100px !important;
                        text-align: center;
                        color: black !important;
                        text-decoration: none;
                    }
                    .btbt:hover {
                        color: ghostwhite !important;
                        transform: scale(1.05);
                    }
                    .btbt a {
                        color: black;
                        text-decoration: none !important;
                    }
                    .btbt a:hover {
                        color: ghostwhite !important;
                    }
                    td, tr {
                        text-align: center !important;
                        margin: 0.1% !important;
                    }
                    form {
                        margin: 0 !important;
                        padding: 0 !important;
                    }
                </style>
                @if(count($administradores) > 0)
                <table class="table table-bordered">
                    <tbody>
                        <tr style="background: rgba(223, 219, 214, 0.4) !important;">
                            <th style="background: rgba(223, 219, 214, 0.4) !important;">{{ $area }}</th>
                            <th style="background: rgba(223, 219, 214, 0.4) !important;">Apelido</th>
                            <th style="background: rgba(223, 219, 214, 0.4) !important;">Nome</th>
                            <th colspan="2" style="background: rgba(223, 219, 214, 0.4) !important;">Estado</th>
                        </tr>
                        @foreach ($administradores as $admin)
                        <tr>
                            <td>{{ $admin->nomeArea }}</td>
                            <td>{{ $admin->segundoNome }}</td>
                            <td>{{ $admin->primeiroNome }}</td>

                            <td>
                                <button class="btbt">
                                    <a href="/editar/{{$admin->contacto}}">Actualizar</a>
                                </button>
                            </td>
                            <td>
                                <form action="/apagarAdmin/{{$admin->contacto}}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btbt" type="submit">Apagar</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div style="text-align: center; background: rgba(223, 219, 214, 0.4) !important; width: 100%;">
                    <span>Nenhum administrador Registado</span>
                </div>
                @endif
                {{-- Fim conteúdo --}}
            </div>
        </div>
    </div>
    {{-- <footer>
        <span>Por eleições justas e transparentes, @2024 Todos Direitos Reservados</span>
    </footer> --}}

    @endsection
@endif


@section("js")
    <link rel="stylesheet" href="{{ asset('assets/principal.css') }}">
    <script src="{{ asset('assets/config.js') }}"></script>
    <script src="{{ asset('assets/adminLogin.js') }}"></script>
@endsection
