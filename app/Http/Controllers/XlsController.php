<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Mail;
use DB;
use App\Http\Requests;
use App\Distribuidora as Distribuidora;
use App\File as MFile;
use App\Http\Requests\CargarAvanceRequest as CargarAvanceRequest;
use App\Http\Requests\CargarCierreRequest as CargarCierreRequest;
use App\Http\Controllers\Tools as Tools;
use Excel;
class XlsController extends Controller
{
    public function get_avances(){
      // Quien es mi usuario
      $u = Auth::user();

      // Retorna la distribuidora relacionada con el usuario
      $dis = $u->distribuidora;

      // Tiene distribuidoras?
      // Vacio => No es Administrador
      // No Vacio => Administrador de almenos una distribuidora
      if(!is_null($dis)){

        // Retorna los archivos de cierre subidos por la distribuidora
        $files = $dis->files;
        $files =  $files->where('type','1')->where('procesado','0')->where('distribuidora_id',$dis->id);

        // Tiene Archivos?
        list($month, $year,$periodo) = explode(",",Tools::has_files($files));

        // Retorna la vista mostrando los datos de la distribuidora y el periodo actual
        return view('distribuidora.subirAvance',['distribuidora' => $dis,'periodo' => $periodo]);
      }

      // Si no es administrador de una distribuidora retorna a la pantalla anterior
      return redirect()->back();
    }

    public function post_avances(CargarAvanceRequest $request){
      // Quien es mi usuario
      $u = Auth::user();

      // Retorna lista de distribuidoras relacionadas con el usuario
      $dis = $u->distribuidora;

      // Tiene distribuidoras?
      // Vacio => No es Administrador
      // No Vacio => Administrador de almenos una distribuidora
      if(!is_null($dis)){

        // Retorna los archivos de cierre subidos por la distribuidora
        $files = $dis->files;
        $files =  $files->where('type','1')->where('procesado','0')->where('distribuidora_id',$dis->id);

        // Tiene Archivos?
        list($month, $year,$periodo) = explode(",",Tools::has_files($files));

        // Obtengo los datos enviados por el usuario
        $fechaI = $request->input('fechaDeInicioDeVenta');
        $fechaF = $request->input('fechaFinDeVenta');

        // Se envio el archivo?
        if ($request->hasFile('archivoDeAvance')) {

            Tools::save_file($dis,$u,$year,$month,$fechaI,$fechaF,$request,'archivoDeAvance',env('PATH_FILE').'AvancesDistribuidoras',0);

            $data = array(
              'name' => implode('-',[$dis->id,$u->id,$year,$month,date('d-m-Y-hisu')]),
            );
            Mail::send('emails.avance', $data, function ($message) {
              $message->from(env('MAIL_USERNAME'), 'J&J');
              $message->to(env('CORREO_AVANCES'))->subject('Nuevo avance J&J');
            });

            // El archivo subio bien
            return redirect()->back()->with('status_data', 'El archivo fue cargado correctamente.');
        }
      }
      // El archivo no subio
      return redirect()->back()->with('error_data', 'Error al cargar el archivo, por favor intentelo nuevamente.');
    }

    public function get_cierre(){
      // Quien es mi usuario
      $u = Auth::user();

      // Retorna lista de distribuidoras relacionadas con el usuario
      $dis = $u->distribuidora;

      // Tiene distribuidoras?
      // Vacio => No es Administrador
      // No Vacio => Administrador de almenos una distribuidora
      if(!is_null($dis)){

        // Retorna los archivos de cierre subidos por la distribuidora
        $files = $dis->files;
        $files =  $files->where('type','1')->where('procesado','0')->where('distribuidora_id',$dis->id);

        // Tiene Archivos?
        list($month, $year,$periodo) = explode(",",Tools::has_files($files));

        // Retorna la vista mostrando los datos de la distribuidora y el periodo actual
        return view('distribuidora.subirCierre',['distribuidora' => $dis,'periodo' => $periodo]);
      }

      // Si no es administrador de una distribuidora retorna a la pantalla anterior
      return redirect()->back();
    }

