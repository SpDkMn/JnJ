<?php

namespace App\Http\Controllers\Ejecutivo;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests;
use App\Http\Requests\CargarConcursoRequest as CargarConcursoRequest;
use App\Concurso as Concurso;
use DB;
use Illuminate\Support\Facades\Auth;

class ConcursoController extends Controller
{
    public function view(){
        return view('concurso.view');
    }

    public function view_distribuidoras($id){
        return view('concurso.view_distribuidoras',['id'=>$id]);
    }

    public function list_view(){
      $result = DB::table('concursos as c')
        ->select(
          'c.name as NOMBRE',
          'r.codcanal as CANAL',
          'c.periodo as PERIODO',
          'c.f_inicio as INICIO',
          'c.f_fin as FIN',
          DB::raw('CONCAT(\'<a href="'.URL('concursos/distribuidoras/').'/\',c.id,\'" style="text-decoration:none;color: #e5101f;" class="id_distribuidora"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>\') as VER'),
          DB::raw('CONCAT(\'<a href="'.URL('download/concurso/').'/\',c.id,\'"><img src="'.asset('img/pdf.png').'" alt="Archivo de excel" class="img-thumbnail img-responsive"></a>\') as DOWNLOAD')
        )
        ->join('representantes as r', 'c.representante_id', '=', 'r.id')
        ->join('distribuidoras as d', 'd.representante_id', '=', 'r.id')
        ->where('d.ejecutivo_id','=',Auth::user()->ejecutivo->id)
        ->whereNull('c.deleted_at')
        ->orderBy('c.f_inicio','desc')
        ->orderBy('c.name','asc')
        ->distinct()
        ->get();
      $array = array();
      foreach($result as $a){
        array_push($array,array_values(get_object_vars($a)));
      }
      $array = ["data"=>$array];
      return $array;
    }

    public function list_distribuidoras($id){
      $c = Concurso::find($id);
      $r = $c->representante;
      $e = Auth::user()->ejecutivo;
      $result = DB::table('distribuidoras as d')
        ->select(
          'd.coddistribuidora as CODIGO',
          'd.zona as ZONA',
          'd.name as RAZONSOCIAL',
          'd.phone as TELEFONO',
          'd.email as CORREO'
        )
        ->where('d.representante_id','=',$r->id)
        ->where('d.ejecutivo_id','=',$e->id)
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
    public function create(){
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        return Concurso::find($id);
    }
}
