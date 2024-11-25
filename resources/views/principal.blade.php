@extends('layout.layout')

@section('titulo', 'Resultados Eleitoral')

@section('conteudo')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
    {{-- Início Cabeçalho --}}
    @include('layout.menu1')
    {{-- Fim Cabeçalho --}}
    <link rel="stylesheet" href="{{ asset('assets/resultado.css') }}">
    <div class="col p-0">
        {{-- Perfil --}}
        @include('layout.perfil1')
        {{-- Fim Perfil --}}
        <div class="sidebar-content">
            @if (count($resultados) > 0)
                @if (count($distritos) > 0)
                    <div class="menu-wrapper" style=" background:rgba(247, 207, 134, 0.5)">
                        <div class="menu">
                            <span>DISTRITOS</span>
                            @if (count($locais) == 0)
                                <div class="menu-content menuDistritos">
                                    <input type="text" id="distritoSearch" class="search-input"
                                        placeholder="Procurar distrito...">
                                    <div id="distritoList">
                                        @foreach ($distritos as $distrito)
                                            <a href="/principal/distritos_{{ $distrito->idDistritos }}_{{ $ano }}"
                                                class="menu-item ">
                                                {{ $distrito->nome }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            @if (count($locais) > 0)
                                <div class="menu-content">
                                    <input type="text" id="distritoSearch" class="search-input"
                                        placeholder="Procurar distrito...">
                                    <div id="distritoList">
                                        @foreach ($distritos as $distrito)
                                            <a href="/principal/distritos_{{ $distrito->idDistritos }}_{{ $ano }}"
                                                class="menu-item ">
                                                {{ $distrito->nome }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif


                        </div>
                        @if (count($locais) > 0)

                            <div class="menu">
                                <span>CENTROS</span>
                                @if (count($mesas) == 0)
                                    <div class="menu-content menuCentros">
                                        @foreach ($locais as $local)
                                            <a href="/principal/centros_{{ $local->idLocalDeVotacao }}_{{ $ano }}"
                                                class="menu-item">{{ $local->nome }}</a>
                                        @endforeach
                                    </div>
                                @endif

                                @if (count($mesas) > 0)
                                    <div class="menu-content ">
                                        @foreach ($locais as $local)
                                            <a href="/principal/centros_{{ $local->idLocalDeVotacao }}_{{ $ano }}"
                                                class="menu-item">{{ $local->nome }}</a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                        @endif

                        @if (count($mesas) > 0)
                            <div class="menu">
                                <span>MESAS</span>
                                <div class="menu-content" style="margin-right: 10px !important">
                                    @foreach ($mesas as $mesa)
                                        <a href="/principal/mesas_{{ $mesa->idMesas }}_{{ $ano }}"
                                            class="menu-item">{{ $mesa->nome }}</a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
            @endif
            @if (count($resultados) > 0)

                {{-- Relatorio pais --}}

                <button onclick="imprimirRelatorio( '{{ $relatorioID }}', {{ $anoDeEleicao }})" class="btbt"
                    style="border-radius: 0; background-color: rgba(255, 165, 0, 0.5) !important; margin-right: 0; margin-top: 0.5% !important">
                    Gerar Relatório
                </button>

                <div class="content container-fluid">
                    <div class="container mt-5">
                        <div class="row g-3" style="margin-left: 1px; margin-right: 1px; margin-top: 0%;">
                            <div class="col-md-4" style="margin-bottom: 1% !important">
                                <div class="dashboard tabela1">
                                    <table style="width: 100% !important">
                                        <tr>
                                            <th>Partido</th>
                                            <th>% de Votos</th>
                                            <th>Votos</th>
                                        </tr>
                                        {{-- Mostra apenas os primeiros 4 resultados --}}
                                        <?php $resto = 0; ?>
                                        @foreach ($resultados->take(4) as $index => $resultado)
                                            @php
                                                $cor = '';
                                                switch ($resultado->nome_partido) {
                                                    case 'FRELIMO':
                                                        $cor = 'red';
                                                        break;
                                                    case 'MDM':
                                                        $cor = 'blue';
                                                        break;
                                                    case 'CAD':
                                                        $cor = 'lightblue';
                                                        break;
                                                    case 'RENAMO':
                                                        $cor = 'cornflowerblue';
                                                        break;
                                                    default:
                                                        $cor = 'chartreuse';
                                                        break;
                                                }
                                            @endphp
                                            <tr>
                                                <td class="partido">{{ $resultado->nome_partido }}</td>
                                                <td class="percetagem" style="display: flex; align-items: center;">
                                                    @if ($totalValidos > 0)
                                                        <div class="pillar pillar-1"
                                                            style="width: {{ ($resultado->total_votos / $totalValidos) * 100 }}%; background-color: {{ $cor }};">
                                                        </div>
                                                        &nbsp;
                                                        <span>{{ number_format(($resultado->total_votos / $totalValidos) * 100, 1) }}%</span>
                                                    @else
                                                        <div class="pillar pillar-1"
                                                            style="width: 0%; background-color: {{ $cor }};">
                                                        </div>
                                                        &nbsp;<span>0%</span>
                                                    @endif
                                                </td>
                                                <td>{{ $resultado->total_votos }}</td>
                                                <!-- Exibindo o número de eleitores -->
                                            </tr>
                                            <?php $resto += $resultado->total_votos; ?>
                                        @endforeach

                                        {{-- Mostra o total dos "Outros" --}}
                                        <tr>
                                            <td class="partido">Outros</td>
                                            <td class="percetagem" style="display: flex; align-items: center;">
                                                @if ($totalValidos > 0)
                                                    <div class="pillar pillar-1"
                                                        style="width: {{ (($totalValidos - $resto) / $totalValidos) * 100 }}%; background-color: chartreuse;">
                                                    </div>
                                                @else
                                                    <div class="pillar pillar-1"
                                                        style="width: 0%; background-color: chartreuse;"></div>
                                                @endif
                                                &nbsp;
                                                @if ($totalValidos > 0)
                                                    <span>{{ number_format((($totalValidos - $resto) / $totalValidos) * 100, 1) }}%</span>
                                                @else
                                                    <span>0%</span>
                                                @endif
                                            </td>
                                            <td>{{ $totalValidos - $resto }}</td>
                                    </table>
                                </div>

                            </div>
                            <div style="margin-bottom: 2% !important" class="col-md-3">
                                <div class="dashboard">
                                    <canvas id="myPieChart"></canvas>
                                </div>
                            </div>
                            <div class="col-md-5" style="margin-bottom: 2% !important; margin-letf: 0% !important">
                                <div class="dashboard" style="text-align: ">
                                    <table class="tabela3" style="width: 100% !important; ">
                                        <tr>
                                            <th>Inscritos</th>
                                            <td>{{ $inscritos }}</td>
                                        </tr>
                                        <tr>
                                            <th>Votantes</th>
                                            <th>{{ $totalVotos }}</th>
                                            @if ($inscritos > 0)
                                                <th>{{ number_format(($totalVotos * 100) / $inscritos, 1, ',', '.') }}%
                                                </th>
                                            @else
                                                0%
                                            @endif
                                            <th>% de inscritos</th>
                                        </tr>
                                        <tr>
                                            <td>Votos Nulo</td>
                                            <td>{{ $totalVotosNulo }}</td>
                                            <td>
                                                @if ($totalVotos > 0)
                                                    {{ number_format(($totalVotosNulo * 100) / $totalVotos, 1, ',', '.') }}
                                                    %
                                                @else
                                                    0%
                                                @endif
                                            </td>
                                            <td>% de votantes</td>
                                        </tr>
                                        <tr>
                                            <td>Votos em Branco</td>
                                            <td>{{ $totalVotosBranco }}</td>
                                            @if ($totalVotos > 0)
                                                <td>{{ number_format(($totalVotosBranco * 100) / $totalVotos, 1, ',', '.') }}
                                                    %</td>
                                            @else
                                                0%
                                            @endif
                                            <td>% de votantes</td>
                                        </tr>
                                        <tr>
                                            <th>Votos Válidos</th>
                                            <th>{{ $totalValidos }}</th>
                                            @if ($totalVotos > 0)
                                                <th>{{ number_format(($totalValidos * 100) / $totalVotos, 1, ',', '.') }}
                                                    %</th>
                                            @else
                                                0%
                                            @endif
                                            <th>% de votantes</th>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{-- Legenda das Cores --}}
                        <div class="row g-3 dashboard"
                            style="margin-left: 1px; margin-right: 1px; margin-top: 2%; display: flex; text-align:center; justify-content: space-between; align-items: center; color:white">
                            @foreach ($resultados->take(4) as $index => $resultado)
                                @php
                                    $cor = '';
                                    switch ($resultado->nome_partido) {
                                        case 'FRELIMO':
                                            $cor = 'red';
                                            break;
                                        case 'MDM':
                                            $cor = 'blue';
                                            break;
                                        case 'CAD':
                                            $cor = 'lightblue';
                                            break;
                                        case 'RENAMO':
                                            $cor = 'cornflowerblue';
                                            break;
                                        default:
                                            $cor = 'gray';
                                            break;
                                    }
                                @endphp
                                <span
                                    style="flex: 1; text-align: center; background-color: {{ $cor }};">{{ $index + 1 }}º
                                    Lugar - {{ $resultado->nome_partido }}</span>
                            @endforeach
                            <span style="flex: 1; text-align: center; background-color: chartreuse;">Outros</span>
                        </div>
                        {{-- Fim da Legenda --}}
                    </div>
                @else
                    <h4 style="background:rgba(247, 207, 134, 0.5); text-align: center; margin-top: 2%; padding: 1%">Sem
                        Resultados neste local</h4>
            @endif
            {{-- Fim resultados  --}}


            <div id="slider" style="margin-top: 20px">
                <div class="slide active" style="background-color: darkorange; padding:18PX;">
                    Acompanhe aqui resultados Eleitoral em tempo Real
                </div>
                <div class="slide" style="background-color: rgb(245, 212, 171); padding:18PX;">
                    Visita os resultados dos anos anteriores clicando no menu ano Eleitoral
                </div>
                <div class="slide" style="background-color: rgb(180, 143, 98); padding:18PX;">
                    Visualiza resultados da sua província ou distrito de preferência
                </div>
                <div class="slide" style = "background-color: chartreuse; padding:18PX;">
                    Por Eleições Justas e Transparentes, 2024
                </div>
            </div>
        </div>
    </div>

    </div>
    <footer>
        <span>Por eleições justas e transparentes, @2024 Todos Direitos Reservados</span>
    </footer>

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let currentSlide = 0;
        const slides = document.querySelectorAll('.slide');

        function showSlide(index) {
            slides.forEach((slide, i) => {
                slide.classList.toggle('active', i === index);
            });
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % slides.length;
            showSlide(currentSlide);
        }

        setInterval(nextSlide, 3000); // Muda a cada 5 segundos

        // Inicializar o gráfico de pizza com os dados dinâmicos
        var ctx = document.getElementById('myPieChart').getContext('2d');

        // Calcular os dados para os quatro primeiros e o total de "Outros"
        var topResults = [];
        var otherResults = 0;
        @foreach ($resultados as $index => $resultado)
            @if ($index < 4)
                topResults.push({{ $resultado->total_votos }});
            @else
                otherResults += {{ $resultado->total_votos }};
            @endif
        @endforeach

        var chartData = topResults;
        if (otherResults > 0) {
            chartData.push(otherResults);
        }

        var chartLabels = [
            @foreach ($resultados->take(4) as $resultado)
                '{{ $resultado->nome_partido }}',
            @endforeach
            'Outros'
        ];

        var chartColors = [
            @foreach ($resultados->take(4) as $resultado)
                @php
                    $cor = '';
                    switch ($resultado->nome_partido) {
                        case 'FRELIMO':
                            $cor = 'red';
                            break;
                        case 'MDM':
                            $cor = 'blue';
                            break;
                        case 'CAD':
                            $cor = 'lightblue';
                            break;
                        case 'RENAMO':
                            $cor = 'cornflowerblue';
                            break;
                        default:
                            $cor = 'gray';
                            break;
                    }
                @endphp
                    '{{ $cor }}',
            @endforeach
            'chartreuse'
        ];

        var myPieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: chartLabels,
                datasets: [{
                    data: chartData,
                    backgroundColor: chartColors
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw + '%';
                            }
                        }
                    },
                    datalabels: {
                        formatter: (value, ctx) => {
                            let sum = ctx.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                            let percentage = (value * 100 / sum).toFixed(1).replace('.', ',') + "%";
                            return percentage;
                        },
                        color: '#fff',
                        font: {
                            weight: 'bold'
                        }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });

        $(document).ready(function() {
            $('#distritoSearch').on('input', function() {
                var searchTerm = $(this).val().toLowerCase();
                $('#distritoList .menu-item').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(searchTerm) > -1);
                });
            });
        });

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

        // Salva a posição de rolagem antes do recarregamento
        window.addEventListener('beforeunload', function() {
            localStorage.setItem('scrollPosition', window.scrollY);
        });

        // Restaura a posição de rolagem após o recarregamento
        window.addEventListener('load', function() {
            var scrollPosition = localStorage.getItem('scrollPosition');
            if (scrollPosition) {
                window.scrollTo(0, scrollPosition);
            }
        });
        // Recarrega a página a cada 5 segundos
        setInterval(function() {
            location.reload(true);
        }, 5000);
    </script>
@endsection
@endsection
