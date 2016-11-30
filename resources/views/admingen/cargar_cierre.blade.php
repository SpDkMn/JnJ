@extends('layouts.template')

@section('css-1')
    <!-- Custom styles for this template -->
    <link href="{{asset('css/offcanvas.css')}}" rel="stylesheet">
    <link href="{{asset('css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/jquery.dataTables.min.css')}}" rel="stylesheet">
@stop

@section('content')
  @include('layouts.sidebar')
  <div class="jumbotron">
    <div class="container">
      <h1> Cargar Cierres Procesados </h1>
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
          <form class="form-horizontal" action="" method="POST" enctype="multipart/form-data" style="margin-top: 20px;">
            <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
              <table id="data_catalogo" class="display" cellspacing="0" width="100%">
                  <thead>
                      <tr>
                        <th>Distribuidora</th>
                        <th>Nombre</th>
                        <th>Periodo</th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Fin</th>
                        <th>Seleccionar</th>
                      </tr>
                  </thead>
                  @foreach($distribuidoras as $dist)
                  <tr>
                    <td>{{$dist->DISTRIBUIDORA}}</td>
                    <td>{{$dist->NAME}}</td>
                    <td>{{$dist->PERIODO}}</td>
                    <td>{{$dist->FINI}}</td>
                    <td>{{$dist->FFIN}}</td>
                    <td><input type="checkbox" name="cierres[]" value="{{$dist->ID}}"></td>
                  </tr>
                  @endforeach
                  <tfoot>
                      <tr>
                        <th>Distribuidora</th>
                        <th>Nombre</th>
                        <th>Periodo</th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Fin</th>
                        <th>Seleccionar</th>
                      </tr>
                  </tfoot>
              </table><br/>
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
                <span class="help-block text-center"><strong>{{ $errors->first('fechaFinDeVenta') }}</strong></span>
                @endif
              </div>
              <div class="form-group @if ($errors->has('archivoDeCierre')) has-error @endif">
                <label for="archivoDeCierre" class="col-sm-4 control-label">Ingresar archivo de cierre</label>
                <div class="col-sm-8" style="padding:0px;">
                  <input type="file" id="archivoDeCierre" name="archivoDeCierre" accept="application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                </div>
                @if ($errors->has('archivoDeCierre'))
                <span class="help-block text-center">
                  <strong>{{ $errors->first('archivoDeCierre') }}</strong>
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
    })
    </script>
@stop
