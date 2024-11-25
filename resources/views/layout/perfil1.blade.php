

<nav class="navbar navbar-expand-lg" style="background-color: rgb(255, 165, 0,.6) !important;">
    <div class="container-fluid">
      <!------------navbar content------------------------>
      <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
        <ul class="navbar-nav ml-auto mb-2 mb-lg-0">
          <li class="nav-item">
            @if($eleicao->tipoEleicao == 0)
            <div id="electionStatus" class="status"></div>
            Eleições Gerais - {{$ano}}
            @else
            <div id="electionStatus" class="status"></div>
            Eleições Municípais - {{$ano}}
            @endif
          </li>
          <!----------------------dropdown -->

        </ul>
      </div>
    </div>
  </nav>
