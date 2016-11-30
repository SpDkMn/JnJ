@extends('layouts.template')

@section('css-1')
    <!-- Custom styles for this template -->
    <link href="{{asset('css/offcanvas.css')}}" rel="stylesheet">
    <link href="{{asset('css/jquery.dataTables.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
@stop

@section('content')
  @include('layouts.sidebar')
  <div class="jumbotron">
    <div class="container">
      <h1> Cargar Cierre </h1>
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
          <form class="form-horizontal" action="{{route('upload_cierre_post')}}" method="POST" enctype="multipart/form-data">
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
            <div class="form-group @if ($errors->has('fechaDeInicio')) has-error @endif">
              <label for="fechaDeInicio" class="col-sm-3 control-label">Fecha de inicio</label>
              <div class="input-group date col-sm-8" style="padding-right: 15px;padding-left: 15px;">
                <input type="text" id="fechaDeInicio" name="fechaDeInicio" class="form-control datepicke" placeholder="dd-mm-aaaa" readonly="" value="{{ old('fechaDeInicio') }}">
                <div class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar calendarInicio"></span>
                </div>
              </div>
              @if ($errors->has('fechaDeInicio'))
                  <span class="help-block text-center">
                      <strong>{{ $errors->first('fechaDeInicio') }}</strong>
                  </span>
              @endif
            </div>
            <div class="form-group @if ($errors->has('fechaDeFin')) has-error @endif">
              <label for="fechaDeFin" class="col-sm-3 control-label">Fecha de fin</label>
              <div class="input-group date col-sm-8" style="padding-right: 15px;padding-left: 15px;">
                <input type="text" id="fechaDeFin" name="fechaDeFin" class="form-control datepicke" placeholder="dd-mm-aaaa" readonly="" value="{{ old('fechaDeFin') }}">
                <div class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar calendarFin"></span>
                </div>
              </div>
              @if ($errors->has('fechaDeFin'))
                  <span class="help-block text-center">
                      <strong>{{ $errors->first('fechaDeFin') }}</strong>
                  </span>
              @endif
            </div>
              Seleccione las distribuidoras que se van a cargar
              <table class="table" style="background-color: #f9f9f9;">
                <thead style="color: #f30617;">
                  <tr>
                    <th></th>
                    <th>ESTADO</th>
                    <th>CODIGO</th>
                    <th>CANAL</th>
                    <th>DISTRIBUIDORAS</th>
                    <th>SUSTENTO</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
              <div class="text-center" id="formato">
              </div>
            <div class="form-group @if ($errors->has('archivoDeCierre')) has-error @endif">
              <label for="archivoDeCierre" class="col-sm-4 control-label">Ingresar archivo de cierre</label>
              <div class="col-sm-8" style="padding:0px;">
                <input type="file" id="archivoDeCierre" name="archivoDeCierre" accept="application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"  disabled="true">
              </div>
              @if ($errors->has('archivoDeCierre'))
              <span class="help-block text-center"><strong>{{ $errors->first('archivoDeCierre') }}</strong></span>
              @endif
            </div>
            <div class="text-center">
              <button type="submit" class="btn btn-jnj">Cargar Cierre</button>
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
  <script src="{{asset('js/bootstrap-datepicker.min.js')}}"></script>
  <script src="{{asset('js/bootstrap-datepicker.es.js')}}"></script>
  <script>
  $(document).ready(function() {
    $(".table> tbody:last").children().remove();
    $('#concursos').on('change', function(evt, params) {
      $id_concurso = $("#concursos option:selected").val()
      $.ajax("{{url('API/v1/concurso')}}/"+$id_concurso)
        .done(function(data,textStatus,jqXHR){
          var f_fin = data.f_fin.split('-');
          var f_inicio = data.f_inicio.split('-');
          $f_inicio = f_inicio[2]+'-'+f_inicio[1]+'-'+f_inicio[0]
          $f_fin = f_fin[2]+'-'+f_fin[1]+'-'+f_fin[0]
          $('#fechaDeInicio').val($f_inicio)
          $('#fechaDeFin').val($f_fin)
        })
        .fail(function(){
          console.log('error');
        })
      $.ajax( "{{url('API/v1/cuota/concursos')}}/"+$id_concurso )
        .done(function( data, textStatus, jqXHR ) {
          $(".table> tbody:last").children().remove();
          $.each(data, function(i, item) {
            $row = '<tr role="row" class="odd">'
            $row += '<td>'+item.CHEKCBOX+'</td>'
            $row += '<td>'+item.ESTADO+'</td>'
            $row += '<td>'+item.CODIGO+'</td>'
            $row += '<td>'+item.CANAL+'</td>'
            $row += '<td>'+item.RAZONSOCIAL+'</td>'
            $row += '<td>'+item.ARCHIVOS+'</td>'
            $row += '</tr>'
            $('.table').append($row)
          });
          $( "#formato" ).children().remove()
          $( "#formato" ).append("<button class='btn btn-jnj' type='submit' id='download_formato' name='submit' value='formato'>Descargar formato</button>")
          $( "#archivoDeCierre" ).prop( "disabled", false );
          $('.checkbox').on('click',function() {
            if($(this).is(':checked')) {
              $("#distribuidora-"+$(this).val()).attr("required", true);
              $("#distribuidora-"+$(this).val()).prop('disabled', false)
            } else {
              $("#distribuidora-"+$(this).val()).attr("required", false);
              $("#distribuidora-"+$(this).val()).prop('disabled', true)
            }
          });
        })
        .fail(function() {
          $( "#formato" ).children().remove()
          $(".table> tbody:last").children().remove();
          $( "#archivoDeCierre" ).prop( "disabled", true );
        })
    })
  });
  </script>
@stop
