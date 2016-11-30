@extends('layouts.template')

@section('css-1')
    <!-- Custom styles for this template -->
    <link href="{{asset('css/offcanvas.css')}}" rel="stylesheet">
    <link href="{{asset('css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
@stop

@section('content')
  @include('layouts.sidebar')
  <div class="jumbotron">
    <div class="container">
      <h1> Enviar Avance </h1>
    </div>
  </div>
  <div class="container">
    <div class="row">
      <div class="col-xs-12 col-lg-12 text-center">
        <p style="background:#e5101f;opacity:0.8;padding-top:5px;padding-bottom:5px;">
          <a href="{{route('formato_download')}}" style="color:white;">Descarga aqui el formato del archivo de ventas <img src="{{asset('img/excel_icon.jpg')}}" alt="Archivo de excel" class="img-thumbnail img-responsive"></a>
        </p>
      </div><!--/.col-xs-6.col-lg-4-->
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

                <form class="form-horizontal" action="{{ route('upload_avance_post')}}" method="POST" enctype="multipart/form-data">
                  <input type="hidden" name="_token" value="{{ csrf_token() }}">


                  <div class="form-group @if ($errors->has('fechaDeInicioDeVenta')) has-error @endif">
                    <label for="fechaDeInicioDeVenta" class="col-sm-4 control-label">Fecha inicio de venta</label>
                    <div class="input-group date col-sm-7">
                      <input type="text" id="fechaDeInicioDeVenta" name="fechaDeInicioDeVenta" class="form-control datepicke" placeholder="dd-mm-aaaa" readonly="" value="{{ old('fechaDeInicioDeVenta') }}">
                      <div class="input-group-addon">
                          <span class="glyphicon glyphicon-calendar calendarInicio"></span>
                      </div>
                    </div>
                    @if ($errors->has('fechaDeInicioDeVenta'))
                        <span class="help-block text-center">
                            <strong>{{ $errors->first('fechaDeInicioDeVenta') }}</strong>
                        </span>
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

                  <div class="form-group">
                    <label for="periodo" class="col-sm-4 control-label">Periodo</label>
                    <div class="col-sm-7" style="padding:0px;">
                      <input type="text" class="form-control" id="periodo" value="{{$periodo}}" disabled>
                    </div>
                  </div>

                  <div class="form-group @if ($errors->has('archivoDeAvance')) has-error @endif">
                    <label for="archivoDeAvance" class="col-sm-4 control-label">Ingresar archivo de avance</label>
                    <div class="col-sm-8" style="padding:0px;">
                      <input type="file" id="archivoDeAvance" name="archivoDeAvance">
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
      @include('layouts.footer')
    </div><!--/.container-->
    @include('layouts.javascript')
    <script src="{{asset('js/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('js/bootstrap-datepicker.es.js')}}"></script>
    <script src="{{asset('js/offcanvas.js')}}"></script>
    <script>
    $(function () {
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