    public function post_cierre(CargarCierreRequest $request){
      // Quien es mi usuario
      $u = Auth::user();

      // Retorna lista de distribuidoras relacionadas con el usuario
      $dis = $u->distribuidora;

      // Tiene distribuidoras?
      // Vacio => No es Administrador
      // No Vacio => Administrador de almenos una distribuidora
      if(!is_null($dis)){

        // Retorna los archivos de cierre subidos por la distribuidora
        $files = $dis->files;
        $files =  $files->where('type','1')->where('procesado','0')->where('distribuidora_id',$dis->id);

        // Tiene Archivos?
        list($month, $year,$periodo) = explode(",",Tools::has_files($files));

        // Obtengo los datos enviados por el usuario
        $fechaI = $request->input('fechaDeInicioDeVenta');
        $fechaF = $request->input('fechaFinDeVenta');

        // Se envio el archivo?
        if ($request->hasFile('archivoDeCierre')) {

            Tools::save_file($dis,$u,$year,$month,$fechaI,$fechaF,$request,'archivoDeCierre',env('PATH_FILE').'CierresDistribuidoras',1);

            $data = array(
              'name' => implode('-',[$dis->id,$u->id,$year,$month,date('d-m-Y-hisu')]),
            );
            Mail::send('emails.cierre', $data, function ($message) {
              $message->from(env('MAIL_USERNAME'), 'J&J');
              $message->to(env('CORREO_CIERRE'))->subject('Nuevo Cierre J&J');
            });

            // El archivo subio bien
            return redirect()->back()->with('status_data', 'El archivo fue cargado correctamente.');

        }

        // El archivo no subio
        return redirect()->back()->with('error_data', 'Error al cargar el archivo, por favor intentelo nuevamente.');
      }

      // El archivo no subio
      return redirect()->back()->with('error_data', 'Error al cargar el archivo, por favor intentelo nuevamente.');
    }

    public function download_xls(){
      return response()->download(env('PATH_FILE').'formato.xlsx');
    }

    public function download_avance_procesado($id){
      $file = MFile::where('id',$id)->first();
      return response()->download(env('PATH_FILE').'RegistroAvances/'.$file->name);
    }

    public function download_procesado_cierre($id){
      $file = MFile::where('id',$id)->first();
      return response()->download(env('PATH_FILE').'RegistroCierre/'.$file->name);
    }

    public function download_cierre_procesado($file_id,$coddistribuidora){
      $file = MFile::find($file_id);
      $result = DB::table('avances as a')
      ->select(
      'a.codconcurso as codconcurso',
      'a.codcanal as codcanal',
      'a.coddistribuidora as coddistribuidora',
      'a.codvendedor as codvendedor',
      'a.vendedortipo as vendedortipo',
      'a.ventas as ventas',
      'a.meta as meta'
      )
      ->join('files as f', 'a.file_id', '=', 'f.id')
      ->where('coddistribuidora','=',$coddistribuidora)
      ->where('f.id','=',$file_id)
      ->where('f.type','=',1)
      ->get();
      $array = array();
      foreach($result as $a){
        array_push($array,get_object_vars($a));
      }
      Excel::create(explode('.',$file->name)[0], function($excel) use($array) {
        $excel->sheet('Sheetname', function($sheet) use($array) {
          $sheet->fromArray($array);
        });
      })->download('xlsx');
      dd($result);
    }

