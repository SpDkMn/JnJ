<nav class="navbar navbar-inverse navbar-fixed-top">
  <div class="container-fluid">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="/">
        <img alt="JnJ" class="imgJnJ" src="{{asset('logo-peru_2_0.png')}}">
        </a>
      </div>


      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav navbar-right">
          <li><a href="#">Bienvenido {{Auth::user()->name}} {{Auth::user()->lastname}}</a></li>
          @if(Auth::user()->profile->weight == 10)
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Mantenimiento <span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li><a href="{{route('mantenimiento_representante_view')}}">Representante</a></li>
              <li><a href="{{route('mantenimiento_ejecutivo_view')}}">Ejecutivo</a></li>
              <li><a href="{{route('mantenimiento_distribuidora_view')}}">Distribuidora</a></li>
              <li><a href="{{route('mantenimiento_vendedor_view')}}">Vendedor/Supervisor</a></li>
              <li><a href="{{route('mantenimiento_cuota_view')}}">Cuotas</a></li>
            </ul>
          </li>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Reportes <span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li><a href="{{route('reporte_concursos_admin')}}">Concursos</a></li>
              <li><a href="{{route('reporte_cuota_representante_admin')}}">Cuotas</a></li>
              <li><a href="{{route('reporte_avance_admin')}}">Avances</a></li>
              <li><a href="{{route('reporte_cierres_admin')}}">Cierres</a></li>
            </ul>
          </li>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Procesar Archivos <span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li><a href="{{route('procesar_cierre_view')}}">Cierres</a></li>
            </ul>
          </li>
          @endif

          @if(Auth::user()->profile->weight == 7)
          <li><a href="{{route('confirmar_cierre')}}" class="">Confirmar Cierre</a></li>
          <li><a href="{{route('concurso_view')}}" class="">Concursos</a></li>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Reportes <span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li><a href="{{route('reporte_cuota_representante')}}">Cuotas</a></li>
              <li><a href="{{route('reporte_avance_representate')}}">Avances</a></li>
              <li><a href="{{route('reporte_cierre_representate')}}">Cierres</a></li>
            </ul>
          </li>
          @endif

          @if(Auth::user()->profile->weight == 4)
          <li><a href="{{route('concursos_view')}}" class="">Concursos</a></li>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Cargar Archivos <span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li><a href="{{route('upload_cuota')}}">Cuotas</a></li>
              <li><a href="{{route('upload_avance')}}">Avances</a></li>
              <li><a href="{{route('upload_cierre')}}">Cierres</a></li>
            </ul>
          </li>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Reportes <span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li><a href="{{route('reporte_cuota')}}">Cuotas</a></li>
              <li><a href="{{route('reporte_avances')}}">Avances</a></li>
              <li><a href="{{route('reporte_cierres')}}">Cierres</a></li>
            </ul>
          </li>
          @endif

          <li><a href="{{ url('/logout') }}" class="">Cerrar sesión</a></li>
        </ul>
      </div><!-- /.navbar-collapse -->
  </div>
</nav>
