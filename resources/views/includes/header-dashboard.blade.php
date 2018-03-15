<header class="main-header">
    <!-- Logo -->
    <a href="{{ URL::to('dashboard') }}" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>APAA</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>APAA</b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-user"></i>
              @if(Auth::check())
                <span class="hidden-xs">{{ Auth::user()->nome." ".Auth::user()->sobrenome }}</span>
              @endif
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
				<i class="fa fa-user fa-2x"></i>
                @if(Auth::check())
                  <p>
                    {{ Auth::user()->nome." ".Auth::user()->sobrenome }}
                    <small></small>
                  </p>
                @endif
              </li>
              <!-- Menu Body -->
              <li class="user-body">
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="{{ route('editar-perfil',["id" => Auth::user()->id])}}" class="btn btn-default btn-flat"><i class="fa fa-cogs"></i> Perfil</a>
                </div>
                <div class="pull-right">
                  <a href="{{route('sair')}}" class="btn btn-default btn-flat"><i class="fa fa-sign-out"></i> Sair</a>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>