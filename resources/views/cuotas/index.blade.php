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
      <h1> Cargar Cuota </h1>
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
          <form class="form-horizontal" id="form-1" name="form-1" action="{{ route('upload_cuota_post') }}" method="POST" enctype="multipart/form-data">
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
            <table class="table" style="background-color: #f9f9f9;">
              <thead style="color: #f30617;">
                <tr>
                  <th></th>
                  <th>CODIGO</th>
                  <th>CANAL</th>
                  <th>DISTRIBUIDORAS</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
            <div class="text-center" id="formato">
            </div>
            <div class="form-group @if ($errors->has('archivoDeCuota')) has-error @endif">
              <label for="archivoDeCuota" class="col-sm-4 control-label">Ingresar archivo del concurso</label>
              <div class="col-sm-8" style="padding:0px;">
                <input type="file" id="archivoDeCuota" name="archivoDeCuota" accept="application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" disabled="true">
              </div>
              @if ($errors->has('archivoDeCuota'))
              <span class="help-block text-center"><strong>{{ $errors->first('archivoDeCuota') }}</strong></span>
              @endif
            </div>
            <div class="text-center">
              <button type="submit" class="btn btn-jnj" name="submit" value="submit">Cargar Cuotas</button>
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
    $(".table> tbody:last").children().remove();
    $('#concursos').on('change', function(evt, params) {
      $id_concurso = $("#concursos option:selected").val()
      $.ajax( "{{url('API/v1/cuota/concursos')}}/"+$id_concurso )
        .done(function( data, textStatus, jqXHR ) {
          $(".table> tbody:last").children().remove();
          $.each(data, function(i, item) {
            $row = '<tr role="row" class="odd">'
            $row += '<td>'+item.CHEKCBOX+'</td>'
            $row += '<td>'+item.CODIGO+'</td>'
            $row += '<td>'+item.CANAL+'</td>'
            $row += '<td>'+item.RAZONSOCIAL+'</td>'
            $row += '</tr>'
            $('.table').append($row)
          })
          $( "#formato" ).children().remove()
          $( "#formato" ).append("<button class='btn btn-jnj' type='submit' id='download_formato' name='submit' value='formato'>Descargar formato</button>")
          $( "#archivoDeCuota" ).prop( "disabled", false );
        })
        .fail(function() {
          $( "#formato" ).children().remove()
          $(".table> tbody:last").children().remove();
          $( "#archivoDeCuota" ).prop( "disabled", true );
        })
    })
  });
  </script>
@stop
