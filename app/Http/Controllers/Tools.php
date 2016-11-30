<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use App\File as MFile;

class Tools{

  // Retorna el string en mayusculas
  public static function uppercase($string){
    $charLower = ['á','é','í','ó','ú','ñ'];
    $charUpper = ['Á','É','Í','Ó','Ú','Ñ'];
    $upperString = str_replace($charLower,$charUpper,$string);
    $upperString = strtoupper($upperString);
    return $upperString;
  }

  public static function has_files($files){
    $meses = ['ENERO','FEBRERO','MARZO','ABRIL','MAYO','JUNIO','JULIO','AGOSTO','SETIEMBRE','OCTUBRE','NOVIEMBRE','DICIEMBRE'];
    $array = array();
    foreach($files as $f){
      if(!is_null($f->file)){
        if($f->file->success == 1){
          array_push($array,$f->file);
        }
      }
    }
    if(!empty($array)){
      $lastFile = array_pop($array);
      $mes_anterior = array_search($lastFile->month,$meses);
      $year = $lastFile->year;
      $month = $meses[++$mes_anterior];
      $periodo = ($month . ' ' .$year);
    }else{
      // Si no tiene archivos subidos
      // Ruta del archivo de configuración
      $filename = self::PATH_FILE.'config.txt';

      // obtiene lo que dice el archivo quitando caracteres del sistema \t\n etc
      $contents = trim(File::get($filename));

      // Genera la variable periodo para mostrar al usuario
      list($month, $year) = explode(",", $contents);
      $periodo = ($month . ' ' .$year);
    }
    return implode(',',[$month,$year,$periodo]);
  }

  // $string = archivoDeAvance | archivoDeCierre
  // $path = /home/Code/jandj/app/files/AvancesDistribuidoras | /home/Code/jandj/app/files/CierresDistribuidoras
  // $type = 0 | 1
  public static function save_file($dis,$u,$year,$month,$fechaI,$fechaF,$request,$string,$path,$type){
    // genera el nombre del nuevo archivo a guardar
    $name = implode('-',[$dis->id,$u->id,$year,$month,date('d-m-Y-hisu')]);
    $name .= '.'.$request->file($string)->guessExtension();

    // Guarda el archivo en la ruta establecida
    $request->file($string)->move($path, $name);

    // Guardo los datos en la tabla files
    $xlsCargado = new MFile;
    $xlsCargado->name = $name;
    $xlsCargado->user_id = $u->id;
    $xlsCargado->distribuidora_id = $dis->id;
    $xlsCargado->fecha_inicio = $fechaI;
    $xlsCargado->fecha_fin = $fechaF;
    $xlsCargado->type = $type;
    $xlsCargado->procesado = '0';
    $xlsCargado->year = $year;
    $xlsCargado->month = $month;
    $xlsCargado->save();
  }
}
