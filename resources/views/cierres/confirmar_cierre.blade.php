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
      <h1> Confirmar Cierres </h1>
    </div>
  </div>
  <div class="container">
    <div class="row">
      <div class="panel panel-default">
        <div class="panel-body">
          <form class="form-horizontal" action="{{route('confirmar_cierre')}}" method="POST" enctype="multipart/form-data">
            <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
            <div class="form-group  @if ($errors->has('nombreDelConcurso')) has-error @endif">
              <label for="nombreDelConcurso" class="col-sm-3 control-label">Seleccione un concurso</label>
              <div class="col-sm-8">
                <select id="concursos" name="concursos" class="form-control">
                  <option></option>
                  @foreach($concursos as $c)
                  <option value="{{$c->id}}">{{$c->titulo}} - {{$c->periodo}}</option>
                  @endforeach
                </select>
              </div>
              @if ($errors->has('nombreDelConcurso'))
              <span class="help-block text-center col-sm-12"><strong>{{ $errors->first('nombreDelConcurso') }}</strong></span>
              @endif
            </div>
            Seleccione las distribuidoras que se van a cargar
            <table class="table" id="table" style="background-color: #f9f9f9;">
              <thead style="color: #f30617;">
                <tr>
                  <th></th>
                  <th>CODIGO</th>
                  <th>DISTRIBUIDORAS</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
            <div class="text-center">
              <button type="submit" class="btn btn-jnj">Generar Reporte</button>
            </div>
          </form>
          <form class="form-horizontal" action="{{route('confirmar_cierre_2')}}" method="POST" enctype="multipart/form-data">
            <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
            <table class="table" style="background-color: #f9f9f9;">
              <thead style="color: #f30617;">
                <tr>
                  @foreach($header as $h)
                  <th><?php echo $h; ?></th>
                  @endforeach
                </tr>
              </thead>
              <tbody>
                @foreach($cierres as $c)
                  <tr>
                    @foreach($c as $key => $dato)
                      @if($key == 'ID')
                      <td><input type="checkbox" class="checkbox" name="aprobado[]" value="{{$dato}}" checked=""></td>
                      <td><input type="checkbox" class="checkbox" name="desaprobado[]" value="{{$dato}}" ></td>
                      @else
                      <td>{{$dato}}</td>
                      @endif
                    @endforeach
                  </tr>
                @endforeach
              </tbody>
            </table>
            <div class="text-center">
              <button type="submit" class="btn btn-jnj">Confirmar cierres</button>
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
  <script>
  $(document).ready(function() {
    $("#table> tbody:last").children().remove();
    $('#concursos').on('change', function(evt, params) {
      $id_concurso = $("#concursos option:selected").val()
      $.ajax( "{{url('API/v1/cuotas/concursos')}}/"+$id_concurso )
        .done(function( data, textStatus, jqXHR ) {
          $("#table> tbody:last").children().remove();
          $.each(data, function(i, item) {
            $row = '<tr role="row" class="odd">'
            $row += '<td>'+item.CHEKCBOX+'</td>'
            $row += '<td>'+item.CODIGO+'</td>'
            $row += '<td>'+item.RAZONSOCIAL+'</td>'
            $row += '</tr>'
            $('#table').append($row)
          });
        })
        .fail(function() {
          $(".table> tbody:last").children().remove();
        })
    })
  });
  </script>
@stop