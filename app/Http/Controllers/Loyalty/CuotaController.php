<?php

namespace App\Http\Controllers\Loyalty;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use DB;
use App\Concurso as Concurso;
use App\Http\Controllers\DBExcel as DBExcel;

class CuotaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function index(){
         return view('mantenimiento.cuota.index');
     }

     public function list(){
       $result = DB::table('concursos as c')
         ->select(
           'c.name as NOMBRE',
           'c.periodo as PERIODO'
         )
         ->whereNull('c.deleted_at')
         ->orderBy('c.f_inicio','desc')
         ->orderBy('c.name','asc')
         ->get();
       $array = array();
       foreach($result as $a){
         array_push($array,array_values(get_object_vars($a)));
       }
       $array = ["data"=>$array];
       return $array;
     }

     /**
      * Show the form for creating a new resource.
      *
      * @return \Illuminate\Http\Response
      */
     public function create(Request $request){
         // Si piden el formato
         if($request->submit == 'formato'){
           $this->download_formato($request);
         }

         // Valida los campos necesarios para la carga
         $this->validate($request, [
           'concursos' => 'required|exists:concursos,id',
           'checked' => 'required',
           'archivoDeCuota' => 'required|mimes:xls,xlsx',
         ]);

         // Busca el concurso
         $c = Concurso::find($request->concursos);

         // genera el nombre del nuevo archivo a guardar
         $string = 'archivoDeCuota';
         $name = date('d-m-Y-hisu').'.'.$request->file($string)->guessExtension();

         // Guarda el archivo en la ruta establecida
         $path = self::PATH_FILE.'tmp';
         $request->file($string)->move($path, $name);

         // Codigo de las distribuidoras elegidas
         $codigos = DB::table('distribuidoras as d')
           ->select('*')
           ->whereIn('d.id', $request->checked)
           ->get();

         // Carga los datos y los muestra en la web
         return DBExcel::cargarCuota($path,$name,$c,$codigos);
     }

     public function getReporte(){
       $result = DB::table('concursos as c')
        ->select(
          'c.id as id',
          'c.name as titulo',
          'c.periodo as periodo')
        ->whereNull('c.deleted_at')
        ->orderBy('c.f_inicio','desc')
        ->orderBy('c.name','asc')
        ->get();
      return view('cuotas.reporte_admin',['concursos'=>$result]);
    }

    public function postReporte(Request $request){
      $co = $request->concursos;
      $ch = $request->checked;

      $select = [
        's.dni as DNI',
        DB::raw('CONCAT(s.name, \' \', s.lastname, \' \', s.lastname2) as NOMBRE'),
        'd.coddistribuidora as CODIGO',
        'd.name as RAZONSOCIAL'
      ];

      $header = [
        'DNI','NOMBRE','CODIGO','RAZONSOCIAL'
      ];

      //$concurso = Concurso::find($co);
      $concurso = DB::table('concursos')->where('id','=',$co)->first();

      if($concurso->volumen == 1){
        array_push($select,'c.volumen as VOLUMEN');
        array_push($header,'VOLUMEN');
      }
      if( !is_null($concurso->value_condition) ){
        array_push($select, 'c.condicion as '.strtoupper($concurso->key_condition));
        array_push($header,strtoupper($concurso->key_condition));
      }

      $cuotas = DB::table('cuotas as c')
        ->join('supervisores as s', 'c.supervisor_id','=','s.id')
        ->join('distribuidoras as d', 'c.distribuidor_id', '=', 'd.id')
        ->join('concursos as co', 'c.concurso_id', '=', 'co.id')
        ->join('ejecutivos as e', 'c.ejecutivo_id', '=', 'e.id')
        ->join('representantes as r', 'c.representante_id', '=', 'r.id')
        ->where('co.id','=',$co)
        ->whereIn('d.id', $ch)
        ->whereNull('co.deleted_at');

      $cuotas = $cuotas->select($select)->get();

      // Si piden el export
      if($request->submit == 'export'){
        $data = [];
        foreach($cuotas as $c){
          array_push($data,(array)$c);
        }
        DBExcel::DescargarCuotaEjecutivo($data);
      }

      $result = DB::table('concursos as c')
        ->select(
          'c.id as id',
          'c.name as titulo',
          'c.periodo as periodo'
        )
        ->whereNull('c.deleted_at')
        ->orderBy('c.f_inicio','desc')
        ->orderBy('c.name','asc')
        ->get();
      return view('cuotas.reporte_admin_post',['concursos'=>$result,'cuotas'=>$cuotas,'header'=>$header]);
    }
}
