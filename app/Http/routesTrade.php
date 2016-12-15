<?php
  Route::get('/reportes/distribuidoras',['as'=>'reporte_distribuidoras_view','uses'=>'DistribuidoraController@getReporte']);
  Route::get('/API/V1/reporte/distribuidoras',['as'=>'distribuidora_list_personalizada','uses'=>'DistribuidoraController@lista']);

  Route::get('/confirmar/cierre',['as'=>'confirmar_cierre','uses'=>'CierreController@getConfirmar']);
  Route::post('/confirmar/cierre',['as'=>'confirmar_cierre','uses'=>'CierreController@postConfirmar']);
  Route::post('/confirmar/cierre/proceso',['as'=>'confirmar_cierre_2','uses'=>'CierreController@postConfirmarProceso']);

  ## Ruta para ver formulario de reportes
  Route::get('/reportes/cuotas',['as' => 'reporte_cuota_representante', 'uses' => 'CuotaController@getReporte']);
  Route::post('/reportes/cuotas',['as' => 'reporte_couta_post_representante','uses' => 'CuotaController@postReporte']);

  Route::get('/API/v1/cuotas/concursos/{id}',['as'=>'distribuidoras_concursos_representante','uses'=>'DistribuidoraController@distribuidora_lista']);

  Route::get('/reportes/avances',['as' => 'reporte_avance_representate', 'uses' => 'AvanceController@getReporte']);
  Route::post('/reportes/avances',['as' => 'reporte_avance_post_representate', 'uses' => 'AvanceController@postReporte']);

  Route::get('/reportes/cierres',['as' => 'reporte_cierre_representate', 'uses' => 'CierreController@getReporte']);
  Route::post('/reportes/cierres',['as' => 'reporte_cierre_post_representate', 'uses' => 'CierreController@postReporte']);

  Route::get('/concurso',['as'=>'concurso_view','uses'=>'ConcursoController@index']);
  Route::get('/API/v1/concurso',['as'=>'concurso_list','uses'=>'ConcursoController@lista']);
  Route::get('/concurso/editar/{id}',['as'=>'concurso_editar','uses'=>'ConcursoController@edit']);
  Route::post('/concurso/editar/{id}',['as'=>'concurso_update','uses'=>'ConcursoController@update']);
  Route::get('/concurso/eliminar/{id}',['as'=>'concurso_destroy','uses'=>'ConcursoController@destroy']);

  Route::post('/concurso',['as'=>'concurso_post','uses'=>'ConcursoController@store']);
  Route::get('/concurso/download/{id}',['as'=>'descargar_catalogo','uses'=>'ConcursoController@download']);
