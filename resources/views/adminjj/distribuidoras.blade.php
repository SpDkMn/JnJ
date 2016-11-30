@extends('layouts.template')

@section('css-1')
    <!-- Custom styles for this template -->
    <link href="{{asset('css/offcanvas.css')}}" rel="stylesheet">
    <link href="{{asset('css/bootstrap-chosen.css')}}" rel="stylesheet">
@stop

@section('content')
    <div class="container">
      <div class="row row-offcanvas row-offcanvas-right">
        <div class="col-xs-12 col-sm-9">
          <p class="pull-right visible-xs">
            <button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas">Menu</button>
          </p>
          <div class="jumbotron">
            <h1>Mantenimiento de distribuidoras</h1>
          </div>

          <div class="row">
            <div class="panel panel-default col-md-4 col-md-offset-4">
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

                <form class="form-horizontal" action="" id="mantenimiento" method="POST" enctype="multipart/form-data">
                  <input type="hidden" name="_token" value="{{ csrf_token() }}">
                  <input type="hidden" name="_method" value="PUT">

                  <div class="form-group  @if ($errors->has('codigoDistribuidora')) has-error @endif">
                    <label for="codigoDistribuidora" class="col-sm-3 control-label">Cod.</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="codigoDistribuidora" value="{{old('codigoDistribuidora')}}" disabled>
                    </div>
                    @if ($errors->has('codigoDistribuidora'))
                        <span class="help-block text-center col-sm-12">
                            <strong>{{ $errors->first('codigoDistribuidora') }}</strong>
                        </span>
                    @endif
                  </div>


                  <div class="form-group  @if ($errors->has('nombreDelDistribuidor')) has-error @endif">
                    <label for="nombreDelDistribuidor" class="col-sm-3 control-label">Nombre del Dist.</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="nombreDelDistribuidor" name="nombreDelDistribuidor" value="{{old('nombreDelDistribuidor')}}">
                    </div>
                    @if ($errors->has('nombreDelDistribuidor'))
                        <span class="help-block text-center col-sm-12">
                            <strong>{{ $errors->first('nombreDelDistribuidor') }}</strong>
                        </span>
                    @endif
                  </div>


                  <div class="form-group  @if ($errors->has('ruc')) has-error @endif">
                    <label for="ruc" class="col-sm-3 control-label">Ruc</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="ruc" name="ruc" value="{{old('ruc')}}">
                    </div>
                    @if ($errors->has('ruc'))
                        <span class="help-block text-center col-sm-12">
                            <strong>{{ $errors->first('ruc') }}</strong>
                        </span>
                    @endif
                  </div>


                  <div class="form-group  @if ($errors->has('trading')) has-error @endif">
                    <label for="trading" class="col-sm-3 control-label">Trading</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="trading" value="{{old('trading')}}" disabled>
                    </div>
                    @if ($errors->has('trading'))
                        <span class="help-block text-center col-sm-12">
                            <strong>{{ $errors->first('trading') }}</strong>
                        </span>
                    @endif
                  </div>


                  <div class="form-group  @if ($errors->has('direccion')) has-error @endif">
                    <label for="direccion" class="col-sm-3 control-label">Dirección</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="direccion" name="direccion" value="{{old('direccion')}}">
                    </div>
                    @if ($errors->has('direccion'))
                        <span class="help-block text-center col-sm-12">
                            <strong>{{ $errors->first('direccion') }}</strong>
                        </span>
                    @endif
                  </div>


                  <div class="form-group  @if ($errors->has('referencia')) has-error @endif">
                    <label for="referencia" class="col-sm-3 control-label">Referencia</label>
                    <div class="col-sm-8">
                      <textarea class="form-control" id="referencia" name="referencia" rows="3">{{old('referencia')}}</textarea>
                      <!--input type="text" class="form-control" id="inputEmail3" value="{{(old('fechaFinDeVenta'))?:''}}"-->
                    </div>
                    @if ($errors->has('referencia'))
                        <span class="help-block text-center col-sm-12">
                            <strong>{{ $errors->first('referencia') }}</strong>
                        </span>
                    @endif
                  </div>


                  <div class="form-group  @if ($errors->has('telefono')) has-error @endif">
                    <label for="telefono" class="col-sm-3 control-label">Teléfono</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="telefono" name="telefono" value="{{old('telefono')}}">
                    </div>
                    @if ($errors->has('telefono'))
                        <span class="help-block text-center col-sm-12">
                            <strong>{{ $errors->first('telefono') }}</strong>
                        </span>
                    @endif
                  </div>


                  <div class="form-group  @if ($errors->has('correoElectronico')) has-error @endif">
                    <label for="correoElectronico" class="col-sm-3 control-label">Correo electronico</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="correoElectronico" name="correoElectronico" value="{{old('correoElectronico')}}">
                    </div>
                    @if ($errors->has('correoElectronico'))
                        <span class="help-block text-center col-sm-12">
                            <strong>{{ $errors->first('correoElectronico') }}</strong>
                        </span>
                    @endif
                  </div>


                  <div class="text-center">
                    <button type="submit" class="btn btn-primary">Guardar</button>
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
    <script src="{{asset('js/chosen.jquery.js')}}"></script>
    <script src="{{asset('js/offcanvas.js')}}"></script>
    <script>
    $(function () {
      $id = '{{old('ruc')}}'
      if($id != ''){
        $.ajax( "{{ url('distribuidora/ruc')}}/"+$('#ruc').val())
          .done(function( data, textStatus, jqXHR ) {
            $('#codigoDistribuidora').val(data.id)
            $('#trading').val(data.trading) // trading
            $('#mantenimiento').attr('action', '{{url('distribuidora')}}/'+data.id);
          })
          .fail(function() {
            alert( "error" );
          })
      }
      $('.chosen-select').chosen()
      $('.chosen-select').on('change', function(evt, params) {
        $.ajax( "{{ url('distribuidora')}}/"+params.selected )
          .done(function( data, textStatus, jqXHR ) {
            $('#codigoDistribuidora').val(data.id)
            $('#nombreDelDistribuidor').val(data.name) // name
            $('#ruc').val(data.ruc) // ruc
            $('#trading').val(data.trading) // trading
            $('#direccion').val(data.address) // address
            $('#referencia').val(data.reference) // reference
            $('#telefono').val(data.phone) // phone
            $('#correoElectronico').val(data.email) // email
            $('#mantenimiento').attr('action', '{{url('distribuidora')}}/'+data.id);
          })
          .fail(function() {
            alert( "error" );
          })
      })
    })
    </script>
@stop
