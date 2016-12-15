<?php

namespace App\Http\Controllers\Loyalty;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use DB;
use App\Concurso as Concurso;

class ConcursoController extends Controller
{
    public function getReporte(){
      $concursos = Concurso::all();
      return view('mantenimiento.concurso.index',['concursos'=>$concursos]);
    }

    public function list(){
      $result = DB::table('concursos as c')
        ->select(
          //'c.codconcurso as CODIGO',
          'c.name as NOMBRE',
          'c.periodo as PERIODO',
          'c.f_inicio as INICIO',
          'c.f_fin as FIN',
          DB::raw('CONCAT(\'<a href="'.URL('/concurso/download/').'/\',c.id,\'"><img src="'.asset('img/pdf.png').'" alt="Archivo de excel" class="img-thumbnail img-responsive"></a>\') as DOWNLOAD'),
          DB::raw('CONCAT(\'<a href="'.URL('/concurso/editar/\',c.id,\'').'" style="text-decoration:none;color: #e5101f;" class="id_distribuidora" data-id="\',c.id,\'"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>\') as EDITAR'),
          DB::raw('CONCAT(\'<a href="'.URL('/concurso/eliminar/\',c.id,\'').'" style="text-decoration:none;color: #e5101f;" class="id_distribuidora" data-id="\',c.id,\'"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>\') as ELIMINAR')
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

}
