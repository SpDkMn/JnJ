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
      <h1> Distribuidoras participantes </h1>
    </div>
  </div>
  <div class="container">
    <div class="row">
      <div class="panel panel-default">
        <div class="panel-body">
          <table id="data_representante" class="display" cellspacing="0" width="100%">
            <thead>
              <tr>
                <th>CODIGO</th>
                <th>ZONA</th>
                <th>RAZONSOCIAL</th>
                <th>TELEFONO</th>
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
      $('#data_representante').DataTable({"ajax": '{{route('concursos_distribuidoras',['id'=>$id])}}'});
    });
    </script>
@stop
