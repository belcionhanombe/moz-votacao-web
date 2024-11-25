@extends('layout.layout')

@section('titulo', 'Relatório')

@section('conteudo')

<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        font-size: 12px;
    }
    header {
        text-align: center;
        padding: 10px;
        border-bottom: 1px solid #000;
    }
    header h1 {
        margin: 0;
        font-size: 20px;
    }
    .logo {
        width: 50px;
        height: auto;
        float: left;
    }
    .header-info {
        text-align: right;
        margin-right: 10px;
    }
    .summary-table, .details {
        margin: 20px 0;
        width: 100%;
        border-collapse: collapse;
    }
    .summary-table th, .summary-table td, .details th, .details td {
        border: 1px solid #000;
        padding: 8px;
        text-align: left;
    }
    .summary-table th, .details th {
        background-color: #f2f2f2;
    }
    h3 {
        margin-top: 30px;
        margin-bottom: 10px;
    }
    footer {
        text-align: center;
        padding: 10px;
        border-top: 1px solid #000;
        font-size: 10px;
        position: relative;
        bottom: 0;
        width: 100%;
    }

    /* Estilos para impressão */
    @page {
        margin: 0;
    }
    body {
        margin: 1%;
    }
    .imprimir {
        margin: 2% !important;
        page-break-after: avoid;
    }
</style>

<div class="imprimir">
    <br><br><br><br>
    <header>
        <div class="logo">
            <img style="width: 500%" src="https://www.zambezia.gov.mz/var/ezdemo_site/storage/images/informacao/noticias-da-provincia/membros-do-stae-tomam-posse-no-distrito-de-quelimane/43190-1-por-MZ/Membros-do-STAE-tomam-posse-no-Distrito-de-Quelimane_articleimage.jpg" alt="Logo" width="50px">
        </div>
        <div class="header-info">
            <p><b>República de Moçambique: {{ $anoDeEleicao }}</b></p>

            @if ($provincia !="0")
            <p> Provincia de  {{ $provincia->nome }}</p>
            @endif
            @if ($distrito != "0")
            <p>Distrito de {{ $distrito->nome }}</p>

            @endif
            @if ($local != "0")
            <p> Centro: {{ $local->nome }}</p>
            @endif

            @if ($mesa != "0")
            <p>Mesa: {{ $mesa->nome }}</p>
            @endif

            <p>Relatório de Resultados</p>
            <p><strong>Data: {{ date('d/m/Y') }}</strong></p>
        </div>
    </header>

    <section class="report-overview">
        <h3>Resumo da Participação Eleitoral</h3>
        <table class="summary-table">
            <thead>
                <tr>
                    <th>Descrição</th>
                    <th>Quantidade</th>
                    <th>Percentual</th>
                    <th>Referência</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Inscritos</td>
                    <td>{{ $inscritos }}</td>
                    <td>100%</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Votantes</td>
                    <td>{{ $totalVotos }}</td>
                    <td>
                        @if($inscritos > 0)
                            {{ number_format(($totalVotos / $inscritos) * 100, 1) }}%
                        @else
                            N/A
                        @endif
                    </td>
                    <td>% de inscritos</td>
                </tr>
                <tr>
                    <td>Votos Nulo</td>
                    <td>{{ $totalVotosNulo }}</td>
                    <td>
                        @if($totalVotos > 0)
                            {{ number_format(($totalVotosNulo / $totalVotos) * 100, 1) }}%
                        @else
                            N/A
                        @endif
                    </td>
                    <td>% de votantes</td>
                </tr>
                <tr>
                    <td>Votos em Branco</td>
                    <td>{{ $totalVotosBranco }}</td>
                    <td>
                        @if($totalVotos > 0)
                            {{ number_format(($totalVotosBranco / $totalVotos) * 100, 1) }}%
                        @else
                            N/A
                        @endif
                    </td>
                    <td>% de votantes</td>
                </tr>
                <tr>
                    <td>Votos Válidos</td>
                    <td>{{ $totalValidos }}</td>
                    <td>
                        @if($totalVotos > 0)
                            {{ number_format(($totalValidos / $totalVotos) * 100, 1) }}%
                        @else
                            N/A
                        @endif
                    </td>
                    <td>% de votantes</td>
                </tr>
            </tbody>
        </table>
    </section>

    <section class="report-details">
        <h3>Detalhes do Relatório</h3>
        <table class="details">
            <thead>
                <tr>
                    <th>Partido</th>
                    <th>Total de Votos</th>
                    <th>Percentual (%)</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalOutros = 0; // Inicializa a variável para somar outros votos
                    $mostVotedCount = 4; // Número de partidos mais votados a serem exibidos
                @endphp

                @foreach($resultados as $index => $resultado)
                    @if($index < $mostVotedCount)
                        <tr>
                            <td>{{ $resultado->nome_partido }}</td>
                            <td>{{ $resultado->total_votos }}</td>
                            <td>
                                @if($totalVotos > 0)
                                    {{ number_format(($resultado->total_votos / $totalValidos) * 100, 1) }}%
                                @else
                                    0%
                                @endif
                            </td>
                        </tr>
                    @else
                        @php
                            $totalOutros += $resultado->total_votos; // Soma os votos dos partidos restantes
                        @endphp
                    @endif
                @endforeach

                <tr>
                    <td>Outros</td>
                    <td>{{ $totalOutros }}</td>
                    <td>
                        @if($totalVotos > 0)
                            {{ number_format(($totalOutros / $totalValidos) * 100, 1) }}%
                        @else
                            N/A
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>
    </section>

    <br><br>
    <footer>
        <p>Relatório gerado automaticamente pelo sistema - {{ date('d/m/Y H:i:s') }}</p>
        <p>Página 1 de 1</p>
    </footer>

</div>

@endsection
