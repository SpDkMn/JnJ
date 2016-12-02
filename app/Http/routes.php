<?php
## Ruta para el login/logout
Route::auth();

## Rutas para todos los usuarios registrados
Route::group(['middleware' => ['auth','ejecutivo']], function () {

  // Esto lo puede ver:
  //  - Ejecutivo
  //  - Representante
  //  - Loyalty

  ## Pagina de inicio al loguearse
  Route::get('/',['as'=>'home_path','uses'=>'HomeController@index']);

  /**
   * Visualización de concursos
   *
   */
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

  ## Rutas para los usuarios representantes y Loyalty
  Route::group(['middleware' => ['auth','representante']], function () {
    // Esto lo puede ver:
    //  - Representante
    //  - Loyalty
    Route::get('/reportes/distribuidoras',['as'=>'reporte_distribuidoras_view','uses'=>'DistribuidoraController@reporte']);
    Route::get('/API/V1/reporte/distribuidoras',['as'=>'distribuidora_list_personalizada','uses'=>'DistribuidoraController@list_personalizada']);

    Route::get('/confirmar/cierre',['as'=>'confirmar_cierre','uses'=>'CierreController@confirmar_view']);
    Route::post('/confirmar/cierre',['as'=>'confirmar_cierre','uses'=>'CierreController@confirmar']);
    Route::post('/confirmar/cierre/proceso',['as'=>'confirmar_cierre_2','uses'=>'CierreController@confirmar_proceso']);

     ## Ruta para ver formulario de reportes
     Route::get('/reportes/cuotas',['as' => 'reporte_cuota_representante', 'uses' => 'CuotaController@reporte_representante']);
     Route::post('/reportes/cuotas',['as' => 'reporte_couta_post_representante','uses' => 'CuotaController@reporte_view_representante']);
     Route::get('/API/v1/cuotas/concursos/{id}',['as'=>'distribuidoras_concursos_representante','uses'=>'DistribuidoraController@distribuidoras_concursos_list_1']);

     Route::get('/reportes/avances',['as' => 'reporte_avance_representate', 'uses' => 'AvanceController@reporte_representante']);
     Route::post('/reportes/avances',['as' => 'reporte_avance_post_representate', 'uses' => 'AvanceController@reporte_view_representante']);

     Route::get('/reportes/cierres',['as' => 'reporte_cierre_representate', 'uses' => 'CierreController@reporte_representante']);
     Route::post('/reportes/cierres',['as' => 'reporte_cierre_post_representate', 'uses' => 'CierreController@reporte_view_representante']);

    Route::get('/concurso',['as'=>'concurso_view','uses'=>'ConcursoController@index']);
    Route::get('/API/v1/concurso',['as'=>'concurso_list','uses'=>'ConcursoController@list']);
    Route::get('/concurso/editar/{id}',['as'=>'concurso_editar','uses'=>'ConcursoController@edit']);
    Route::post('/concurso/editar/{id}',['as'=>'concurso_update','uses'=>'ConcursoController@update']);
    Route::get('/concurso/eliminar/{id}',['as'=>'concurso_destroy','uses'=>'ConcursoController@destroy']);

    Route::post('/concurso',['as'=>'concurso_post','uses'=>'ConcursoController@store']);
    Route::get('/concurso/download/{id}',['as'=>'descargar_catalogo','uses'=>'ConcursoController@download']);

    ## Rutas para el usuario loyalty
    Route::group(['middleware' => ['auth','loyalty']], function () {
      // Esto lo puede ver:
      //  - Loyalty

      ## Carga de montos de cierre
      Route::get('/procesar/cierre',['as'=>'procesar_cierre_view','uses'=>'CierreController@procesar_view']);
      Route::post('/procesar/cierre',['as'=>'cierre_loyalty','uses'=>'CierreController@procesar_loyalty']);

      ## Mantenimiento de representantes
      Route::get('/mantenimiento/representante',['as'=>'mantenimiento_representante_view','uses'=>'RepresentanteController@index']);
      Route::get('/API/v1/mantenimiento/representante',['as'=>'representante_list','uses'=>'RepresentanteController@list']);
      Route::post('/mantenimiento/representante/confirmar',['as'=>'mantenimiento_representante_post','uses'=>'Representantecontroller@store']);
      Route::post('/mantenimiento/representante',['as'=>'mantenimiento_representante_post2','uses'=>'Representantecontroller@storeF']);

      ## Mantenimiento de ejecutivos
      Route::get('/mantenimiento/ejecutivo',['as'=>'mantenimiento_ejecutivo_view','uses'=>'EjecutivoController@index']);
      Route::get('/API/v1/mantenimiento/ejecutivo',['as'=>'ejecutivo_list','uses'=>'EjecutivoController@list']);
      Route::post('/mantenimiento/ejecutivo/confirmar',['as'=>'mantenimiento_ejecutivo_post','uses'=>'EjecutivoController@store']);
      Route::post('/mantenimiento/ejecutivo',['as'=>'mantenimiento_ejecutivo_post2','uses'=>'EjecutivoController@storeF']);

      ## Mantenimiento de Distribuidora
      Route::get('/mantenimiento/distribuidora',['as'=>'mantenimiento_distribuidora_view','uses'=>'DistribuidoraController@index']);
      Route::get('/API/v1/mantenimiento/distribuidora',['as'=>'distribuidora_list','uses'=>'DistribuidoraController@list']);
      Route::post('/mantenimiento/distribuidora/confirmar',['as'=>'mantenimiento_distribuidora_post','uses'=>'DistribuidoraController@store']);
      Route::post('/mantenimiento/distribuidora',['as'=>'mantenimiento_distribuidora_post2','uses'=>'DistribuidoraController@storeF']);

      ## Mantenimiento de Supervisores
      Route::get('/mantenimiento/vendedor',['as'=>'mantenimiento_vendedor_view','uses'=>'SupervisorController@index']);
      Route::get('/API/v1/mantenimiento/vendedor',['as'=>'vendedor_list','uses'=>'SupervisorController@list']);
      Route::post('/mantenimiento/vendedor/confirmar',['as'=>'mantenimiento_vendedor_post','uses'=>'SupervisorController@store']);
      Route::post('/mantenimiento/vendedor',['as'=>'mantenimiento_vendedor_post2','uses'=>'SupervisorController@storeF']);

      ## Mantenimiento de Cuotas
      Route::get('/mantenimiento/cuota',['as'=>'mantenimiento_cuota_view','uses'=>'CuotaController@index']);
      Route::get('/API/v1/mantenimiento/cuota',['as'=>'cuota_list','uses'=>'CuotaController@list']);
      Route::post('/mantenimiento/cuota',['as'=>'mantenimiento_cuota_post','uses'=>'Representantecontroller@store']);

      ## Reportes
      Route::get('/API/v1/admin/cuotas/concursos/{id}',['as'=>'distribuidoras_concursos_representante','uses'=>'DistribuidoraController@distribuidoras_concursos_list_1_admin']);

      Route::get('/reportes/admin/concursos',['as'=>'reporte_concursos_admin','uses'=>'ConcursoController@reporte_concursos_admin']);
      Route::get('/API/v1/admin/concurso',['as'=>'concurso_admin_list','uses'=>'ConcursoController@list_admin']);

      Route::get('/reportes/admin/cuotas',['as' => 'reporte_cuota_representante_admin', 'uses' => 'CuotaController@reporte_admin']);
      Route::post('/reportes/admin/cuotas',['as' => 'reporte_couta_post_admin','uses' => 'CuotaController@reporte_view_admin']);

      Route::get('/reportes/admin/avances',['as' => 'reporte_avance_admin', 'uses' => 'AvanceController@reporte_admin']);
      Route::post('/reportes/admin/avances',['as' => 'reporte_avance_post_admin', 'uses' => 'AvanceController@reporte_view_admin']);

      Route::get('/reportes/admin/cierres',['as' => 'reporte_cierres_admin', 'uses' => 'CierreController@reporte_admin']);
      Route::post('/reportes/admin/cierres',['as' => 'reporte_cierre_post_admin', 'uses' => 'CierreController@reporte_view_admin']);
    });
  });
});
