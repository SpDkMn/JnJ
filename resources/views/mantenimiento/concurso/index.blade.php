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
      <h1> Concursos </h1>
    </div>
  </div>
  <div class="container">
    <div class="row">

      <div class="panel panel-default">
        <div class="panel-body">
          <table id="data_concursos" class="display" cellspacing="0" width="100%">
            <thead>
              <tr>
                <th>PERIODO</th>
                <th>NOMBRE</th>
                <th>INICIO</th>
                <th>FIN</th>
                <th>DESCARGAR</th>
                <th>EDITAR</th>
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
      $('#data_concursos').DataTable({"ajax": '{{route('concurso_admin_list')}}'});
    });
    </script>
@stop