    public function download_avance_procesado_representante($id){
      $u = Auth::user();
      $rep = $u->representante;
      $dis = $rep->distribuidoras;
      $array = array();
      foreach($dis as $d){
        array_push($array,$d->coddistribuidora);
      }
      $file = MFile::find($id);
      $result = DB::table('avances as a')
      ->select(
      'a.codconcurso as codconcurso',
      'a.codcanal as codcanal',
      'a.coddistribuidora as coddistribuidora',
      'a.codvendedor as codvendedor',
      'a.vendedortipo as vendedortipo',
      'a.ventas as ventas',
      'a.meta as meta'
      )
      ->join('files as f', 'a.file_id', '=', 'f.id')
      ->whereIn('coddistribuidora',$array)
      ->where('f.id','=',$id)
      ->where('f.type','=',0)
      ->get();
      $array = array();
      foreach($result as $a){
        array_push($array,get_object_vars($a));
      }
      Excel::create(explode('.',$file->name)[0], function($excel) use($array) {
        $excel->sheet('Sheetname', function($sheet) use($array) {
          $sheet->fromArray($array);
        });
      })->download('xlsx');
      dd($result);
    }

    public function download_cierre_procesado_representante($id){
      $u = Auth::user();
      $rep = $u->representante;
      $dis = $rep->distribuidoras;
      $array = array();
      foreach($dis as $d){
        array_push($array,$d->coddistribuidora);
      }
      $file = MFile::find($id);
      $result = DB::table('avances as a')
      ->select(
      'a.codconcurso as codconcurso',
      'a.codcanal as codcanal',
      'a.coddistribuidora as coddistribuidora',
      'a.codvendedor as codvendedor',
      'a.vendedortipo as vendedortipo',
      'a.ventas as ventas',
      'a.meta as meta'
      )
      ->join('files as f', 'a.file_id', '=', 'f.id')
      ->whereIn('coddistribuidora',$array)
      ->where('f.id','=',$id)
      ->where('f.type','=',1)
      ->get();
      $array = array();
      foreach($result as $a){
        array_push($array,get_object_vars($a));
      }
      Excel::create(explode('.',$file->name)[0], function($excel) use($array) {
        $excel->sheet('Sheetname', function($sheet) use($array) {
          $sheet->fromArray($array);
        });
      })->download('xlsx');
      dd($result);
    }

    public function download_cierre_procesado_supervisor($file_id,$codvendedor){
      $file = MFile::find($file_id);
      $result = DB::table('avances as a')
      ->select(
      'a.codconcurso as codconcurso',
      'a.codcanal as codcanal',
      'a.coddistribuidora as coddistribuidora',
      'a.codvendedor as codvendedor',
      'a.vendedortipo as vendedortipo',
      'a.ventas as ventas',
      'a.meta as meta'
      )
      ->join('files as f', 'a.file_id', '=', 'f.id')
      ->where('codvendedor','=',$codvendedor)
      ->where('f.id','=',$file_id)
      ->where('f.type','=',1)
      ->get();
      $array = array();
      foreach($result as $a){
        array_push($array,get_object_vars($a));
      }
      Excel::create(explode('.',$file->name)[0], function($excel) use($array) {
        $excel->sheet('Sheetname', function($sheet) use($array) {
          $sheet->fromArray($array);
        });
      })->download('xlsx');
      dd($result);
    }

    public function download_avance_procesado_supervisor($file_id,$codvendedor){
      $file = MFile::find($file_id);
      $result = DB::table('avances as a')
      ->select(
      'a.codconcurso as codconcurso',
      'a.codcanal as codcanal',
      'a.coddistribuidora as coddistribuidora',
      'a.codvendedor as codvendedor',
      'a.vendedortipo as vendedortipo',
      'a.ventas as ventas',
      'a.meta as meta'
      )
      ->join('files as f', 'a.file_id', '=', 'f.id')
      ->where('codvendedor','=',$codvendedor)
      ->where('f.id','=',$file_id)
      ->where('f.type','=',0)
      ->get();
      $array = array();
      foreach($result as $a){
        array_push($array,get_object_vars($a));
      }
      Excel::create(explode('.',$file->name)[0], function($excel) use($array) {
        $excel->sheet('Sheetname', function($sheet) use($array) {
          $sheet->fromArray($array);
        });
      })->download('xlsx');
      dd($result);
    }
}
