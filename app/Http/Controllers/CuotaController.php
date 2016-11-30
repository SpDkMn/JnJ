<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CuotaCargaRequest as CuotaCargaRequest;
use App\Http\Controllers\DBExcel as DBExcel;
use App\Concurso as Concurso;
use App\Distribuidora as Distribuidora;
use App\Supervisor as Supervisor;

class CuotaController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function index()
     {
         return view('mantenimiento.cuota.index');
     }

     public function list(){
       $result = DB::table('concursos as c')
         ->select(
           'c.name as NOMBRE',
           'c.codconcurso as CODIGO',
           'c.periodo as PERIODO'
         )
         ->get();
       $array = array();
       foreach($result as $a){
         array_push($array,array_values(get_object_vars($a)));
       }
       $array = ["data"=>$array];
       return $array;
     }

    public function reporte(){
        $result = DB::table('concursos as c')
          ->select(
            'c.id as id',
            'c.name as titulo',
            'c.periodo as periodo'
          )
          ->join('representantes as r', 'c.representante_id', '=', 'r.id')
          ->join('distribuidoras as d', 'd.representante_id', '=', 'r.id')
          ->where('d.ejecutivo_id','=',Auth::user()->ejecutivo->id)
          ->distinct()
          ->get();
        return view('cuotas.reporte',['concursos'=>$result]);
    }

    /**
     *
     */
    public function create(){
      date_default_timezone_set('America/Lima');
      $fecha_actual = date('Y-m-d');
      $fecha_limite = strtotime ( '-15 day' , strtotime ( $fecha_actual ) ) ;
      $fecha_limite = date ( 'Y-m-d' , $fecha_limite );

      $result = DB::table('concursos as c')
        ->select(
          'c.id as id',
          'c.name as titulo',
          'c.periodo as periodo'
        )
        ->join('representantes as r', 'c.representante_id', '=', 'r.id')
        ->join('distribuidoras as d', 'd.representante_id', '=', 'r.id')
        ->where('d.ejecutivo_id','=',Auth::user()->ejecutivo->id)
        ->where('c.f_inicio', '<=', $fecha_actual)
        ->where('c.f_inicio', '>=', $fecha_limite)
        ->distinct()
        ->get();
        return view('cuotas.index',['concursos'=>$result]);
    }

    public static function download_formato(Request $request){
      $c = Concurso::find($request->concursos);
      $dato = [];
      if($c->volumen == 1){$dato['volumen'] = 1;}
      else{$dato['volumen'] = 0;}
      if( !is_null($c->value_condition) ){$dato['condicion'] = $c->value_condition;
      }else{$dato['condicion'] = '0';}
      $sups = Supervisor::whereIn('distribuidor_id',$request->checked)->orderBy('cargo', 'desc')->get();
      DBExcel::formatoCuota($sups,$dato);
    }

    public function store(Request $request){
        // Si piden el formato
        if($request->submit == 'formato'){
          $this->download_formato($request);
        }

        // Valida los campos necesarios para la carga
        $this->validate($request, [
          'concursos' => 'required|exists:concursos,id',
          'checked' => 'required',
          'archivoDeCuota' => 'required|mimes:xls,xlsx',
        ]);

        // Busca el concurso
        $c = Concurso::find($request->concursos);

        // genera el nombre del nuevo archivo a guardar
        $string = 'archivoDeCuota';
        $name = date('d-m-Y-hisu').'.'.$request->file($string)->guessExtension();

        // Guarda el archivo en la ruta establecida
        $path = self::PATH_FILE.'tmp';
        $request->file($string)->move($path, $name);

        // Codigo de las distribuidoras elegidas
        $codigos = DB::table('distribuidoras as d')
          ->select('*')
          ->whereIn('d.id', $request->checked)
          ->get();

        // Carga los datos y los muestra en la web
        return DBExcel::cargarCuota($path,$name,$c,$codigos);
        //return redirect()->back()->with('status_data', 'El archivo fue cargado correctamente.');
    }

    public function storeF(Request $request){
      // Valida los campos necesarios para la carga
      $this->validate($request, [
        'codconcurso' => 'required|exists:concursos,id',
        'checked' => 'required',
        'submit' => 'required',
      ]);

      // Busca el concurso
      $c = Concurso::find($request->codconcurso);

      // guarda el nombre del nuevo a buscar
      $name = $request->submit;

      // Ruta del archivo a buscar
      $path = self::PATH_FILE.'tmp';

      // Codigo de las distribuidoras elegidas
      $codigos = $request->checked;

      // Carga los datos
      DBExcel::cargarCuota2($path,$name,$c,$codigos);
      return redirect()->route('upload_cuota')->with('status_data', 'El archivo fue cargado correctamente.');
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

    public function reporte_view(Request $request){
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
          array_push($header,'CUOTA');
        }/*
        if($concurso->cobertura == 1){
          array_push($select,'co.cobertura as COBERTURA');
          array_push($header,'COBERTURA');
        }*/
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
          ->where('e.id','=',Auth::user()->ejecutivo->id)
          ->where('co.id','=',$co)
          ->whereIn('d.id', $ch);

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
          ->join('representantes as r', 'c.representante_id', '=', 'r.id')
          ->join('distribuidoras as d', 'd.representante_id', '=', 'r.id')
          ->where('d.ejecutivo_id','=',Auth::user()->ejecutivo->id)
          ->distinct()
          ->get();
        return view('cuotas.reporte_1',['concursos'=>$result,'cuotas'=>$cuotas,'header'=>$header]);
    }

    public function reporte_representante(){
      $result = DB::table('concursos as c')
        ->select(
          'c.id as id',
          'c.name as titulo',
          'c.periodo as periodo'
        )
        ->where('c.representante_id','=',Auth::user()->representante->id)
        ->get();
      return view('cuotas.reporte_representante',['concursos'=>$result]);
    }

    public function reporte_representante_admin(){
      $result = DB::table('concursos as c')
        ->select(
          'c.id as id',
          'c.name as titulo',
          'c.periodo as periodo'
        )
        ->get();
      return view('cuotas.reporte_representante_admin',['concursos'=>$result]);
    }

    public function reporte_view_representante(Request $request){
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
        ->whereIn('d.id', $ch);

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
        ->get();
      return view('cuotas.reporte_1_representante',['concursos'=>$result,'cuotas'=>$cuotas,'header'=>$header]);
    }
    public function reporte_view_admin(Request $request){
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

      //$concurso = Concurso::find($co);
      $concurso = DB::table('concursos')->where('id','=',$co)->first();

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
        ->whereIn('d.id', $ch);

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
        ->get();
      return view('cuotas.reporte_1_admin',['concursos'=>$result,'cuotas'=>$cuotas,'header'=>$header]);
    }
}
