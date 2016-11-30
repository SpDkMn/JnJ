@extends('layouts.template')

@section('css-1')
    <!-- Custom styles for this template -->
    <link href="{{asset('css/offcanvas.css')}}" rel="stylesheet">
@stop

@section('content')
  @include('layouts.sidebar')
  <div class="jumbotron">
    <div class="container">
      <h1> Mant. Distribuidora </h1>
    </div>
  </div>
  <div class="container">
    <div class="row">
      <div class="panel panel-default">
        <div class="panel-body text-center">
          {{$distribuidora->name}}
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
          <form class="form-horizontal" action="{{ route('distribuidora.update',['distribuidora' => $distribuidora->id])}}" method="POST" enctype="multipart/form-data" style="margin-top: 20px;">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="_method" value="PUT">
            <div class="form-group  @if ($errors->has('codigoDistribuidora')) has-error @endif">
              <label for="codigoDistribuidora" class="col-sm-3 control-label">Cod.</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="codigoDistribuidora" value="{{(old('codigoDistribuidora'))?old('codigoDistribuidora'):$distribuidora->id}}" disabled>
              </div>
              @if ($errors->has('codigoDistribuidora'))
                <span class="help-block text-center col-sm-12"><strong>{{ $errors->first('codigoDistribuidora') }}</strong></span>
              @endif
            </div>
            <div class="form-group  @if ($errors->has('nombreDelDistribuidor')) has-error @endif">
              <label for="nombreDelDistribuidor" class="col-sm-3 control-label">Nombre del Dist.</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="nombreDelDistribuidor" name="nombreDelDistribuidor" value="{{(old('nombreDelDistribuidor'))?old('nombreDelDistribuidor'):$distribuidora->name}}">
              </div>
              @if ($errors->has('nombreDelDistribuidor'))
              <span class="help-block text-center col-sm-12"><strong>{{ $errors->first('nombreDelDistribuidor') }}</strong></span>
              @endif
            </div>
            <div class="form-group  @if ($errors->has('ruc')) has-error @endif">
              <label for="ruc" class="col-sm-3 control-label">Ruc</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="ruc" name="ruc" value="{{(old('ruc'))?old('ruc'):$distribuidora->ruc}}">
              </div>
              @if ($errors->has('ruc'))
              <span class="help-block text-center col-sm-12"><strong>{{ $errors->first('ruc') }}</strong></span>
              @endif
            </div>
            <div class="form-group  @if ($errors->has('trading')) has-error @endif">
              <label for="trading" class="col-sm-3 control-label">Trading</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="trading" value="{{(old('trading'))?old('trading'):$distribuidora->trading}}" disabled>
              </div>
              @if ($errors->has('trading'))
              <span class="help-block text-center col-sm-12"><strong>{{ $errors->first('trading') }}</strong></span>
              @endif
            </div>
            <div class="form-group  @if ($errors->has('direccion')) has-error @endif">
              <label for="direccion" class="col-sm-3 control-label">Dirección</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="direccion" name="direccion" value="{{(old('direccion'))?old('direccion'):$distribuidora->address}}">
              </div>
              @if ($errors->has('direccion'))
              <span class="help-block text-center col-sm-12"><strong>{{ $errors->first('direccion') }}</strong></span>
              @endif
            </div>
            <div class="form-group  @if ($errors->has('referencia')) has-error @endif">
              <label for="referencia" class="col-sm-3 control-label">Referencia</label>
              <div class="col-sm-8"><textarea class="form-control" id="referencia" name="referencia" rows="3">{{(old('referencia'))?old('referencia'):$distribuidora->reference}}</textarea></div>
              @if ($errors->has('referencia'))
              <span class="help-block text-center col-sm-12"><strong>{{ $errors->first('referencia') }}</strong></span>
              @endif
            </div>
            <div class="form-group  @if ($errors->has('telefono')) has-error @endif">
              <label for="telefono" class="col-sm-3 control-label">Teléfono</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="telefono" name="telefono" value="{{(old('telefono'))?old('telefono'):$distribuidora->phone}}">
              </div>
              @if ($errors->has('telefono'))
              <span class="help-block text-center col-sm-12"><strong>{{ $errors->first('telefono') }}</strong></span>
              @endif
            </div>
            <div class="form-group  @if ($errors->has('correoElectronico')) has-error @endif">
              <label for="correoElectronico" class="col-sm-3 control-label">Correo electronico</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="correoElectronico" name="correoElectronico" value="{{(old('correoElectronico'))?old('correoElectronico'):$distribuidora->email}}">
              </div>
              @if ($errors->has('correoElectronico'))
              <span class="help-block text-center col-sm-12"><strong>{{ $errors->first('correoElectronico') }}</strong></span>
              @endif
            </div>
            <div class="text-center">
              <button type="submit" class="btn btn-jnj">Guardar</button>
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
