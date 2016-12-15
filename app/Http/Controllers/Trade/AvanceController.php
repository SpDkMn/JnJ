<?php

namespace App\Http\Controllers\Trade;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DBExcel as DBExcel;
use App\Concurso as Concurso;

class AvanceController extends Controller
{
    public function getReporte(){
      $result = DB::table('concursos as c')
        ->select(
          'c.id as id',
          'c.name as titulo',
          'c.periodo as periodo'
        )
        ->where('c.representante_id','=',Auth::user()->representante->id)
        ->whereNull('c.deleted_at')
        ->orderBy('c.f_inicio','desc')
        ->orderBy('c.name','asc')
        ->get();
      return view('avances.reporte_representante',['concursos'=>$result]);
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

      $concurso = Concurso::find($co);

      if($concurso->volumen == 1){
        array_push($select,'c.volumen as VOLO');
        array_push($select,'a.volumen as VOLA');
        array_push($select,DB::raw('CONCAT(ROUND((a.volumen/c.volumen)*100,2),\'%\') as VOLP'));
        array_push($header,'VOL.O');
        array_push($header,'VOL.A');
        array_push($header,'VOL.%');
      }
      if($concurso->cobertura == 1){
        array_push($select,'a.cobertura as COBA');
        array_push($header,'COB.A');
      }
      if( !is_null($concurso->value_condition) ){
        array_push($select, 'c.condicion as '.strtoupper($concurso->key_condition));
        array_push($select, 'a.condicion as CONA');
        array_push($select,DB::raw('CONCAT(ROUND((a.condicion/c.condicion)*100,2),\'%\') as CONP'));
        array_push($header,strtoupper($concurso->key_condition));
        array_push($header,'CON.A');
        array_push($header,'CON.%');
      }

      $avances = DB::table('avances as a')
        ->join('supervisores as s', 'a.supervisor_id','=','s.id')
        ->join('distribuidoras as d', 'a.distribuidor_id', '=', 'd.id')
        ->join('cuotas as c','a.cuota_id','=','c.id')
        ->join('concursos as co', 'a.concurso_id', '=', 'co.id')
        ->join('ejecutivos as e', 'a.ejecutivo_id', '=', 'e.id')
        ->join('representantes as r', 'a.representante_id', '=', 'r.id')
        ->where('a.representante_id','=',Auth::user()->representante->id)
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
        DBExcel::DescargarAvancesEjecutivo($data);
      }

      $result = DB::table('concursos as c')
        ->select(
          'c.id as id',
          'c.name as titulo',
          'c.periodo as periodo'
        )
        ->where('c.representante_id','=',Auth::user()->representante->id)
        ->whereNull('c.deleted_at')
        ->orderBy('c.f_inicio','desc')
        ->orderBy('c.name','asc')
        ->get();
      return view('avances.reporte_representante_post',['concursos'=>$result,'avances'=>$avances,'header'=>$header]);
    }
}
