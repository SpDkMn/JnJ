<?php
## Ruta para el login/logout
Route::auth();

Route::group(['namespace' => '\Ejecutivo', 'middleware' => ['auth','ejecutivo']], function () {

  ## Pagina de inicio al loguearse
  Route::get('/',['as'=>'home_path','uses'=>'HomeController@index']);

  ## Muestra lista de concursos
  Route::get('/concursos',['as'=> 'concursos_view','uses'=>'ConcursoController@view']);
  ## Ruta JSON con los concursos
  Route::get('/API/v1/concursos',['as'=>'concursos_list_ejecutivo','uses'=>'ConcursoController@list_view']);
  Route::get('/API/v1/concurso/{id}',['as'=>'concurso_show','uses'=>'ConcursoController@show']);

  Route::get('concursos/distribuidoras/{id}',['as'=>'concursos_distribuidoras_view','uses'=>'ConcursoController@view_distribuidoras']);
  Route::get('API/v1/concursos/{id}',['as'=>'concursos_distribuidoras','uses'=>'ConcursoController@list_distribuidoras']);
  ## Ruta descargar concurso
  Route::get('download/concurso/{id}',['as'=>'concurso_download','uses'=>'ConcursoController@download']);

  Route::get('/API/v1/cuota/concursos/{id}',['as'=>'distribuidoras_concursos','uses'=>'DistribuidoraController@distribuidoras_concursos_list']);
  Route::get('/API/v1/cuota/concursos/new/{id}',['as'=>'distribuidoras_concursos','uses'=>'DistribuidoraController@distribuidoras_concursos_list_new']);

  ## Muestra la vista para cargar avance
  Route::get('/upload/avance',['as'=>'upload_avance','uses'=>'AvanceController@create']);
  ## Ruta para cargar el avance
  Route::post('/upload/avance',['as'=>'upload_avance_post','uses'=>'AvanceController@store']);
  Route::post('/upload/avance/fin',['as'=>'upload_avance_post_2','uses'=>'AvanceController@storeF']);
  ## Descargar formato

  ## Muestra la vista para cargar cierre
  Route::get('/upload/cierre',['as'=>'upload_cierre','uses'=>'CierreController@create']);
  ## Ruta para cargar el cierre
  Route::post('/upload/cierre',['as'=>'upload_cierre_post','uses'=>'CierreController@store']);
  Route::post('/upload/cierre/fin',['as'=>'upload_cierre_post_2','uses'=>'CierreController@storeF']);

  ## Muestra la vista para cargar cuota
  Route::get('/upload/cuota',['as'=>'upload_cuota','uses'=>'CuotaController@create']);

  ## Ruta para cargar la cuota
  Route::post('/upload/cuota',['as'=>'upload_cuota_post','uses'=>'CuotaController@store']);
  Route::post('/upload/cuota/fin',['as'=>'upload_cuota_post_2','uses'=>'CuotaController@storeF']);

  ## Ruta para ver formulario de reportes
  Route::get('/reporte/cuotas',['as' => 'reporte_cuota', 'uses' => 'CuotaController@reporte']);
  Route::post('/reporte/cuotas',['as' => 'reporte_couta_post','uses' => 'CuotaController@reporte_view']);

  Route::get('/reporte/avances',['as' => 'reporte_avances', 'uses' => 'AvanceController@reporte']);
  Route::post('/reporte/avances',['as' => 'reporte_avances_post', 'uses' => 'AvanceController@reporte_view']);

  Route::get('/reporte/cierres',['as' => 'reporte_cierres', 'uses' => 'CierreController@reporte']);
  Route::post('/reporte/cierres',['as' => 'reporte_cierres_post', 'uses' => 'CierreController@reporte_view']);
});
