<?php

namespace App\Http\Controllers\Trade;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use DB;
use Illuminate\Support\Facades\Auth;
use App\Concurso as Concurso;
use App\Http\Controllers\DBExcel as DBExcel;

class CuotaController extends Controller
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
      return view('cuotas.reporte_representante',['concursos'=>$result]);
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
        ->where('c.representante_id','=',Auth::user()->representante->id)
        ->whereNull('c.deleted_at')
        ->orderBy('c.f_inicio','desc')
        ->orderBy('c.name','asc')
        ->get();
      return view('cuotas.reporte_representante_post',['concursos'=>$result,'cuotas'=>$cuotas,'header'=>$header]);
    }
}
