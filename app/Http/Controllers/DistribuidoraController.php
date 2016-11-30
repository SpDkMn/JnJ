<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Distribuidora as Distribuidora;
use App\Representante as Representante;
use App\Concurso as Concurso;
use App\Cuota as Cuota;
use App\Cierre as Cierre;
use App\Supervisor as Supervisor;
use DB;
use App\Http\Requests\DistribuidoraCargaRequest as DistribuidoraCargaRequest;
use App\Http\Controllers\DBExcel as DBExcel;
use Illuminate\Support\Facades\Auth;

class DistribuidoraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('mantenimiento.distribuidora.index');
    }

    public function reporte(){
        return view('reporte.distribuidora.index');
    }

    public function list(){
        $result = DB::table('distribuidoras as d')
        ->select(
          'd.coddistribuidora as CODIGO',
          'd.zona as ZONA',
          'd.name as RAZONSOCIAL',
          'r.codcanal as CANAL',
          DB::raw('CONCAT(u.name,\' \',u.lastname) as EJECUTIVO')
          //DB::raw('CONCAT(\'<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>\') as EDITAR'),
          //DB::raw('CONCAT(\'<a href="#" style="text-decoration:none;color: #e5101f;" class="id_distribuidora" data-id="\',d.id,\'"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>\') as ELIMINAR')
        )
        ->join('representantes as r', 'd.representante_id', '=', 'r.id')
        ->join('ejecutivos as j', 'd.ejecutivo_id', '=', 'j.id')
        ->join('users as u','u.id','=','j.user_id')
        ->get();
      $array = array();
      foreach($result as $a){
        array_push($array,array_values(get_object_vars($a)));
      }
      $array = ["data"=>$array];
      return $array;
    }

    public function list_personalizada(){
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

    public function distribuidoras_concursos_list_new($id){
      $c = Concurso::find($id);
      $r = $c->representante;
      $e = Auth::user()->ejecutivo;
      $distribuidoras = Distribuidora::where('representante_id',$c->representante_id)
        ->where('ejecutivo_id',$e->id)
        ->get();
      $result = [];
      foreach($distribuidoras as $distribuidora){
        $array['CHEKCBOX'] = "<input type='checkbox' class='checkbox' name='checked[]' value='$distribuidora->id' checked=''>";
        $array['ESTADO'] = "COMPLETADO";
        $array['CODIGO'] = $distribuidora->coddistribuidora;
        $array['CANAL'] = $distribuidora->representante->codcanal;
        $array['RAZONSOCIAL'] = $distribuidora->name;
        $array['ARCHIVOS'] = "<input type='file' disabled>";
        array_push($result,$array);
      }
      return $result;
    }

    public function distribuidoras_concursos_list($id){
      $c = Concurso::find($id);
      $r = $c->representante;
      $e = Auth::user()->ejecutivo;
      $distribuidoras = Distribuidora::where('representante_id',$c->representante_id)
        ->where('ejecutivo_id',$e->id)
        ->get();
      $faltantes = [];
      $pendientes = [];
      $completado = [];
      foreach($distribuidoras as $distribuidora){
        $cierres = Cierre::where('distribuidor_id',$distribuidora->id)
          ->where('concurso_id',$c->id)
          ->where('ejecutivo_id',$e->id)
          ->where('representante_id',$r->id)
          ->get();
        if($cierres->isEmpty()){
          array_push($faltantes,$distribuidora);
        }else{
          $supervisoresd = Supervisor::where('distribuidor_id',$distribuidora->id)->get();
          $supervisoresc = [];
          foreach($cierres as $cierre){
            array_push($supervisoresc,$cierre->supervisor_id);
          }
          $supervisoresc = Supervisor::whereIn('id',$supervisoresc)->get();
          $diff = $supervisoresd->diff($supervisoresc);
          if($diff->isEmpty()){
            array_push($completado,$distribuidora);
          }else{
            array_push($pendientes,$distribuidora);
          }
        }
      }
      //dd($faltantes,$pendientes,$completado);
      $result = [];
      foreach($faltantes as $f){
        $array['CHEKCBOX'] = "<input type='checkbox' class='checkbox' name='checked[]' value='$f->id' checked=''>";
        $array['ESTADO'] = "FALTANTE";
        $array['CODIGO'] = $f->coddistribuidora;
        $array['CANAL'] = $f->representante->codcanal;
        $array['RAZONSOCIAL'] = $f->name;
        $array['ARCHIVOS'] = "<input type='file' id='distribuidora-$f->id' name='archivosDeSustento[]' required>";
        array_push($result,$array);
      }
      foreach($pendientes as $f){
        $array['CHEKCBOX'] = "<input type='checkbox' class='checkbox' name='checked[]' value='$f->id' checked=''>";
        $array['ESTADO'] = "PENDIENTE";
        $array['CODIGO'] = $f->coddistribuidora;
        $array['CANAL'] = $f->representante->codcanal;
        $array['RAZONSOCIAL'] = $f->name;
        $array['ARCHIVOS'] = "<input type='file' id='distribuidora-$f->id' name='archivosDeSustento[]' required>";
        array_push($result,$array);
      }
      foreach($completado as $f){
        $array['CHEKCBOX'] = "<input type='checkbox' class='checkbox' disabled>";
        $array['ESTADO'] = "COMPLETADO";
        $array['CODIGO'] = $f->coddistribuidora;
        $array['CANAL'] = $f->representante->codcanal;
        $array['RAZONSOCIAL'] = $f->name;
        $array['ARCHIVOS'] = "<input type='file' disabled>";
        array_push($result,$array);
      }
      return $result;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DistribuidoraCargaRequest $request)
    {
          // genera el nombre del nuevo archivo a guardar
          $string = 'cargaDeDistribuidoras';
          $name = date('d-m-Y-hisu').'.'.$request->file($string)->guessExtension();

          // Guarda el archivo en la ruta establecida
          $path = self::PATH_FILE.'tmp';
          $request->file($string)->move($path, $name);

          return DBExcel::cargarDistribuidora($path,$name);
    }

    public function storeF(Request $request)
    {
      // Valida los campos necesarios para la carga
      $this->validate($request, [
        'submit' => 'required',
      ]);

      // guarda el nombre del nuevo a buscar
      $name = $request->submit;

      // Ruta del archivo a buscar
      $path = self::PATH_FILE.'tmp';

      // Carga los datos
      DBExcel::cargarDistribuidora2($path,$name);
      return redirect()->route('mantenimiento_distribuidora_view')->with('status_data', 'El archivo fue cargado correctamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function distribuidoras_concursos_list_1($id){
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

    public function distribuidoras_concursos_list_1_admin($id){
      //$c = Concurso::find($id);
      $c = DB::table("concursos")->where('id','=',$id)->first();
      $result = DB::table('distribuidoras as d')
        ->select(
          DB::raw('CONCAT(\'<input type="checkbox" class="checkbox" name="checked[]" value="\',d.id,\'" checked="">\') as CHEKCBOX'),
          'd.coddistribuidora as CODIGO',
          'd.name as RAZONSOCIAL'
        )
        ->where('representante_id','=',$c->representante_id)
        ->get();
      return $result;
    }
}
