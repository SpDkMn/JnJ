@extends('layouts.template')

@section('css-1')
    <!-- Custom styles for this template -->
    <link href="{{asset('css/offcanvas.css')}}" rel="stylesheet">
    <link href="{{asset('css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/bootstrap-chosen.css')}}" rel="stylesheet">
    <link href="{{asset('css/jquery.dataTables.min.css')}}" rel="stylesheet">
@stop

@section('content')
  @include('layouts.sidebar')
  <div class="jumbotron">
    <div class="container">
      <h1> Cargar Avance Procesados </h1>
    </div>
  </div>
  <div class="container">
    <div class="row">
      <div class="panel panel-default">
        <div class="panel-body text-center">
          <select data-placeholder="distribuidora" id="distribuidora" class="chosen-select">
            <option value=""></option>
            @foreach($distribuidoras as $dist)
            <option value="{{$dist->id}}">{{$dist->name}}</option>
            @endforeach
          </select>
        </div>
      </div>
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
          <form class="form-horizontal" id="cargar_avances_procesados" action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
            <table id="data_catalogo" class="display" cellspacing="0" width="100%">
                  <thead>
                      <tr>
                        <th>Nombre</th>
                        <th>Periodo</th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Fin</th>
                        <th>Seleccionar</th>
                      </tr>
                  </thead>
                  <tfoot>
                      <tr>
                        <th>Nombre</th>
                        <th>Periodo</th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Fin</th>
                        <th>Seleccionar</th>
                      </tr>
                  </tfoot>
              </table>
            <div class="form-group @if ($errors->has('fechaDeInicioDeVenta')) has-error @endif">
              <label for="fechaDeInicioDeVenta" class="col-sm-4 control-label">Fecha inicio de venta</label>
              <div class="input-group date col-sm-7">
                <input type="text" id="fechaDeInicioDeVenta" name="fechaDeInicioDeVenta" class="form-control datepicke" placeholder="dd-mm-aaaa" readonly="" value="{{ old('fechaDeInicioDeVenta') }}">
                <div class="input-group-addon">
                  <span class="glyphicon glyphicon-calendar calendarInicio"></span>
                </div>
              </div>
              @if ($errors->has('fechaDeInicioDeVenta'))
              <span class="help-block text-center"><strong>{{ $errors->first('fechaDeInicioDeVenta') }}</strong></span>
              @endif
            </div>
            <div class="form-group @if ($errors->has('fechaFinDeVenta')) has-error @endif">
              <label for="fechaFinDeVenta" class="col-sm-4 control-label">Fecha fin de venta</label>
              <div class="input-group date col-sm-7">
                <input type="text" id="fechaFinDeVenta" name="fechaFinDeVenta" class="form-control datepicke" placeholder="dd-mm-aaaa" readonly="" value="{{ old('fechaFinDeVenta') }}">
                <div class="input-group-addon">
                  <span class="glyphicon glyphicon-calendar calendarFin"></span>
                </div>
              </div>
              @if ($errors->has('fechaFinDeVenta'))
              <span class="help-block text-center">
                <strong>{{ $errors->first('fechaFinDeVenta') }}</strong>
              </span>
              @endif
            </div>
            <div class="form-group @if ($errors->has('archivoDeAvance')) has-error @endif">
              <label for="archivoDeAvance" class="col-sm-4 control-label">Ingresar archivo de avance</label>
              <div class="col-sm-8" style="padding:0px;">
                <input type="file" id="archivoDeAvance" name="archivoDeAvance" accept="application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
              </div>
              @if ($errors->has('archivoDeAvance'))
              <span class="help-block text-center">
                <strong>{{ $errors->first('archivoDeAvance') }}</strong>
              </span>
              @endif
            </div>
            <div class="text-center">
              <button type="submit" class="btn btn-jnj">Enviar Avance</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    @include('layouts.footer')
  </div><!--/.container-->
    @include('layouts.javascript')
    <script src="{{asset('js/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('js/bootstrap-datepicker.es.js')}}"></script>
    <script src="{{asset('js/chosen.jquery.js')}}"></script>
    <script src="{{asset('js/offcanvas.js')}}"></script>
    <script src="{{asset('js/jquery.dataTables.min.js')}}"></script>
    <script>
    $(function () {
      $('#data_catalogo').DataTable();
      $('.calendarInicio').datepicker({language:'es'})
      .on('changeDate',function(ev){
        date = ev.dates
        day = date[0].getDate()
        month = ((date[0].getMonth()).toString().length == 1) ? "0"+(date[0].getMonth()+1) : date[0].getMonth()
        year = date[0].getUTCFullYear()
        EndDate =  day+"-"+month+"-"+year
        $('#fechaDeInicioDeVenta').val(EndDate)
      })
      $('.calendarFin').datepicker({language:'es'})
      .on('changeDate',function(ev){
        date = ev.dates
        day = date[0].getDate()
        month = ((date[0].getMonth()).toString().length == 1) ? "0"+(date[0].getMonth()+1) : date[0].getMonth()
        year = date[0].getUTCFullYear()
        EndDate =  day+"-"+month+"-"+year
        $('#fechaFinDeVenta').val(EndDate)
      })
      $('.chosen-select').chosen()
      $('.chosen-select').on('change', function(evt, params) {
        $.ajax( "{{ url('distribuidora')}}/avances/"+params.selected )
          .done(function( data, textStatus, jqXHR ) {
            $('#cargar_avances_procesados').attr('action', "{{ url('distribuidora')}}/avances/procesar/"+params.selected);
            //$('.checkbox').remove();
            $(".registro").remove();
            $.each( data, function( key, dato ) {
                var nuevaFila="<tr class='registro'>";
                nuevaFila+="<td>"+dato.name+"</td>";
                nuevaFila+="<td>"+dato.month+"-"+dato.year+"</td>";
                nuevaFila+="<td>"+dato.fecha_inicio+"</td>";
                nuevaFila+="<td>"+dato.fecha_fin+"</td>";
                nuevaFila+="<td><div class='checkbox'><label><input type='checkbox' name='avances[]' value='"+dato.id+"'></label></div></td>";
                nuevaFila+="</tr>";
                $("#data_catalogo").append(nuevaFila);
                string = '<div class="checkbox"><label><input type="checkbox" name="avances[]" value="'+dato.id+'">'+dato.name+'</label></div>'
                //$( "#_token" ).after(string)
            });
            $('#data_catalogo').DataTable();

          })
          .fail(function() {
            alert( "error" );
          })
      })
    })
    </script>
@stop
