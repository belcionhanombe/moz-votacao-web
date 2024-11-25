<style>
    a.link-dark.rounded.venus-title:hover {
        color:white !important;
    }
</style>

<!------------------------remove the style prop static for better experience-->
<div class="row flex-nowrap mr-0">
    <div class="flex-shrink-0 venus-sidebar venus-sidebar-shrink">
      <!--------------------------------------title-->
      <a class="d-flex align-items-center venus-py-1 px-2 link-dark text-decoration-none border-bottom">
        <div class="venus-container">
          <div class="sidebar-brand">
            <img src="/assets/seuvoto.png" alt="" srcset="" width="35px" height="35px">&nbsp;
            <span class="fs-5 fw-semibold venus-title">Gest&atilde;o eleitoral</span>
          </div>
          <button class="btn venus-sidebar-toggle">
            <i class="bi bi-list venus-20px"></i>
          </button>
        </div>
      </a>
      <!---------------------------menu list-->
      <ul class="list-unstyled ps-0" style=" background-color: rgba(247, 207, 134, 0.5) !important;">
            <!------------------------------menu item-->
            <li class="mb-1">
                <button class="btn btn-toggle align-items-center rounded collapsed" style="margin-bottom: 0 !important" data-bs-toggle="collapse" data-bs-target="#orders-collapse" aria-expanded="false">
                  <div style="margin: 0 !important; display: flex; align-items: center;">
                    <i class="bi bi-calendar2-check" style="margin-top: 1%"></i>
                    <span class="venus-title">&nbsp;&nbsp;Ano Eleitoral</span>
                  </div>
                </button>
                <div class="collapse" id="orders-collapse" style="background: rgba(255, 165, 0, 0.5) !important; margin-top: 0 !important;">
                  <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                    @foreach ($todasEleicoes as $eleicao )
                    <li>
                        <a href="{{ url('ResultadosEleitoral/' . $eleicao->ano) }}" class="link-dark rounded venus-title">
                            @if ($eleicao->tipoEleicao == 0)
                                <span>{{$eleicao->ano}} - Gerais</span>
                            @else
                                <span>{{$eleicao->ano}} - Municípais</span>
                            @endif
                        </a>

                    @endforeach

                  </ul>
                </div>
            </li>
            <li class="mb-1">
              <!-----------------------------toggle button-->
              <button class="btn btn-toggle align-items-center rounded collapsed" data-bs-toggle="collapse" data-bs-target="#dashboard-collapse" aria-expanded="false">
                <div>
                    <i class="bi bi-geo-alt-fill"></i></i>&nbsp;
                  <span class="venus-title">Províncias</span>
                </div>
              </button>
              <!-------------------------dropdown menu list-->
              <div class="collapse" id="dashboard-collapse"  style="background: rgb(255, 165, 0,.5) !important;">
                <ul class="btn-toggle-nav list-unstyled fw-normal  small">

                    @if($provincias)
                    @foreach ($provincias as $provincia)
                    <li>
                        <a href="/principal/provincias_{{$provincia->idProvincias}}_{{$ano}}" class="link-dark rounded venus-title" data-provincia="Provincia A">
                        {{$provincia->nome}}
                        </a>
                    </li>
                    @endforeach
                    @endif
                </ul>
              </div>
            </li>
        </ul>
    </div>
  </div>

