@extends('layouts.template')

@section('css-1')
    <!-- Custom styles for this template -->
    <link href="{{asset('css/offcanvas.css')}}" rel="stylesheet">
@stop

@section('content')
  @include('layouts.sidebar')
  <div class="jumbotron">
    <div class="container">
      <h1> Nuevo Concurso </h1>
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
          <form class="form-horizontal" action="{{route('subir_concurso_post')}}" method="POST" enctype="multipart/form-data">
            <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
            <div class="form-group  @if ($errors->has('nombreDelConcurso')) has-error @endif">
              <label for="nombreDelConcurso" class="col-sm-3 control-label">Nombre del concurso</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="nombreDelConcurso" name="nombreDelConcurso" value="{{old('nombreDelConcurso')}}">
              </div>
              @if ($errors->has('nombreDelConcurso'))
              <span class="help-block text-center col-sm-12"><strong>{{ $errors->first('nombreDelConcurso') }}</strong></span>
              @endif
            </div>
            <div class="form-group  @if ($errors->has('periodo')) has-error @endif">
              <label for="periodo" class="col-sm-3 control-label">Periodo</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="periodo" name="periodo" value="{{old('periodo')}}">
              </div>
              @if ($errors->has('periodo'))
              <span class="help-block text-center col-sm-12"><strong>{{ $errors->first('periodo') }}</strong></span>
              @endif
            </div>
            <div class="form-group  @if ($errors->has('codigoDeConcurso')) has-error @endif">
              <label for="codigoDeConcurso" class="col-sm-3 control-label">Codigo de Concurso</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="codigoDeConcurso" name="codigoDeConcurso" value="{{old('codigoDeConcurso')}}">
              </div>
              @if ($errors->has('codigoDeConcurso'))
              <span class="help-block text-center col-sm-12"><strong>{{ $errors->first('codigoDeConcurso') }}</strong></span>
              @endif
            </div>
            <div class="form-group @if ($errors->has('archivoDeConcurso')) has-error @endif">
              <label for="archivoDeConcurso" class="col-sm-4 control-label">Ingresar archivo del concurso</label>
              <div class="col-sm-8" style="padding:0px;">
                <input type="file" id="archivoDeConcurso" name="archivoDeConcurso" accept="
                application/pdf,
                application/vnd.ms-powerpoint,
                application/msword,
                application/vnd.openxmlformats-officedocument.wordprocessingml.document,
                application/vnd.openxmlformats-officedocument.presentationml.presentation
                ">
              </div>
              @if ($errors->has('archivoDeConcurso'))
              <span class="help-block text-center"><strong>{{ $errors->first('archivoDeConcurso') }}</strong></span>
              @endif
            </div>
            <div class="text-center">
              <button type="submit" class="btn btn-jnj">Cargar Concurso</button>
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
