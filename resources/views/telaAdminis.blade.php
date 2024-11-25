@extends('layout.layout')
@section('titulo', 'Admins')

<!-- JavaScript -->
{{-- Início Cabeçalho --}}
@include('layout.menu')
{{-- Fim Cabeçalho --}}

@section('conteudo')
    <div class="col p-0">
        {{-- Perfil --}}
        @include('layout.perfil')
        <div class="sidebar-content" style=" justify-content: center;">
            <br>
            <div style="text-align: center !important">
                <div>
                    <table style="max-width: 800px; margin: auto;">
                        <tr style="background: rgba(223, 219, 214, 0.5) !important; ">
                            <th>Nome</th>
                            <th style="text-align: center">Ano</th>
                            <th style="text-align: center">Estado</th>
                        </tr>
                        {{-- para o Ano --}}
                        <tr style="background: rgb(234, 213, 213)">
                            @if (session()->get('administradores.nivel') == 1)
                                <td>Mocambique</td>
                            @endif
                            @if (session()->get('administradores.nivel') >= 2)
                                <td>{{ $local->nome }}</td>
                            @endif

                            <td style="text-align: center;">{{ $anoEleicoes->ano }}</td>

                            <td style="text-align: center">
                                @if (session()->get('administradores.nivel') == 1)
                                    <button onclick="imprimirRelatorio(0, {{ $anoEleicoes->ano }})" class="btbt"
                                        style="border-radius: 0; background-color: rgba(255, 165, 0, 0.5) !important; margin-right: 0; margin-top: 0.5% !important">
                                        Gerar Relatório
                                    </button>
                                @endif

                                @if (session()->get('administradores.nivel') == 2)
                                    <button
                                        onclick="imprimirRelatorio('provincias_{{ session()->get('administradores.provincias_idProvincias') }}_{{ $anoEleicoes->ano }}', {{ $anoEleicoes->ano }})"
                                        class="btbt"
                                        style="border-radius: 0; background-color: rgba(255, 165, 0, 0.5) !important; margin-right: 0; margin-top: 0.5% !important">
                                        Gerar Relatório
                                    </button>
                                @endif

                                @if (session()->get('administradores.nivel') == 3)
                                    <button
                                        onclick="imprimirRelatorio('distritos_{{ session()->get('administradores.distritos_idDistritos') }}_{{ $anoEleicoes->ano }}', {{ $anoEleicoes->ano }})"
                                        class="btbt"
                                        style="border-radius: 0; background-color: rgba(255, 165, 0, 0.5) !important; margin-right: 0; margin-top: 0.5% !important">
                                        Gerar Relatório
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @foreach ($pontosDeRelatorio as $ponto)
                            <tr>
                                <td> {{ $ponto->nome }}</td>
                                <td style="text-align: center;">{{ $anoEleicoes->ano }}</td>
                                <td style="text-align: center">


                                    @if (session()->get('administradores.nivel') == 1)
                                        <button
                                            onclick="imprimirRelatorio('provincias_{{ $ponto->idProvincias }}_{{ $anoEleicoes->ano }}', {{ $anoEleicoes->ano }} )"
                                            class="btbt"
                                            style="border-radius: 0; background-color: rgba(255, 165, 0, 0.5) !important; margin-right: 0; margin-top: 0.5% !important">
                                            Gerar Relatório
                                        </button>
                                    @endif
                                    @if (session()->get('administradores.nivel') == 2)
                                        <button
                                            onclick="imprimirRelatorio('distritos_{{ $ponto->idDistritos }}_{{ $anoEleicoes->ano }}', {{ $anoEleicoes->ano }} )"
                                            class="btbt"
                                            style="border-radius: 0; background-color: rgba(255, 165, 0, 0.5) !important; margin-right: 0; margin-top: 0.5% !important">
                                            Gerar Relatório
                                        </button>
                                    @endif

                                    @if (session()->get('administradores.nivel') == 3)
                                        <button
                                            onclick="imprimirRelatorio('centros_{{ $ponto->idLocalDeVotacao }}_{{ $anoEleicoes->ano }}', {{ $anoEleicoes->ano }} )"
                                            class="btbt"
                                            style="border-radius: 0; background-color: rgba(255, 165, 0, 0.5) !important; margin-right: 0; margin-top: 0.5% !important">
                                            Gerar Relatório
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                    </table>
                </div>

            </div>
        </div>
    </div>



@section('js')
    <link rel="stylesheet" href="{{ asset('assets/principal.css') }}">
    <script src="{{ asset('assets/config.js') }}"></script>
    <script src="{{ asset('assets/adminLogin.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function imprimirRelatorio(id, ano) {
            // Fetch para obter o conteúdo da página do relatório
            fetch('/relatorio/' + id + '/' + ano)

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
                        }, 500); // Aguarda 500ms para garantir que a impressão seja concluída
                    };
                })
                .catch(error => {
                    console.error('Erro ao carregar página para impressão:', error);
                });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
@endsection

<script></script>
