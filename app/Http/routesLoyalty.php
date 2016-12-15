<?php
  ## Carga de montos de cierre
  Route::get('/procesar/cierre',['as'=>'procesar_cierre_view','uses'=>'CierreController@getProcesar']);
  Route::post('/procesar/cierre',['as'=>'cierre_loyalty','uses'=>'CierreController@postProcesar']);

  ## Mantenimiento de representantes
  Route::get('/mantenimiento/representante',['as'=>'mantenimiento_representante_view','uses'=>'RepresentanteController@index']);
  Route::get('/API/v1/mantenimiento/representante',['as'=>'representante_list','uses'=>'RepresentanteController@list']);
  Route::post('/mantenimiento/representante/confirmar',['as'=>'mantenimiento_representante_post','uses'=>'RepresentanteController@create']);
  Route::post('/mantenimiento/representante',['as'=>'mantenimiento_representante_post2','uses'=>'RepresentanteController@store']);

  ## Mantenimiento de ejecutivos
  Route::get('/mantenimiento/ejecutivo',['as'=>'mantenimiento_ejecutivo_view','uses'=>'EjecutivoController@index']);
  Route::get('/API/v1/mantenimiento/ejecutivo',['as'=>'ejecutivo_list','uses'=>'EjecutivoController@list']);
  Route::post('/mantenimiento/ejecutivo/confirmar',['as'=>'mantenimiento_ejecutivo_post','uses'=>'EjecutivoController@create']);
  Route::post('/mantenimiento/ejecutivo',['as'=>'mantenimiento_ejecutivo_post2','uses'=>'EjecutivoController@store']);

  ## Mantenimiento de Distribuidora
  Route::get('/mantenimiento/distribuidora',['as'=>'mantenimiento_distribuidora_view','uses'=>'DistribuidoraController@index']);
  Route::get('/API/v1/mantenimiento/distribuidora',['as'=>'distribuidora_list','uses'=>'DistribuidoraController@list']);
  Route::post('/mantenimiento/distribuidora/confirmar',['as'=>'mantenimiento_distribuidora_post','uses'=>'DistribuidoraController@create']);
  Route::post('/mantenimiento/distribuidora',['as'=>'mantenimiento_distribuidora_post2','uses'=>'DistribuidoraController@store']);

  ## Mantenimiento de Supervisores
  Route::get('/mantenimiento/vendedor',['as'=>'mantenimiento_vendedor_view','uses'=>'SupervisorController@index']);
  Route::get('/API/v1/mantenimiento/vendedor',['as'=>'vendedor_list','uses'=>'SupervisorController@list']);
  Route::post('/mantenimiento/vendedor/confirmar',['as'=>'mantenimiento_vendedor_post','uses'=>'SupervisorController@create']);
  Route::post('/mantenimiento/vendedor',['as'=>'mantenimiento_vendedor_post2','uses'=>'SupervisorController@store']);
  /*** **/
  ## Mantenimiento de Cuotas
  Route::get('/mantenimiento/cuota',['as'=>'mantenimiento_cuota_view','uses'=>'CuotaController@index']);
  Route::get('/API/v1/mantenimiento/cuota',['as'=>'cuota_list','uses'=>'CuotaController@list']);
  Route::post('/mantenimiento/cuota',['as'=>'mantenimiento_cuota_post','uses'=>'CuotaController@create']);
  /*** **/
  ## Reportes
  Route::get('/API/v1/admin/cuotas/concursos/{id}',['as'=>'distribuidoras_concursos_representante','uses'=>'DistribuidoraController@distribuidoras_list']);

  Route::get('/reportes/admin/concursos',['as'=>'reporte_concursos_admin','uses'=>'ConcursoController@getReporte']);
  Route::get('/API/v1/admin/concurso',['as'=>'concurso_admin_list','uses'=>'ConcursoController@list']);

  Route::get('/reportes/admin/cuotas',['as' => 'reporte_cuota_representante_admin', 'uses' => 'CuotaController@getReporte']);
  Route::post('/reportes/admin/cuotas',['as' => 'reporte_couta_post_admin','uses' => 'CuotaController@postReporte']);

  Route::get('/reportes/admin/avances',['as' => 'reporte_avance_admin', 'uses' => 'AvanceController@getReporte']);
  Route::post('/reportes/admin/avances',['as' => 'reporte_avance_post_admin', 'uses' => 'AvanceController@postReporte']);

  Route::get('/reportes/admin/cierres',['as' => 'reporte_cierres_admin', 'uses' => 'CierreController@getReporte']);
  Route::post('/reportes/admin/cierres',['as' => 'reporte_cierre_post_admin', 'uses' => 'CierreController@postReporte']);
