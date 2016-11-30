@extends('layouts.template')

@section('css-1')
    <!-- Custom styles for this template -->
    <link href="{{asset('css/signin.css')}}" rel="stylesheet">
@stop

@section('content')
<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">
        <img alt="JnJ" class="imgJnJ" src="{{asset('logo-peru_2_0.png')}}">
      </a>
    </div>
  </div>
</nav>
    <div class="container" style="margin-top: 70px;">
      <div class="panel panel-default">
      <div class="panel-body">
        <form class="col-md-6 col-md-offset-3" role="form" method="POST" action="{{ url('/login') }}">
          {!! csrf_field() !!}
          <h2 class="form-signin-heading">Ingreso al sistema</h2>
          @if ($errors->has('username'))
          <span class="help-block">
            <strong>{{ $errors->first('username') }}</strong>
          </span>
          @endif
          <div class="form-group">
            <label for="inputUsername" class="control-label">Nombre de usuario</label>
            <input type="text" id="inputUsername" class="form-control" placeholder="Nombre de usuario" required="" autofocus="" name="username" value="{{ old('username') }}">
          </div>

          <div class="form-group">
            <label for="inputPassword" class="control-label">Contraseña</label>
            <input type="password" id="inputPassword" class="form-control" placeholder="Contraseña" required="" name="password">
          </div>

          <div class="checkbox">
            <label>
              <input type="checkbox" name="remember"> Recuerdame
            </label>
          </div>
          <button class="btn btn-lg btn-jnj btn-block" type="submit">Entrar</button>
        </form>
      </div>
    </div>
    </div> <!-- /container -->
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="{{asset('js/ie10-viewport-bug-workaround.js')}}"></script>
@stop
