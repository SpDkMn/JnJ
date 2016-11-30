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
      <h1> Concursos </h1>
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
          <form class="form-horizontal" action="{{route('concurso_update',['id'=>$concurso->id])}}" method="POST" enctype="multipart/form-data">
            <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
            <div class="form-group  @if ($errors->has('nombreDelConcurso')) has-error @endif">
              <label for="nombreDelConcurso" class="col-sm-3 control-label">Nombre del concurso</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="nombreDelConcurso" name="nombreDelConcurso" value="{{$concurso->name}}">
              </div>
              @if ($errors->has('nombreDelConcurso'))
              <span class="help-block text-center col-sm-12"><strong>{{ $errors->first('nombreDelConcurso') }}</strong></span>
              @endif
            </div>
            <div class="form-group  @if ($errors->has('periodo')) has-error @endif">
              <label for="periodo" class="col-sm-3 control-label">Periodo</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="periodo" name="periodo" value="{{$concurso->periodo}}">
              </div>
              @if ($errors->has('periodo'))
              <span class="help-block text-center col-sm-12"><strong>{{ $errors->first('periodo') }}</strong></span>
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
            <!--div class="form-group  @if ($errors->has('codigoDeConcurso')) has-error @endif">
              <label for="codigoDeConcurso" class="col-sm-3 control-label">Codigo de Concurso</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="codigoDeConcurso" name="codigoDeConcurso" value="{{old('codigoDeConcurso')}}">
              </div>
              @if ($errors->has('codigoDeConcurso'))
              <span class="help-block text-center col-sm-12"><strong>{{ $errors->first('codigoDeConcurso') }}</strong></span>
              @endif
            </div-->
            <div class="form-group">
              <!--label for="volumen" class="col-sm-4 control-label"> <input type="checkbox" id="volumen" name="volumen" class="volumen" checked="{{old('volumen')}}" value="true"> Volumen</label-->
              <label for="cobertura" class="col-sm-3 control-label"> <input type="checkbox" id="cobertura" name="cobertura" class="cobertura" @if($concurso->cobertura == 1) checked @endif value="true"> Cobertura</label>
            </div>
            <div class="form-group">
              <label for="condicion" class="col-sm-3 control-label"> <input type="checkbox" id="condicion" name="condicion" class="condicion" @if(!empty($concurso->key_condition)) checked @endif value="true"> Condición</label>
              <div class="col-sm-8" style="padding:0px;">
                <div class="form-group">
                  <label for="producto" class="col-sm-2 control-label">Producto</label>
                  <div class="col-sm-9" style="padding:0px;">
                    <input type="text" class="form-control" id="producto" name="producto" value="{{$concurso->key_condition}}">
                  </div>
                </div>
                <div class="form-group">
                  <label for="cantidad" class="col-sm-2 control-label">Cantidad</label>
                  <div class="col-sm-9" style="padding:0px;">
                    <input type="text" class="form-control" id="cantidad" name="cantidad" value="{{$concurso->value_condition}}">
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group @if ($errors->has('archivoDeConcurso')) has-error @endif">
              <label for="archivoDeConcurso" class="col-sm-4 control-label">Ingresar archivo del concurso</label>
              <div class="col-sm-8" style="padding:0px;">
                <input type="file" id="archivoDeConcurso" name="archivoDeConcurso" accept="application/msword,application/vnd.ms-powerpoint,application/pdf">
              </div>
              @if ($errors->has('archivoDeConcurso'))
              <span class="help-block text-center"><strong>{{ $errors->first('archivoDeConcurso') }}</strong></span>
              @endif
            </div>
            <div class="text-center">
              <button type="submit" class="btn btn-jnj">Cargar Concurso</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    @include('layouts.footer')
    </div><!--/.container-->
    @include('layouts.javascript')
    <script src="{{asset('js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('js/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('js/bootstrap-datepicker.es.js')}}"></script>
    <script src="{{asset('js/offcanvas.js')}}"></script>
    <script>
    $(document).ready(function() {
      var date = new Date();
      var i = 0;
      $('.calendarInicio').datepicker({language:'es',format:'dd-mm-yyyy'})
      .on('changeDate',function(ev){
        date = ev.dates
        day = date[0].getDate()
        if(day < 10) day = '0'+day
        month = ((date[0].getMonth()).toString().length == 1) ? "0"+(date[0].getMonth()+1) : date[0].getMonth()
        year = date[0].getUTCFullYear()
        EndDate =  day+"-"+month+"-"+year
        $('#fechaDeInicio').val(EndDate)
        if(i==0){
          i++
          $('.calendarFin').datepicker({language:'es', startDate: EndDate,format:'dd-mm-yyyy'})
          .on('changeDate',function(ev){
            date = ev.dates
            day = date[0].getDate()
            if(day < 10) day = '0'+day
            month = ((date[0].getMonth()).toString().length == 1) ? "0"+(date[0].getMonth()+1) : date[0].getMonth()
            year = date[0].getUTCFullYear()
            EndDate =  day+"-"+month+"-"+year
            $('#fechaDeFin').val(EndDate)
          })
        }else{
          $('.calendarFin').datepicker('update', '')
          $('#fechaDeFin').val("")
          $('.calendarFin').datepicker('setStartDate', EndDate)
        }
      })
      $f_inicio = '{{$concurso->f_inicio}}'.split("-")
      day = $f_inicio[2]
      month = $f_inicio[1]
      year = $f_inicio[0]
      $('.calendarInicio').datepicker('setDate',new Date(year,month-1,day))
      $f_fin = '{{$concurso->f_fin}}'.split("-")
      day = $f_fin[2]
      month = $f_fin[1]
      year = $f_fin[0]
      $('.calendarFin').datepicker('setDate',new Date(year,month-1,day))

      $('.condicion').on('click', function() {
        console.log($(this).is(':checked'))
        if( $(this).is(':checked') ){
          $("#producto").prop('disabled', false);
          $("#cantidad").prop('disabled', false);
        } else {
          $("#producto").prop('disabled', true);
          $("#cantidad").prop('disabled', true);
        }
      })
    });
    </script>
@stop
