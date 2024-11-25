@if (session()->has('administradores'))
    <div class="row flex-nowrap mr-0">
        <div class="flex-shrink-0 venus-sidebar venus-sidebar-shrink">
            <!-- Sidebar Title -->
            <a class="d-flex align-items-center venus-py-1 px-2 link-dark text-decoration-none border-bottom">
                <div class="venus-container">
                    <div class="sidebar-brand">
                        <img src="/assets/seuvoto.png" alt="Logo" width="35px" height="35px">
                        <span class="fs-5 fw-semibold venus-title">Gestão Eleitoral</span>
                    </div>
                    <button class="btn venus-sidebar-toggle">
                        <i class="bi bi-list venus-20px"></i>
                    </button>
                </div>
            </a>

            <!-- Menu List -->
            <ul class="list-unstyled ps-0" style="background-color: rgba(247, 207, 134, 0.5);">

                <!-- Configurações de Eleições -->
                @if (session()->get('administradores.nivel') == 1)
                    <li class="mb-1">
                        <button class="btn btn-toggle align-items-center rounded collapsed" data-bs-toggle="collapse" data-bs-target="#orders-collapse" aria-expanded="false">
                            <i class="bi bi-gear-wide"></i>
                            <span class="venus-title">&nbsp;Configurações</span>
                        </button>
                        <div class="collapse" id="orders-collapse" style="background: rgba(255, 165, 0, 0.5);">
                            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                                <li><a href="/configE" class="link-dark rounded venus-title">Eleições</a></li>
                                <li><a href="/configM" class="link-dark rounded venus-title">Município</a></li>
                                <li><a href="/partidos" class="link-dark rounded venus-title">Partidos</a></li>
                            </ul>
                        </div>
                    </li>
                @endif

                <!-- Configurações de Administradores -->
                @if (session()->get('administradores.nivel') != 4)
                    <li class="mb-1">
                        <button class="btn btn-toggle align-items-center rounded collapsed" data-bs-toggle="collapse" data-bs-target="#dashboard-collapse" aria-expanded="false">
                            <i class="bi bi-people-fill"></i>&nbsp;
                            <span class="venus-title">
                                {{ session()->get('administradores.nivel') != 3 ? 'Administradores' : 'Administração' }}
                            </span>
                        </button>
                        <div class="collapse" id="dashboard-collapse" style="background: rgba(255, 165, 0, 0.5);">
                            <ul class="btn-toggle-nav list-unstyled fw-normal small">
                                @if (session()->get('administradores.nivel') == 1)
                                    <li><a href="/filtroAdmin" class="link-dark rounded venus-title">Listar Admins Provincial</a></li>
                                @elseif (session()->get('administradores.nivel') == 2)
                                    <li><a href="/filtroAdmin" class="link-dark rounded venus-title">Listar Admins Distrital</a></li>
                                @elseif (session()->get('administradores.nivel') == 3)
                                    <li><a href="/gerirLocal" class="link-dark rounded venus-title">Centro de Votação</a></li>
                                    <li><a href="/filtroAdmin" class="link-dark rounded venus-title">Presidentes de Mesa</a></li>
                                @endif
                                @if (session()->get('administradores.nivel') != 3)
                                    <li><a href="/registar" class="link-dark rounded venus-title"><i class="bi bi-pen"></i>&nbsp;Registar</a></li>
                                @endif
                            </ul>
                        </div>
                    </li>
                @endif

                <!-- Relatório - Ano -->
                <li class="mb-1">
                    <button class="btn btn-toggle align-items-center rounded collapsed" data-bs-toggle="collapse" data-bs-target="#products-collapse" aria-expanded="false">
                        <i class="bi bi-fingerprint"></i>
                        <span class="venus-title">Relatório - Ano</span>
                    </button>
                    <div class="collapse" id="products-collapse" style="background: rgba(255, 165, 0, 0.5);">
                        <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                            <li>
                                <a href="/RelatorioSelecionado/2024" class="link-dark rounded venus-title"><b>ANO: 2024</b></a>
                            </li>
                        </ul>
                    </div>
                </li>

            </ul>
        </div>
    </div>
@endif
