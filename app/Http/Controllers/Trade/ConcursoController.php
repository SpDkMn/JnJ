<?php

namespace App\Http\Controllers\Trade;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Concurso as Concurso;
use DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CargarConcursoRequest as CargarConcursoRequest;

class ConcursoController extends Controller
{
    /**
     * Pagina principal de concurso
     * Muestra los concursos
     * Carga concursos
     */
    public function index(){
        return view('concurso.index');
    }

    public function lista(){
      $result = DB::table('concursos as c')
        ->select(
          //'c.codconcurso as CODIGO',
          'c.periodo as PERIODO',
          'c.name as NOMBRE',
          'c.f_inicio as INICIO',
          'c.f_fin as FIN',
          DB::raw('CONCAT(\'<a href="'.URL('/concurso/download/').'/\',c.id,\'"><img src="'.asset('img/pdf.png').'" alt="Archivo de excel" class="img-thumbnail img-responsive"></a>\') as DOWNLOAD'),
          DB::raw('CONCAT(\'<a href="'.URL('/concurso/editar/\',c.id,\'').'" style="text-decoration:none;color: #e5101f;" class="id_distribuidora" data-id="\',c.id,\'"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>\') as EDITAR'),
          DB::raw('CONCAT(\'<a href="'.URL('/concurso/eliminar/\',c.id,\'').'" style="text-decoration:none;color: #e5101f;" class="id_distribuidora" data-id="\',c.id,\'"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>\') as ELIMINAR')
        )
        ->where('c.representante_id','=',Auth::user()->representante->id)
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CargarConcursoRequest $request){
      $file = $request->file('archivoDeConcurso');
      $name = str_replace(' ','_',$request->nombreDelConcurso).'.'.$file->guessExtension();

      $c = new Concurso;
      $c->name = $request->nombreDelConcurso;
      $c->namefile = $name;//$file->getClientOriginalName();
      //$c->codconcurso = $request->codigoDeConcurso;
      $c->periodo = $request->periodo;
      $c->representante_id = Auth::user()->representante->id;
      $c->f_inicio = date('Y-m-d',strtotime($request->fechaDeInicio));
      $c->f_fin = date('Y-m-d',strtotime($request->fechaDeFin));
      $c->volumen = 1;
      if(!is_null($request->condicion)){
        $c->key_condition = $request->producto;
        $c->value_condition = $request->cantidad;
      }
      if(!is_null($request->cobertura)){
        $c->cobertura = 1;
      }else{
        $c->cobertura = 0;
      }
      $c->save();

      $path = self::PATH_FILE.'concursos';
      // Guarda el archivo en la ruta establecida
      $file->move($path, $c->namefile);

      // El archivo subio bien
      return redirect()->back()->with('status_data', 'El archivo fue cargado correctamente.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
        $c = Concurso::find($id);
        return view('concurso.edit',['concurso'=>$c]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
      $c = Concurso::find($id);
      $c->name = $request->nombreDelConcurso;
      //$c->codconcurso = $request->codigoDeConcurso;
      $c->periodo = $request->periodo;
      $c->f_inicio = date('Y-m-d',strtotime($request->fechaDeInicio));
      $c->f_fin = date('Y-m-d',strtotime($request->fechaDeFin));
      $c->volumen = 1;
      if(!is_null($request->condicion)){
        $c->key_condition = $request->producto;
        $c->value_condition = $request->cantidad;
      }
      if(!is_null($request->cobertura)){
        $c->cobertura = 1;
      }else{
        $c->cobertura = 0;
      }
      if(!is_null($request->file('archivoDeConcurso'))){
        $file = $request->file('archivoDeConcurso');
        $name = str_replace(' ','_',$request->nombreDelConcurso).'.'.$file->guessExtension();
        $c->namefile = $name;//$file->getClientOriginalName();
        $path = self::PATH_FILE.'concursos';
        // Guarda el archivo en la ruta establecida
        $file->move($path, $c->namefile);
      }
      $c->save();

      // El archivo subio bien
      if(Auth::user()->username == 'LOYALTYP'){
        return redirect()->route('reporte_concursos_admin')->with('status_data', 'El concurso fue editado correctamente.');
      }
      return redirect()->route('concurso_view')->with('status_data', 'El concurso fue editado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        $c = Concurso::find($id);
        $c->delete();
        return redirect()->route('concurso_view');
    }

    public function download($id){
      $concurso = Concurso::find($id);
      return response()->download(self::PATH_FILE.'concursos/'.$concurso->namefile);
    }
}
