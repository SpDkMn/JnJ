@extends('layouts.template')

@section('css-1')
    <!-- Custom styles for this template -->
    <link href="{{asset('css/offcanvas.css')}}" rel="stylesheet">
@stop

@section('content')
  @include('layouts.sidebar')
  <div class="jumbotron">
    <div class="container">
      <h1> Cargar Montos Cierre </h1>
    </div>
  </div>
  <div class="container">
    <div class="row">
      @if(!empty(session('status_data')))
      <div class="alert alert-success" role="alert">{{session('status_data')}}</div>
      @endif
      @if(!empty(session('error_data')))
      <div class="alert alert-danger" role="alert">{{session('error_data')}}</div>
      @endif
      <div class="panel panel-default">
        <div class="panel-body">
          <form class="form-horizontal" action="{{route('cierre_loyalty')}}" method="POST" enctype="multipart/form-data">
            <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
            <div class="form-group  @if ($errors->has('nombreDelConcurso')) has-error @endif">
              <label for="nombreDelConcurso" class="col-sm-3 control-label">Seleccione un concurso</label>
              <div class="col-sm-8">
                <select id="concursos" name="concursos" class="form-control">
                  <option></option>
                  @foreach($concursos as $c)
                  <option value="{{$c->id}}">{{$c->name}} - {{$c->periodo}}</option>
                  @endforeach
                </select>
              </div>
              @if ($errors->has('nombreDelConcurso'))
              <span class="help-block text-center col-sm-12"><strong>{{ $errors->first('nombreDelConcurso') }}</strong></span>
              @endif
            </div>
            <div class="text-center">
              <button type="submit" class="btn btn-jnj" name="submit" value="descargar">Descargar Cierre</button>
            </div>
            <div class="form-group @if ($errors->has('archivoDeCierre')) has-error @endif">
              <label for="archivoDeCierre" class="col-sm-4 control-label">Ingresar archivo de cierre</label>
              <div class="col-sm-8" style="padding:0px;">
                <input type="file" id="archivoDeCierre" name="archivoDeCierre" accept="application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" >
              </div>
              @if ($errors->has('archivoDeCierre'))
              <span class="help-block text-center"><strong>{{ $errors->first('archivoDeCierre') }}</strong></span>
              @endif
            </div>
            <div class="text-center">
              <button type="submit" class="btn btn-jnj">Cargar Cierre</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @include('layouts.footer')
  </div><!--/.container-->
  @include('layouts.javascript')
  <script src="{{asset('js/offcanvas.js')}}"></script>
@stop
