<?php

namespace App\Http\Controllers\Loyalty;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use DB;
use App\Http\Requests\EjecutivoCargaRequest as EjecutivoCargaRequest;
use App\Http\Controllers\DBExcel as DBExcel;

class EjecutivoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        return view('mantenimiento.ejecutivo.index');
    }

    public function list(){
      $result = DB::table('ejecutivos as j')
        ->select(
          'u.dni as DNI',
          'j.codejecutivo as CODIGO',
          'u.lastname as APELLIDO',
          'u.name as NOMBRE',
          'u.email as CORREO',
          //DB::raw('CONCAT(\'<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>\') as EDITAR'),
          DB::raw('CONCAT(\'<a href="#" style="text-decoration:none;color: #e5101f;" class="id_ejecutivo" data-id="\',j.id,\'"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>\') as ELIMINAR')
        )
        ->join('users as u', 'j.user_id', '=', 'u.id')
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
    public function create(EjecutivoCargaRequest $request){
      // genera el nombre del nuevo archivo a guardar
      $string = 'cargaDeEjecutivos';
      $name = date('d-m-Y-hisu').'.'.$request->file($string)->guessExtension();

      // Guarda el archivo en la ruta establecida
      $path = self::PATH_FILE.'tmp';
      $request->file($string)->move($path, $name);

      return DBExcel::cargarEjecutivo($path,$name);

      //return redirect()->back()->with('status_data', 'El archivo fue cargado correctamente.');
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
      DBExcel::cargarEjecutivo2($path,$name);

      return redirect()->route('mantenimiento_ejecutivo_view')->with('status_data', 'El archivo fue cargado correctamente.');
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
}
