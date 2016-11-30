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
        <h1> Sugerencias</h1>
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
                <form class="form-horizontal" action="{{route('sugerencias_post')}}" method="POST" enctype="multipart/form-data" style="margin-top: 20px;">
                  <input type="hidden" name="_token" value="{{ csrf_token() }}">
                  <div class="form-group  @if ($errors->has('tema')) has-error @endif">
                    <label for="tema" class="col-sm-3 control-label">Tema</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="tema" name="tema" value="{{old('tema')}}">
                    </div>
                    @if ($errors->has('tema'))
                        <span class="help-block text-center col-sm-12">
                            <strong>{{ $errors->first('tema') }}</strong>
                        </span>
                    @endif
                  </div>
                  <div class="form-group  @if ($errors->has('sugerencia')) has-error @endif">
                    <label for="sugerencia" class="col-sm-3 control-label">Referencia</label>
                    <div class="col-sm-8">
                      <textarea class="form-control" id="sugerencia" name="sugerencia" rows="3">{{old('sugerencia')}}</textarea>
                    </div>
                    @if ($errors->has('sugerencia'))
                        <span class="help-block text-center col-sm-12">
                            <strong>{{ $errors->first('sugerencia') }}</strong>
                        </span>
                    @endif
                  </div>
                  <div class="text-center">
                    <button type="submit" class="btn btn-jnj">Enviar</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
      @include('layouts.footer')
    </div><!--/.container-->
    @include('layouts.javascript')
    <script src="{{asset('js/offcanvas.js')}}"></script>
@stop
