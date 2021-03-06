<?php

namespace App\Http\Controllers\Ejecutivo;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests;
use DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CierreCargaRequest as CierreCargaRequest;
use App\Http\Controllers\DBExcel as DBExcel;
use App\Concurso as Concurso;
use App\Cierre as Cierre;
use App\Supervisor as Supervisor;

class CierreController extends Controller
{
    public static function download_cierre(Request $request){
      $co = $request->concursos;
      $select = [
        's.dni as DNI',
        DB::raw('CONCAT(s.name, \' \', s.lastname, \' \', s.lastname2) as NOMBRE'),
        'r.codCanal as CANAL',
        'd.coddistribuidora as CODIGO',
        'd.name as RAZONSOCIAL'
      ];
      $header = ['DNI','NOMBRE','CANAL','CODIGO','RAZONSOCIAL'];

      $concurso = Concurso::find($co);
      array_push($select,'co.name as CONCURSO');
      array_push($header,'CONCURSO');

      if($concurso->volumen == 1){
        array_push($select,'c.volumen as CUOTA');
        array_push($header,'CUOTA');
        array_push($select,'ci.volumen as VENTA');
        array_push($header,'VENTA');
        array_push($select,DB::raw('CONCAT(ROUND((ci.volumen/c.volumen)*100,2),\'%\') as ALCANCE'));
        array_push($header,'ALCANCE');
      }
      if($concurso->cobertura == 1){
        array_push($select,'ci.cobertura as COBERTURA');
        array_push($header,'COBERTURA');
      }
      if( !is_null($concurso->value_condition) ){
        array_push($select, 'co.value_condition as '.strtoupper($concurso->key_condition));
        array_push($header,strtoupper($concurso->key_condition));
        array_push($select, 'ci.condicion as '.strtoupper($concurso->key_condition).'.VENDIDOS');
        array_push($header, strtoupper($concurso->key_condition).'.VENDIDOS');
        array_push($select, DB::raw('CONCAT(ROUND((ci.condicion/co.value_condition)*100,2),\'%\') as CONDICION'));
        array_push($header,'CONDICION');
      }
      array_push($header,'MONTO');

      $cierres = DB::table('cierres as ci')
        ->join('supervisores as s', 'ci.supervisor_id','=','s.id')
        ->join('distribuidoras as d', 'ci.distribuidor_id', '=', 'd.id')
        ->join('cuotas as c','ci.cuota_id','=','c.id')
        ->join('concursos as co', 'ci.concurso_id', '=', 'co.id')
        ->join('ejecutivos as e', 'ci.ejecutivo_id', '=', 'e.id')
        ->join('representantes as r', 'ci.representante_id', '=', 'r.id')
        ->where('co.id','=',$co)
        ->whereNull('ci.monto')
        ->whereNull('co.deleted_at');

      $cierres = $cierres->select($select)->get();
      /*dd($cierres);
      $cierres = Cierre::where('concurso_id',$request->concursos)
      ->where('monto',null)->get();*/
      DBExcel::DescargarCierres($cierres,$header);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function create(){
      date_default_timezone_set('America/Lima');
      $fecha_actual = date('Y-m-d');
      $fecha_limite = strtotime ( '-30 day' , strtotime ( $fecha_actual ) ) ;
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
        ->where('c.f_fin', '<=', $fecha_actual)
        //->where('c.f_fin', '>=', $fecha_limite)
        ->whereNull('c.deleted_at')
        ->orderBy('c.f_inicio','desc')
        ->orderBy('c.name','asc')
        ->distinct()
        ->get();
      return view('cierres.index',['concursos'=>$result]);
    }

    public static function download_formato(Request $request){
        $c = Concurso::find($request->concursos);
        $dato = [];
        if($c->volumen == 1){$dato['volumen'] = 1;
        }else{$dato['volumen'] = 0;}
        if($c->cobertura == 1){$dato['cobertura'] = 1;
        }else{$dato['cobertura'] = 0;}
        if( !is_null($c->value_condition) ){$dato['condicion'] = $c->value_condition;
        }else{$dato['condicion'] = '0';}
        $sups = Supervisor::whereIn('distribuidor_id',$request->checked)
          ->orderBy('lastname','asc')
          ->orderBy('lastname2','asc')
          ->orderBy('name','asc')
          ->get();
        DBExcel::formatoCierre($sups,$dato);
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
          'archivoDeCierre' =>  'required|mimes:xls,xlsx',
        ]);

        // Busca el concurso
        $c = Concurso::find($request->concursos);

        // genera el nombre del nuevo archivo a guardar
        $string = 'archivoDeCierre';
        $name = date('d-m-Y-hisu').'.'.$request->file($string)->guessExtension();

        // Guarda el archivo en la ruta establecida
        $path = self::PATH_FILE.'tmp';
        $request->file($string)->move($path, $name);

        // Codigo de las distribuidoras elegidas
        $codigos = DB::table('distribuidoras as d')
          ->select('*')
          ->whereIn('d.id', $request->checked)
          ->get();

        $nombreSustentos = [];
        $sustentos = $request->file('archivosDeSustento');
        $paths = self::PATH_FILE.'sustento';
        foreach($sustentos as $s){
          $s->move($paths,$s->getClientOriginalName());
          array_push($nombreSustentos,$s->getClientOriginalName());
        }

        // Carga los datos y los muestra en la web
        return DBExcel::cargarCierre($path,$name,$c,$codigos,$nombreSustentos);
        //DBExcel::cargarCierre($path,$name,$c->codconcurso);
        //return redirect()->back()->with('status_data', 'El archivo fue cargado correctamente.');
    }

    public function storeF(Request $request){
      // Valida los campos necesarios para la carga
      $this->validate($request, [
        'codconcurso' => 'required|exists:concursos,id',
        'checked' => 'required',
        'submit' => 'required',
        'sustento' => 'required',
      ]);

      // Busca el concurso
      $c = Concurso::find($request->codconcurso);

      // guarda el nombre del nuevo a buscar
      $name = $request->submit;

      // Ruta del archivo a buscar
      $path = self::PATH_FILE;

      // Codigo de las distribuidoras elegidas
      $codigos = $request->checked;

      // Carga los datos
      DBExcel::cargarCierre2($path,$name,$c,$codigos,$request->sustento);
      return redirect()->route('upload_cierre')->with('status_data', 'El archivo fue cargado correctamente.');
    }

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
          ->whereNull('c.deleted_at')
          ->orderBy('c.f_inicio','desc')
          ->orderBy('c.name','asc')
          ->distinct()
          ->get();
        return view('cierres.reporte',['concursos'=>$result]);
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
        array_push($select, 'ci.condicion as VENDIDOS');
        array_push($select,DB::raw('CONCAT(ROUND((ci.condicion/c.condicion)*100,2),\'%\') as CONP'));
        array_push($header,strtoupper($concurso->key_condition));
        array_push($header,' VENDIDOS');
        array_push($header,'%');
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
          ->join('representantes as r', 'c.representante_id', '=', 'r.id')
          ->join('distribuidoras as d', 'd.representante_id', '=', 'r.id')
          ->where('d.ejecutivo_id','=',Auth::user()->ejecutivo->id)
          ->whereNull('c.deleted_at')
          ->orderBy('c.f_inicio','desc')
          ->orderBy('c.name','asc')
          ->distinct()
          ->get();
        return view('cierres.reporte_post',['concursos'=>$result,'cierres'=>$cierres,'header'=>$header]);
    }
}
