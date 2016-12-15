<?php

namespace App\Http\Controllers\Loyalty;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use DB;
use App\Http\Controllers\DBExcel as DBExcel;
use App\Http\Requests\DistribuidoraCargaRequest as DistribuidoraCargaRequest;

class DistribuidoraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        return view('mantenimiento.distribuidora.index');
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(DistribuidoraCargaRequest $request){
          // genera el nombre del nuevo archivo a guardar
          $string = 'cargaDeDistribuidoras';
          $name = date('d-m-Y-hisu').'.'.$request->file($string)->guessExtension();

          // Guarda el archivo en la ruta establecida
          $path = self::PATH_FILE.'tmp';
          $request->file($string)->move($path, $name);

          return DBExcel::cargarDistribuidora($path,$name);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
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
    public function show($id){
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        //
    }

    public function distribuidoras_list($id){
      $c = DB::table("concursos")->where('id','=',$id)->first();
      $result = DB::table('distribuidoras as d')
        ->select(DB::raw('CONCAT(\'<input type="checkbox" class="checkbox" name="checked[]" value="\',d.id,\'" checked="">\') as CHEKCBOX'),
            'd.coddistribuidora as CODIGO',
            'd.name as RAZONSOCIAL')
        ->where('representante_id','=',$c->representante_id)
        ->get();
      return $result;
    }
}
