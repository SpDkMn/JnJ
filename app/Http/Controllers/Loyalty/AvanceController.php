<?php

namespace App\Http\Controllers\Loyalty;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use DB;
use App\Concurso as Concurso;
use App\Http\Controllers\DBExcel as DBExcel;
use App\Http\Requests\ReporteAvanceAdminRequest as ReporteAvanceAdminRequest;

class AvanceController extends Controller
{
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
      return view('avances.reporte_admin',['concursos'=>$result]);
    }

    public function postReporte(ReporteAvanceAdminRequest $request){
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

      $concurso = Concurso::find($co);

      if($concurso->volumen == 1){
        array_push($select,'c.volumen as CUOTA');
        array_push($select,'a.volumen as VENTA');
        array_push($select,DB::raw('CONCAT(ROUND((a.volumen/c.volumen)*100,2),\'%\') as ALCANCE'));
        array_push($header,'CUOTA');
        array_push($header,'VENTA');
        array_push($header,'%');
      }
      if($concurso->cobertura == 1){
        array_push($select,'a.cobertura as PTS.COBERTURA');
        //array_push($select,DB::raw('CONCAT(ROUND((a.cobertura/c.cobertura)*100,2),\'%\') as COBP'));
        array_push($header,'PTS.COB.');
        //array_push($header,'COB.%');
      }
      if( !is_null($concurso->value_condition) ){
        if($request->submit == 'export'){
          array_push($select, 'c.condicion as '.strtoupper($concurso->key_condition));
          array_push($select, 'a.condicion as CONA');
        }
        array_push($select,DB::raw('CONCAT(ROUND((a.condicion/c.condicion)*100,2),\'%\') as CONDICION'));

        array_push($header,'CONDICION');
      }

      $avances = DB::table('avances as a')
        ->join('supervisores as s', 'a.supervisor_id','=','s.id')
        ->join('distribuidoras as d', 'a.distribuidor_id', '=', 'd.id')
        ->join('cuotas as c','a.cuota_id','=','c.id')
        ->join('concursos as co', 'a.concurso_id', '=', 'co.id')
        ->join('ejecutivos as e', 'a.ejecutivo_id', '=', 'e.id')
        ->join('representantes as r', 'a.representante_id', '=', 'r.id')
        ->where('co.id','=',$co)
        ->whereIn('d.id', $ch)
        ->whereNull('co.deleted_at');

      $avances = $avances->select($select)->get();

      // Si piden el export
      if($request->submit == 'export'){
        $data = [];
        foreach($avances as $c){
          array_push($data,(array)$c);
        }
        if(count($data)!=0){
          DBExcel::DescargarAvancesEjecutivo($data);
        }
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
      return view('avances.reporte_admin_post',['concursos'=>$result,'avances'=>$avances,'header'=>$header]);
    }
}
