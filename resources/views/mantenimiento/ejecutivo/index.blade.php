@extends('layouts.template')

@section('css-1')
    <!-- Custom styles for this template -->
    <link href="{{asset('css/offcanvas.css')}}" rel="stylesheet">
    <link href="{{asset('css/jquery.dataTables.min.css')}}" rel="stylesheet">
@stop

@section('content')
  @include('layouts.sidebar')
  <div class="jumbotron">
    <div class="container">
      <h1> Mantenimiento Ejecutivo </h1>
    </div>
  </div>
  <div class="container">
    <div class="row">
      <!--div class="col-xs-12 col-lg-12 text-center">
        <p style="background:#e5101f;opacity:0.8;padding-top:5px;padding-bottom:5px;">
          <a href="#" style="color:white;">Descarga aqui el formato del archivo de ejecutivos <img src="{{asset('img/excel_icon.jpg')}}" alt="Archivo de excel" class="img-thumbnail img-responsive"></a>
        </p>
      </div><!--/.col-xs-12.col-lg-12.text-center-->
    </div><!--/row-->
    <div class="row">
      @if(!empty(session('status_data')))
      <div class="alert alert-success" role="alert">{{session('status_data')}}</div>
      @endif
      @if(!empty(session('error_data')))
      <div class="alert alert-danger" role="alert">{{session('error_data')}}</div>
      @endif
      <div class="panel panel-default">
        <div class="panel-body">
          <form class="form-inline" action="{{route('mantenimiento_ejecutivo_post')}}" method="POST" enctype="multipart/form-data">
            <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
            <div class="form-group @if ($errors->has('cargaDeEjecutivos')) has-error @endif">
              <label for="cargaDeEjecutivos">Cargar Ejecutivos</label>
              <input type="file" class="form-control" id="cargaDeEjecutivos" name="cargaDeEjecutivos" accept="application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
              @if ($errors->has('cargaDeEjecutivos'))
              <span class="help-block text-center">
                <strong>{{ $errors->first('cargaDeEjecutivos') }}</strong>
              </span>
              @endif
            </div>
              <button type="submit" class="btn btn-jnj" style="margin-bottom: 0;">Cargar Ejecutivos</button>
          </form>
        </div>
      </div>

      <div class="panel panel-default">
        <div class="panel-body">
          <table id="data_ejecutivo" class="display" cellspacing="0" width="100%">
            <thead>
              <tr>
                <th>DNI</th>
                <th>CODIGO</th>
                <th>APELLIDO</th>
                <th>NOMBRE</th>
                <th>CORREO</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
    @include('layouts.footer')
    </div><!--/.container-->
    @include('layouts.javascript')
    <script src="{{asset('js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('js/offcanvas.js')}}"></script>
    <script>
    $(document).ready(function() {
      $('#data_ejecutivo').DataTable({"ajax": '{{route('ejecutivo_list')}}'});
    });
    </script>
@stop
