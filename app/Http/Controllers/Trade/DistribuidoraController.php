<?php

namespace App\Http\Controllers\Trade;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Representante as Representante;
use DB;
use App\Concurso as Concurso;
use Illuminate\Support\Facades\Auth;

class DistribuidoraController extends Controller
{
    public function getReporte(){
        return view('reporte.distribuidora.index');
    }

    public function lista(){
      $r = Representante::where('user_id','=',Auth::user()->id)->first();
      $result = DB::table('distribuidoras as d')
        ->select(
          'd.coddistribuidora as CODIGO',
          'd.zona as ZONA',
          'd.name as RAZONSOCIAL',
          'd.phone as TELEFONO',
          'd.email as CORREO',
          'j.codejecutivo as EJECUTIVO',
          //DB::raw('CONCAT(\'<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>\') as EDITAR'),
          DB::raw('CONCAT(\'<a href="#" style="text-decoration:none;color: #e5101f;" class="id_distribuidora" data-id="\',d.id,\'"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>\') as ELIMINAR')
        )
        ->join('ejecutivos as j', 'd.ejecutivo_id', '=', 'j.id')
        ->where('d.representante_id','=',$r->id)
        ->get();
      $array = array();
      foreach($result as $a){
        array_push($array,array_values(get_object_vars($a)));
      }
      $array = ["data"=>$array];
      return $array;
    }

    public function distribuidora_lista($id){
      $c = Concurso::find($id);
      $r = Auth::user()->representante;
      $result = DB::table('distribuidoras as d')
        ->select(
          DB::raw('CONCAT(\'<input type="checkbox" class="checkbox" name="checked[]" value="\',d.id,\'" checked="">\') as CHEKCBOX'),
          'd.coddistribuidora as CODIGO',
          'd.name as RAZONSOCIAL',
          DB::raw('CONCAT(\'<input type="file" id="distribuidora-\',d.id,\'" name="archivosDeSustento[]" required>\') as ARCHIVOS'),
          DB::raw('(SELECT SUM(c.monto) FROM cierres as c WHERE c.monto IS NOT NULL AND c.confirmed IS NULL AND c.distribuidor_id = d.id) AS MONTO')
        )
        ->where('d.representante_id','=',$r->id)
        ->get();
      return $result;
    }
}
