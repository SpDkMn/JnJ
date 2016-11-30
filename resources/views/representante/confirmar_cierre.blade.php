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
      <h1> Confirmar Cierre </h1>
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
          <form class="form-horizontal" action="{{route('confirmar_cierre_post')}}" method="POST">
            <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
            <table id="tabla_confirmar" class="table table-striped">
              <thead>
                <tr><th>#</th><th>Archivo</th><th>Periodo</th><th>Descargar</th><th>Confirmar</th></tr>
              </thead>
              <tbody>
                @foreach($archivosDeCierre as $archivo)
                <tr>
                  <th scope="row">{{$archivo->id}}</th><td>{{$archivo->name}}</td><td>{{$archivo->month}}-{{$archivo->year}}</td><td>
                    <a href="{{route('descargar_cierre_procesado',['id'=>$archivo->id])}}"><img src="{{asset('img/excel_icon.jpg')}}" alt="Archivo de excel" class="img-thumbnail img-responsive"></a>
                  </td>
                  <td><div class="checkbox text-center"><label><input type="checkbox" name="cierres[]" value="{{$archivo->id}}"></label></div></td>
                </tr>
                @endforeach
              </tbody>
            </table>
            <div class="text-center">
              <button type="submit" class="btn btn-jnj">Confirmar Cierre</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    @include('layouts.footer')
  </div><!--/.container-->
    @include('layouts.javascript')
    <script src="{{asset('js/offcanvas.js')}}"></script>
    <script src="{{asset('js/jquery.dataTables.min.js')}}"></script>
    <script>
      $(document).ready(function() {
        $('#tabla_confirmar').DataTable();
    } );
    </script>
@stop
