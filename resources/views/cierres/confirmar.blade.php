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
                  <option value="{{$c->id}}">{{$c->name}} - {{$c->periodo}} - MONTO: <?php echo DB::table('cierres as ci')->where('ci.monto','<>',null)->where('ci.confirmed',null)->where('ci.concurso_id','=',$c->id)->sum('ci.monto'); ?> </option>
                  @endforeach
                </select>
              </div>
              @if ($errors->has('nombreDelConcurso'))
              <span class="help-block text-center col-sm-12"><strong>{{ $errors->first('nombreDelConcurso') }}</strong></span>
              @endif
            </div>
            <div class="distribuidoras" style="display:none">
              Seleccione las distribuidoras que se van a cargar
              <table class="table" style="background-color: #f9f9f9;">
                <thead style="color: #f30617;">
                  <tr>
                    <th></th>
                    <th>CODIGO</th>
                    <th>DISTRIBUIDORAS</th>
                    <th>MONTO</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
            <div class="bloque-reporte text-center" style="display:none">
              <button type="submit" class="btn btn-jnj">Generar Reporte</button>
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
      $(".distribuidoras").show()
      $.ajax( "{{url('API/v1/cuotas/concursos')}}/"+$id_concurso )
        .done(function( data, textStatus, jqXHR ) {
          $(".table> tbody:last").children().remove();
          $.each(data, function(i, item) {
            $row = '<tr role="row" class="odd">'
            $row += '<td>'+((item.MONTO != null)?item.CHEKCBOX:'<input type="checkbox" class="checkbox" disabled>')+'</td>'
            $row += '<td>'+item.CODIGO+'</td>'
            $row += '<td>'+item.RAZONSOCIAL+'</td>'
            $row += '<td>'+((item.MONTO != null)?item.MONTO:'')+'</td>'
            $row += '</tr>'
            $('.table').append($row)
          });
          $( ".bloque-reporte" ).show();
        })
        .fail(function() {
          $(".table> tbody:last").children().remove();
          $( ".bloque-reporte" ).hide();
          $(".distribuidoras").hide()
        })
    })
  });
  </script>
@stop
