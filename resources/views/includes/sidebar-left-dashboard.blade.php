<!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <i class="fa fa-user fa-2x"></i>
        </div>
        @if(Auth::check())
          <div class="pull-left info">
            <p>{{ Auth::user()->nome." ".Auth::user()->sobrenome }}</p>
          </div>
        @endif
      </div>
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
        <li class="header">MENU DE NAVEGAÇÃO</li>
        @if(Auth::user()->perfil == 2)
          <li class="treeview">
            <a href="#">
              <i class="fa fa-bars"></i> <span>Cadastros</span> <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <li><a href="{{ route('listar-usuarios') }}"><i class="fa fa-user-plus"></i> Usuários</a></li>
              <li><a href="{{ route('listar-usuarios-permissoes') }}"><i class="fa fa-user-plus"></i> Permissões Usuários</a></li>
              <li class="treeview">
                <a href="#">
                  <i class="fa fa-book"></i> <span>Plano</span> <i class="fa fa-angle-left pull-right"></i></a>
                  <ul class="treeview-menu">
                    <li>
                      <a href="{{ route('listar-rotinas') }}">
                        <i class="fa fa-calendar-check-o"></i> Rotinas
                      </a>
                    </li>
                    <li>
                      <a href="{{ route('listar-cgs') }}">
                        <i class="fa fa-sign-in"></i> Cgs
                      </a>
                    </li>
                    <li>
                      <a href="{{ route('listar-uges') }}">
                        <i class="fa fa-sign-in"></i> Uge
                      </a>
                    </li>
                    <li>
                      <a href="{{ route('listar-oss') }}">
                        <i class="fa fa-sign-in"></i> Os
                      </a>
                    </li>
                    <li>
                      <a href="{{ route('listar-objetos') }}">
                        <i class="fa fa-sign-in"></i> Objeto
                      </a>
                    </li>
                    <li>
                      <a href="{{ route('listar-tipo-objetos') }}">
                        <i class="fa fa-sign-in"></i> Tipo de Objetos
                      </a>
                    </li>
                  </ul>
              </li>
              <li class="treeview">
                <a href="#">
                  <i class="fa fa-server"></i> <span>Programa</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                  <li>
                    <a href="{{ route('listar-tipo-publicos') }}">
                      <i class="fa fa-sign-in"></i> Tipo do Público
                    </a>
                  </li>
                  <li>
                    <a href="{{ route('listar-engajamento-publicos') }}">
                      <i class="fa fa-sign-in"></i> Engajamento do Público
                    </a>
                  </li>
                  <li>
                    <a href="{{ route('listar-segmento-publicos') }}">
                      <i class="fa fa-sign-in"></i> Segmento do Público
                    </a>
                  </li>
                  <li>
                    <a href="{{ route('listar-regiao-administrativas') }}">
                      <i class="fa fa-sign-in"></i> Região Admnistrativa
                    </a>
                  </li>
                  <li>
                    <a href="{{ route('listar-municipios') }}">
                      <i class="fa fa-sign-in"></i> Município
                    </a>
                  </li>
                  <li>
                    <a href="{{ route('listar-realizadores') }}">
                      <i class="fa fa-sign-in"></i> Realizador
                    </a>
                  </li>
                  <li>
                    <a href="{{ route('listar-tipagens') }}">
                      <i class="fa fa-sign-in"></i> Tipagens
                    </a>
                  </li>
                  <li>
                    <a href="{{ route('listar-linguagem-programas') }}">
                      <i class="fa fa-sign-in"></i> Linguagem
                    </a>
                  </li>
                  <li>
                    <a href="{{ route('listar-generos-linguagem') }}">
                      <i class="fa fa-sign-in"></i> Gênero de Linguagem
                    </a>
                  </li>
                  <li>
                    <a href="{{ route('listar-tipo-eventos') }}">
                      <i class="fa fa-sign-in"></i> Tipo Evento
                    </a>
                  </li>
                </ul>
              </li>
              <li class="treeview">
                <a href="{{ route('listar-usuarios') }}">
                  <i class="fa fa-fire"></i> <span>Ação</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                  <li>
                    <a href="{{ route('listar-especie-acao') }}">
                      <i class="fa fa-sign-in"></i> Espécie da Ação
                    </a>
                  </li>
                  <li>
                    <a href="{{ route('listar-linguagem-acao') }}">
                      <i class="fa fa-sign-in"></i> Linguagem da Ação
                    </a>
                  </li>
                  <li>
                    <a href="{{ route('listar-funcao-acao') }}">
                      <i class="fa fa-sign-in"></i> Função da Ação
                    </a>
                  </li>
                </ul>
              </li>
            </ul>
          </li>
          <!-- SILAS -->
          <li class="treeview">
            <a href="{{ route('listar-regras') }}">
              <i class="fa fa-flag"></i> <span>Regras</span>
            </a>
          </li>
          <li class="treeview">
            <a href="{{ route('listar-planos') }}">
              <i class="fa fa-th"></i> <span>Planos</span>
            </a>
          </li>
          <li class="treeview">
            <a href="{{ route('listar-programas') }}">
              <i class="fa fa-indent"></i> <span>Programas</span>
            </a>
           <!-- <ul class="treeview-menu">

            </ul> -->
          </li>
          <li class="treeview">
            <a href="{{ route('listar-acoes') }}">
              <i class="fa fa-files-o"></i> <span>Ações</span>
            </a>
          </li>
          <li class="treeview">
            <a href="{{ route('listar-indicadores') }}">
              <i class="fa fa-bar-chart"></i> <span>Indicadores</span>
            </a>
          </li>

          <li class="treeview">
            <a href="{{ route('listar-plano-atividades') }}">
              <i class="fa fa-clone"></i> <span>Atividades</span>
            </a>
          </li>
          <li class="treeview">
            <a href="#">
              <i class="fa fa-clipboard"></i> <span>Relatórios</span> <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <li><a href="{{route('planos-relatorio-mensal')}}">
                <i class="fa fa-calendar-o"></i>Mapa</a>
              </li>
              <li><a href="{{route('planos-relatorio-trimestral')}}">
                <i class="fa fa-calendar"></i>Relatório Trimestral</a>
              </li>
              <li><a href="{{route('lista-relatorio')}}">
                <i class="fa fa-calendar"></i>Lista de Relatório Trimestral</a>
              </li>
              <li><a href="{{route('listar-relatorio-programas-atividades')}}">
                <i class="fa fa-calendar"></i>Lista de Relatório de Atividades</a>
              </li>
            </ul>
          </li>
        @elseif(Auth::user()->perfil == 1)
          <li class="treeview">
            <a href="{{ route('listar-plano-atividades') }}">
              <i class="fa fa-clone"></i> <span>Atividades</span>
            </a>
          </li>
          <li class="treeview">
          <a href="#">
              <i class="fa fa-clipboard"></i> <span>Relatórios</span> <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <li><a href="{{route('planos-relatorio-trimestral')}}">
                <i class="fa fa-calendar"></i>Relatório Trimestral</a>
              </li>
            </li>
          </ul>
        </li>
        @endif
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
