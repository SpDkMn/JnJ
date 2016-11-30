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
      <h1> Procesar </h1>
    </div>
  </div>
  <div class="container">
    <div class="row">
            <div class="panel panel-default">

              <div class="panel-body">

                <form class="form-horizontal">
                  <div class="form-group">
                    <label for="distribuidora" class="col-sm-4 control-label">Distribuidora</label>
                    <div class="input-group date col-sm-7">
                      <input type="text" id="distribuidora" name="distribuidora" class="form-control" readonly="" value="{{ $file->distribuidora->name }}" disabled>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="fechaSubida" class="col-sm-4 control-label">Fecha de subida</label>
                    <div class="input-group date col-sm-7">
                      <input type="text" id="fechaSubida" name="fechaSubida" class="form-control" readonly="" value="{{ $file->created_at }}" disabled>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="periodo" class="col-sm-4 control-label">Periodo</label>
                    <div class="input-group date col-sm-7">
                      <input type="text" id="periodo" name="periodo" class="form-control" readonly="" value="{{ $file->month }} {{ $file->year}}" disabled>
                    </div>
                  </div>
                </form>

                <form class="form-horizontal" action="{{ route('cargar_archivos_procesados',['id'=>$file->id])}}" method="POST" enctype="multipart/form-data">
                  <input type="hidden" name="_token" value="{{ csrf_token() }}">

                  <div class="form-group @if ($errors->has('registrosBuenos')) has-error @endif">
                    <label for="registrosBuenos" class="col-sm-4 control-label">Registros Buenos</label>
                    <div class="input-group date col-sm-8">
                      <input type="text" id="registrosBuenos" name="registrosBuenos" class="form-control" value="{{ old('registrosBuenos') }}">
                    </div>
                    @if ($errors->has('registrosBuenos'))
                        <span class="help-block text-center">
                            <strong>{{ $errors->first('registrosBuenos') }}</strong>
                        </span>
                    @endif
                  </div>

                  <div class="form-group @if ($errors->has('archivoGanadores')) has-error @endif">
                    <label for="archivoGanadores" class="col-sm-4 control-label">Ingresar el archivo de ganadores</label>
                    <div class="col-sm-8" style="padding:0px;">
                      <input type="file" id="archivoGanadores" name="archivoGanadores">
                    </div>
                    @if ($errors->has('archivoGanadores'))
                        <span class="help-block text-center">
                            <strong>{{ $errors->first('archivoGanadores') }}</strong>
                        </span>
                    @endif
                  </div>

                  <div class="form-group @if ($errors->has('registrosErrados')) has-error @endif">
                    <label for="registrosErrados" class="col-sm-4 control-label">Registros Errados</label>
                    <div class="input-group date col-sm-8">
                      <input type="text" id="registrosErrados" name="registrosErrados" class="form-control" value="{{ old('registrosErrados') }}">
                    </div>
                    @if ($errors->has('registrosErrados'))
                        <span class="help-block text-center">
                            <strong>{{ $errors->first('registrosErrados') }}</strong>
                        </span>
                    @endif
                  </div>

                  <div class="form-group @if ($errors->has('archivoErrados')) has-error @endif">
                    <label for="archivoErrados" class="col-sm-4 control-label">Ingresar el archivo de errados</label>
                    <div class="col-sm-8" style="padding:0px;">
                      <input type="file" id="archivoErrados" name="archivoErrados">
                    </div>
                    @if ($errors->has('archivoErrados'))
                        <span class="help-block text-center">
                            <strong>{{ $errors->first('archivoErrados') }}</strong>
                        </span>
                    @endif
                  </div>

                  <div class="text-center">
                    <button type="submit" class="btn btn-primary">Procesar</button>
                  </div>

                </form>
              </div>
            </div>
          </div>
        </div><!--/.col-xs-12.col-sm-9-->
        @include('layouts.sidebar')
      </div><!--/row-->
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
