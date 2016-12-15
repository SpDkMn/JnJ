<?php

namespace App\Http\Controllers\Ejecutivo;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests;
use DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AvanceCargaRequest as AvanceCargaRequest;
use App\Http\Controllers\DBExcel as DBExcel;
use App\Concurso as Concurso;
use App\Ejecutivo as Ejecutivo;
use App\Supervisor as Supervisor;
use App\Http\Requests\ReporteAvanceAdminRequest as ReporteAvanceAdminRequest;

class AvanceController extends Controller
{
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
        return view('avances.reporte',['concursos'=>$result]);
    }

    public function create(){
      date_default_timezone_set('America/Lima');
      $fecha_actual = date('Y-m-d');

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
        //->where('c.f_fin', '>=', $fecha_actual)
        ->whereNull('c.deleted_at')
        ->orderBy('c.f_inicio','desc')
        ->orderBy('c.name','asc')
        ->distinct()
        ->get();
        return view('avances.index',['concursos'=>$result]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->submit == 'formato'){
          $this->download_formato($request);
        }

        // Valida los campos necesarios para la carga
        $this->validate($request, [
          'concursos' => 'required|exists:concursos,id',
          'checked' => 'required',
          'archivoDeAvances' => 'required|mimes:xls,xlsx',
          'fechaDeInicio' => 'required|date_format:"d-m-Y"',
          'fechaDeFin' => 'required|date_format:"d-m-Y"',
        ]);

        // Busca el concurso
        $c = Concurso::find($request->concursos);

        // genera el nombre del nuevo archivo a guardar
        $string = 'archivoDeAvances';
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
        return DBExcel::cargarAvances($path,$name,$c,$codigos);
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
      DBExcel::cargarAvance2($path,$name,$c,$codigos);
      return redirect()->route('upload_avance')->with('status_data', 'El archivo fue cargado correctamente.');
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
      $sups = Supervisor::whereIn('distribuidor_id',$request->checked)->get();
      DBExcel::formatoAvance($sups,$dato);
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
          array_push($select,'c.volumen as CUOTA');
          array_push($select,'a.volumen as VENTA');
          array_push($select,DB::raw('CONCAT(ROUND((a.volumen/c.volumen)*100,2),\'%\') as VOLP'));
          array_push($header,'CUOTA');
          array_push($header,'VENTA');
          array_push($header,'ALCANCE');
        }
        if($concurso->cobertura == 1){
          array_push($select,'a.cobertura as PTOS COBERTURADOS');
          array_push($header,'COBERTURA');
        }
        if( !is_null($concurso->value_condition) ){
          array_push($select, 'c.condicion as '.strtoupper($concurso->key_condition));
          array_push($select, 'a.condicion as VENDIDOS');
          array_push($select,DB::raw('CONCAT(ROUND((a.condicion/c.condicion)*100,2),\'%\') as CONP'));
          array_push($header,strtoupper($concurso->key_condition));
          array_push($header,'VENDIDOS');
          array_push($header,'%');
        }

        $avances = DB::table('avances as a')
          ->join('supervisores as s', 'a.supervisor_id','=','s.id')
          ->join('distribuidoras as d', 'a.distribuidor_id', '=', 'd.id')
          ->join('cuotas as c','a.cuota_id','=','c.id')
          ->join('concursos as co', 'a.concurso_id', '=', 'co.id')
          ->join('ejecutivos as e', 'a.ejecutivo_id', '=', 'e.id')
          ->join('representantes as r', 'a.representante_id', '=', 'r.id')
          ->where('e.id','=',Auth::user()->ejecutivo->id)
          ->where('co.id','=',$co)
          ->whereIn('d.id', $ch)
          ->whereNull('co.deleted_at');

        $avances = $avances->select($select)->get();

        // Si piden el export
        if($request->submit == 'export'){
          $data = [];
          foreach($avances as $c){
            array_push($data,(array)$c);
          }
          DBExcel::DescargarAvancesEjecutivo($data);
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
        return view('avances.reporte_post',['concursos'=>$result,'avances'=>$avances,'header'=>$header]);
    }

}
