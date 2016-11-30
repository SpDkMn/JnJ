<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Supervisor as Supervisor;
use DB;
use App\Http\Requests\SupervisoresCargaRequest as SupervisoresCargaRequest;
use App\Http\Controllers\DBExcel as DBExcel;

class SupervisorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('mantenimiento.supervisor.index');
    }

    public static function list(){
        $result = DB::table('supervisores as s')
            ->select(
              DB::raw('CONCAT(s.lastname,\' \',s.lastname2) as APELLIDO'),
              's.name as NOMBRE',
              's.dni as DNI',
              'd.name as DISTRIBUIDORA',
              's.cargo as CARGO',
              's.phone as TELEFONO',
              's.cel_phone as CELULAR',
              's.email as CORREO',
              //DB::raw('CONCAT(\'<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>\') as EDITAR'),
              DB::raw('CONCAT(\'<a href="#" style="text-decoration:none;color: #e5101f;" class="id_supervisor" data-id="\',s.id,\'"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>\') as ELIMINAR')
            )
            ->join('distribuidoras as d', 's.distribuidor_id', '=', 'd.id')
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
    public function store(SupervisoresCargaRequest $request)
    {
        // genera el nombre del nuevo archivo a guardar
        $string = 'cargaDeSupervisor';
        $name = date('d-m-Y-hisu').'.'.$request->file($string)->guessExtension();

        // Guarda el archivo en la ruta establecida
        $path = self::PATH_FILE.'tmp';
        $request->file($string)->move($path, $name);

        return DBExcel::cargarSupervisores($path,$name);
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
      DBExcel::cargarSupervisores($path,$name);

      return redirect()->route('mantenimiento_vendedor_view')->with('status_data', 'El archivo fue cargado correctamente.');
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
}
