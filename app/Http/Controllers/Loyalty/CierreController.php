<?php

namespace App\Http\Controllers\Loyalty;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use DB;
use App\Concurso as Concurso;
use App\Http\Controllers\DBExcel as DBExcel;

class CierreController extends Controller
{
    public function getProcesar(){
      $concurso = DB::table('concursos as c')
        ->select('c.id')
        ->join('cierres as ci', 'c.id', '=', 'ci.concurso_id')
        ->where('ci.monto',null)
        ->whereNull('c.deleted_at')
        ->orderBy('c.f_inicio','desc')
        ->orderBy('c.name','asc')
        ->distinct()
        ->get();
      $idCon=[];
      foreach($concurso as $i){
        array_push($idCon,$i->id);
      }
      $concursos = Concurso::whereIn('id',$idCon)->get();
      return view('cierres.loyalty',['concursos'=>$concursos]);
    }

    public function postProcesar(Request $request){
      // Si piden el formato
      if($request->submit == 'descargar'){
        $this->download_cierre($request);
      }

      // Valida los campos necesarios para la carga
      $this->validate($request, [
        'concursos' => 'required|exists:concursos,id',
        'archivoDeCierre' =>  'required|mimes:xls,xlsx',
      ]);

      // Busca el concurso
      $c = Concurso::find($request->concursos);

      // genera el nombre del nuevo archivo a guardar
      $string = 'archivoDeCierre';
      $name = date('d-m-Y-hisu').'.'.$request->file($string)->guessExtension();

      // Guarda el archivo en la ruta establecida
      $path = self::PATH_FILE.'tmp';
      $request->file($string)->move($path, $name);

      DBExcel::cargarMonto($path,$name,$c);
      return redirect()->route('procesar_cierre_view')->with('status_data', 'El archivo fue cargado correctamente.');
    }

    public function getReporte(){
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
      return view('cierres.reporte_admin',['concursos'=>$result]);
    }

    public function postReporte(Request $request){
      $co = $request->concursos;
      $ch = $request->checked;

      $select = [
        's.dni as DNI',
        DB::raw('CONCAT(s.name, \' \', s.lastname, \' \', s.lastname2) as NOMBRE'),
        'r.codCanal as CANAL',
        'd.coddistribuidora as CODIGO',
        'd.name as RAZONSOCIAL'
      ];

      $header = [
        'DNI','NOMBRE','CANAL','CODIGO','RAZONSOCIAL'
      ];

      $concurso = Concurso::find($co);
      if($request->submit == 'export'){
        array_push($select,'co.name as CONCURSO');
        array_push($header,'CONCURSO');
      }

      if($concurso->volumen == 1){
        array_push($select,'c.volumen as CUOTA');
        array_push($select,'ci.volumen as VENTA');
        array_push($select,DB::raw('CONCAT(ROUND((ci.volumen/c.volumen)*100,2),\'%\') as ALCANCE'));
        array_push($header,'CUOTA');
        array_push($header,'VENTA');
        array_push($header,'ALCANCE');
      }
      if($concurso->cobertura == 1){
        array_push($select,'ci.cobertura as COBERTURA');
        array_push($header,'COBERTURA');
      }
      if( !is_null($concurso->value_condition) ){
        if($request->submit == 'export'){
          array_push($select, 'co.value_condition as '.strtoupper($concurso->key_condition));
          array_push($select, 'ci.condicion as '.strtoupper($concurso->key_condition).'.VENDIDOS');
        }
        array_push($select,DB::raw('CONCAT(ROUND((ci.condicion/co.value_condition)*100,2),\'%\') as CONDICION'));
        array_push($header,'CONDICION');
      }

      array_push($select, 'ci.monto as MONTO');
      array_push($header,'MONTO');

      $cierres = DB::table('cierres as ci')
        ->join('supervisores as s', 'ci.supervisor_id','=','s.id')
        ->join('distribuidoras as d', 'ci.distribuidor_id', '=', 'd.id')
        ->join('cuotas as c','ci.cuota_id','=','c.id')
        ->join('concursos as co', 'ci.concurso_id', '=', 'co.id')
        ->join('ejecutivos as e', 'ci.ejecutivo_id', '=', 'e.id')
        ->join('representantes as r', 'ci.representante_id', '=', 'r.id')
        ->where('co.id','=',$co)
        ->where('ci.confirmed','=','1')
        ->whereIn('d.id', $ch)
        ->whereNull('co.deleted_at');

        $cierres = $cierres->select($select)->get();

        // Si piden el export
        if($request->submit == 'export'){
          $data = [];
          foreach($cierres as $c){
            array_push($data,(array)$c);
          }
          DBExcel::DescargarCierresEjecutivo($data);
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
      return view('cierres.reporte_admin_post',['concursos'=>$result,'cierres'=>$cierres,'header'=>$header]);
    }
}
