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
      <h1> Mantenimiento Distribuidora </h1>
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
          <form class="form-inline" action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
            <div class="form-group @if ($errors->has('cargaDeDistribuidoras')) has-error @endif">
              <label for="cargaDeDistribuidoras">Cargar distribuidoras</label>
              <input type="file" class="form-control" id="cargaDeDistribuidoras" name="cargaDeDistribuidoras" accept="application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
              @if ($errors->has('cargaDeDistribuidoras'))
              <span class="help-block text-center">
                <strong>{{ $errors->first('cargaDeDistribuidoras') }}</strong>
              </span>
              @endif
            </div>
              <button type="submit" class="btn btn-jnj" style="margin-bottom: 0;">Cargar distribuidora</button>
          </form>
        </div>
      </div>

      <div class="panel panel-default">
        <div class="panel-body">
          <table id="data_representante" class="display" cellspacing="0" width="100%">
            <thead>
              <tr>
                <th>RUC</th>
                <th>CODIGO</th>
                <th>ZONA</th>
                <th>RAZÓN SOCIAL</th>
                <th>TELEFONO</th>
                <th>CORREO</th>
                <th>EJECUTIVO</th>
                <!--th>EDITAR</th-->
                <th>ELIMINAR</th>
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
      $('#data_representante').DataTable({"ajax": '{{route('distribuidora_list_personalizada')}}'});
    });
    </script>
@stop
