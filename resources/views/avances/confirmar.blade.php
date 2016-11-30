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
      <h1> Cargar Avances </h1>
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
          <form class="form-horizontal" id="form-1" name="form-1" action="{{ route('upload_avance_post_2') }}" method="POST" enctype="multipart/form-data">
            <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" id="codconcurso" name="codconcurso" value="{{ $codconcurso }}">
            @foreach($coddistribuidora as $cd)
            <input type="hidden" name="checked[]" value="{{ $cd }}">
            @endforeach
            La siguiente es la lista de elementos correctos (<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>) que seran cargados y errores (<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>) que se omitirán en la carga.
            <table class="table" style="background-color: #f9f9f9;">
              <thead style="color: #f30617;">
                <tr>
                  <th></th>
                  @foreach($header as $h)
                  <th>{{strtoupper(str_replace("_"," ",$h))}}</th>
                  @endforeach
                </tr>
              </thead>
              <tbody>
                @foreach($errores as $es)
                  <tr>
                  <td><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></td>
                  @foreach($es as $e)
                    <td>{{$e}}</td>
                  @endforeach
                  </tr>
                @endforeach
                @foreach($correctos as $es)
                  <tr>
                  <td><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></td>
                  @foreach($es as $e)
                    <td>{{$e}}</td>
                  @endforeach
                  </tr>
                @endforeach
              </tbody>
            </table>
            <div class="text-center">
              <button type="submit" class="btn btn-jnj" name="submit" value="{{$file}}">Confirmar Avances</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @include('layouts.footer')
  </div><!--/.container-->
  @include('layouts.javascript')
  <script src="{{asset('js/jquery.dataTables.min.js')}}"></script>
  <script src="{{asset('js/offcanvas.js')}}"></script>
@stop
