<?php

namespace App\Http\Controllers\Trade;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use DB;
use App\Concurso as Concurso;
use Illuminate\Support\Facades\Auth;
use App\Cierre as Cierre;
use App\Http\Controllers\DBExcel as DBExcel;

class CierreController extends Controller
{
    public function getConfirmar(){
      $result = [];
      $concursos = Concurso::where('representante_id','=',Auth::user()->representante->id)->get();
      foreach($concursos as $concurso){
        $cierres = Cierre::where('concurso_id',$concurso->id)
          ->where('representante_id',Auth::user()->representante->id)
          ->whereNull('confirmed')
          ->whereNotNull('monto')->get();
        if(!$cierres->isEmpty()){
          array_push($result,$concurso);
        }
      }
      return view('cierres.confirmar',['concursos'=>$result]);
    }

    public function postConfirmar(Request $request){
      $co = $request->concursos;
      $ch = $request->checked;

      $select = [
        'ci.id as ID',
        's.dni as DNI',
        DB::raw('CONCAT(s.name, \' \', s.lastname, \' \', s.lastname2) as NOMBRE'),
        'd.coddistribuidora as CODIGO',
        'd.name as RAZONSOCIAL'
      ];

      $header = [
        '<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>','<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>','DNI','NOMBRE','CODIGO','RAZONSOCIAL'
      ];

      $concurso = Concurso::find($co);

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
        array_push($select,DB::raw('IF(ci.condicion >= c.condicion,\'CUMPLIO\',\'NO CUMPLIO\')'));
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
        ->whereNotNull('ci.monto')
        ->whereNull('ci.confirmed')
        ->whereIn('d.id', $ch);

        $cierres = $cierres->select($select)->get();

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
      return view('cierres.confirmar_cierre',['concursos'=>$result,'cierres'=>$cierres,'header'=>$header]);
    }

    public function postConfirmarProceso(Request $request){
      $cierres = $request->aprobado;
      $cierres = Cierre::whereIn('id', $cierres)->get();
      foreach($cierres as $ci){
        $ci->confirmed = 1;
        $ci->save();
      }
      $cierres = $request->desaprobado;
      $cierres = Cierre::whereIn('id', $cierres)->get();
      foreach($cierres as $ci){
        $ci->confirmed = 0;
        $ci->save();
      }
      $result = [];
      $concursos = Concurso::where('representante_id','=',Auth::user()->representante->id)->get();
      foreach($concursos as $concurso){
        $cierres = Cierre::where('concurso_id',$concurso->id)
          ->where('representante_id',Auth::user()->representante->id)
          ->whereNull('confirmed')
          ->whereNotNull('monto')->get();
        if(!$cierres->isEmpty()){
          array_push($result,$concurso);
        }
      }
      return view('cierres.confirmar',['concursos'=>$result]);
    }

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
      return view('cierres.reporte_representante',['concursos'=>$result]);
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
        array_push($select, 'c.condicion as '.strtoupper($concurso->key_condition));
        array_push($select, 'ci.condicion as '.strtoupper($concurso->key_condition).' VENDIDOS');
        array_push($select,DB::raw('CONCAT(ROUND((ci.condicion/c.condicion)*100,2),\'%\') as CONP'));
        array_push($header,strtoupper($concurso->key_condition));
        array_push($header,strtoupper($concurso->key_condition).' VENDIDOS');
        array_push($header,'CON.%');
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
        ->where('ci.representante_id','=',Auth::user()->representante->id)
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
          ->where('c.representante_id','=',Auth::user()->representante->id)
          ->whereNull('c.deleted_at')
          ->orderBy('c.f_inicio','desc')
          ->orderBy('c.name','asc')
          ->get();
      return view('cierres.reporte_representante_post',['concursos'=>$result,'cierres'=>$cierres,'header'=>$header]);
    }
}
