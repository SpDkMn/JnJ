<?php

namespace App\Http\Controllers;

use Excel;
use App\Avance as Avance;
use App\User as user;
use App\Representante as Representante;
use App\Ejecutivo as Ejecutivo;
use App\Distribuidora as Distribuidora;
use App\Supervisor as Supervisor;
use App\StagingArea as StagingArea;
use App\Cuota as Cuota;
use App\Concurso as Concurso;
use App\Error as Error;
use App\Cierre as Cierre;
use App\Sustento as Sustento;
use Illuminate\Support\Facades\Auth;


class DBExcel{
  /** */
  public static function DescargarEjecutivo(){
    Excel::create('Ejecutivos', function($excel) {
      $excel->sheet('Ejecutivos', function($sheet) {
          $sheet->fromArray([
            'ID','Nombre','Apellido',
            'DNI','correo','usuarios'],null, 'A1', false,false);
      });
    })->export('xlsx');
  }
  /** */

  public static function DescargarCuotaEjecutivo($data){
    $header = array_keys($data[0]);
    $dato = [];
    array_push($dato,$header);
    foreach($data as $d){
      array_push($dato,$d);
    }
    Excel::create('Cuotas', function($excel) use($dato) {
      $excel->sheet('Cuotas', function($sheet) use($dato) {
          $sheet->fromArray($dato,null, 'A1', false,false);
      });
    })->export('xlsx');
  }

  public static function DescargarAvancesEjecutivo($data){
    $header = array_keys($data[0]);
    $dato = [];
    array_push($dato,$header);
    foreach($data as $d){
      array_push($dato,$d);
    }
    Excel::create('Avance', function($excel) use($dato) {
      $excel->sheet('Avance', function($sheet) use($dato) {
          $sheet->fromArray($dato,null, 'A1', false,false);
      });
    })->export('xlsx');
  }

  public static function DescargarCierresEjecutivo($data){
    $header = array_keys($data[0]);
    $dato = [];
    array_push($dato,$header);
    foreach($data as $d){
      array_push($dato,$d);
    }
    Excel::create('Cierre', function($excel) use($dato) {
      $excel->sheet('Cierre', function($sheet) use($dato) {
          $sheet->fromArray($dato,null, 'A1', false,false);
      });
    })->export('xlsx');
  }

  public static function DescargarCierres($cierres,$header){
    $dato = [];
    array_push($dato,$header);
    foreach($cierres as $c){
      $aux = [];
      foreach($header as $h){
        if($h != 'MONTO'){
          array_push($aux,$c->$h);
        }
      }
      array_push($dato,$aux);
    }
    Excel::create('Cierre', function($excel) use($dato) {
      $excel->sheet('Cierre', function($sheet) use($dato) {
          $sheet->fromArray($dato,null, 'A1', false,false);
      });
    })->export('xlsx');
      Excel::create('Cierres', function($excel) use($cierres) {
        $excel->sheet('Cierres', function($sheet) use($cierres) {
            $sheet->fromModel($cierres,null, 'A1', false,true);
        });
      })->export('xlsx');
  }

  public static function cargarMonto($path,$name,$c){
    $result = Excel::load($path.'/'.$name, function($reader){})->get();
      foreach($result as $data){
        $ci = Cierre::where('supervisor_id','=',Supervisor::where('dni','=',$data->dni)->first()->id)->first();
        $ci->monto = $data->monto;
        $ci->save();
      }
  }

  public static function formatoCuota($sups,$dato){
    Excel::create('FormatoCuota', function($excel) use($sups,$dato) {
      $header = ['nro documento','cargo','nombre','apellido paterno','apellido materno','cod distribuidor','distribuidora'];
      if($dato['condicion'] != 0){
        array_push($header,'condicion');
      }
      if($dato['volumen'] == 1){
        array_push($header,'Cuota');
      }
      $data = [
        $header
      ];

      if($dato['condicion'] != 0){
        foreach($sups as $s){
          $d = Distribuidora::find($s->distribuidor_id);
          array_push($data,[$s->dni,$s->cargo,$s->name,$s->lastname,$s->lastname2,$d->coddistribuidora,$d->name,$dato['condicion']]);
        }
      }else{
        foreach($sups as $s){
          $d = Distribuidora::find($s->distribuidor_id);
          array_push($data,[$s->dni,$s->cargo,$s->name,$s->lastname,$s->lastname2,$d->coddistribuidora,$d->name]);
        }
      }

      $excel->sheet('Cuota', function($sheet) use($data) {
          $sheet->fromArray($data,null, 'A1', false,false);
      });
    })->export('xlsx');
  }

  public static function formatoAvance($sups,$dato){
    Excel::create('FormatoAvance', function($excel) use($sups,$dato) {
      $header = ['nro documento','nombre','apellido paterno','apellido materno','cod distribuidor','distribuidora'];
      if($dato['condicion'] != 0){
        array_push($header,'condicion');
      }
      if($dato['volumen'] == 1){
        array_push($header,'ventas');
      }
      if($dato['cobertura'] == 1){
        array_push($header,'ptos coberturados');
      }
      $data = [
        $header
      ];
      foreach($sups as $s){
        $d = Distribuidora::find($s->distribuidor_id);
        array_push($data,[$s->dni,$s->name,$s->lastname,$s->lastname2,$d->coddistribuidora,$d->name]);
      }

      $excel->sheet('Avance', function($sheet) use($data) {
          $sheet->fromArray($data,null, 'A1', false,false);
      });

    })->export('xlsx');
  }

  public static function formatoCierre($sups,$dato){
    Excel::create('FormatoCierre', function($excel) use($sups,$dato) {
      $header = ['nro documento','nombre','apellido paterno','apellido materno','cod distribuidor','distribuidora'];
      if($dato['condicion'] != 0){
        array_push($header,'condicion');
      }
      if($dato['volumen'] == 1){
        array_push($header,'ventas');
      }
      if($dato['cobertura'] == 1){
        array_push($header,'ptos coberturados');
      }
      array_push($header,'confirmación');
      $data = [
        $header
      ];
      foreach($sups as $s){
        $d = Distribuidora::find($s->distribuidor_id);
        array_push($data,[$s->dni,$s->name,$s->lastname,$s->lastname2,$d->coddistribuidora,$d->name]);
      }
      $excel->sheet('Cierre', function($sheet) use($data) {
          $sheet->fromArray($data,null, 'A1', false,false);
      });
    })->export('xlsx');
  }

  public static function cargarRepresentante($path,$name){
    $result = Excel::load($path.'/'.$name, function($reader){})->get();

    // Variables para separar los datos correctos de los incorrectos
    $errores = [];
    $correctos = [];

    foreach($result as $data){
      if(
        !empty($data->dni) &&
        !empty($data->canal) &&
        !empty($data->usuarios) &&
        empty(User::where('dni','=',$data->dni)->first()) &&
        empty(User::where('email','=',$data->correo)->first()) &&
        empty(User::where('username','=',$data->usuarios)->first())
      ){
        $dato['error'] = "";
        foreach($data as $k => $v){
          $dato[$k]=$v;
        }
        array_push($correctos,$dato);
        $a = $correctos[0];
      }
      else{
        if(empty($data->dni))
          $dato['error'] = "FALTA DNI";
        if(empty($data->canal))
          $dato['error'] = "FALTA EL CODIGO DE CANAL";
        if(empty($data->usuarios))
          $dato['error'] = "FALTA EL NOMBRE DE USUARIO";
        if(!empty(User::where('dni','=',$data->dni)->first()))
          $dato['error'] = "DNI DE REPRESENTANTE YA EXISTE";
        if(!empty(User::where('email','=',$data->correo)->first()))
          $dato['error'] = "CORREO DE REPRESENTANTE YA EXISTE";
        if(!empty(User::where('username','=',$data->usuarios)->first()))
          $dato['error'] = "NOMBRE DE USUARIO YA EXISTE";
        foreach($data as $k => $v){
          $dato[$k]=$v;
        }
        array_push($errores,$dato);
        $a = $errores[0];
      }
    }
    $header = [];
    foreach($a as $key => $value){
      array_push($header,$key);
    }
    return view('mantenimiento.representante.confirmar',['header'=>$header,'errores'=>$errores,'correctos'=>$correctos,'file'=>$name]);
  }

  public static function cargarRepresentante2($path,$name){
    $result = Excel::load($path.'/'.$name, function($reader){})->get();

    foreach($result as $data){
      if(
        !empty($data->dni) &&
        !empty($data->canal) &&
        !empty($data->usuarios) &&
        empty(User::where('dni','=',$data->dni)->first()) &&
        empty(User::where('email','=',$data->correo)->first()) &&
        empty(User::where('username','=',$data->usuarios)->first())
      ){
        $u = User::create([
          'name' => (string)$data->nombre,
          'lastname' => (string)$data->apellido,
          'dni' => (string)$data->dni,
          'username' => (string)$data->usuarios,
          'email' => (string)$data->email,
          'password' => (string)bcrypt($data->dni),
          'profile_id'=>'2']);

        $r = Representante::create([
          'codrepresentante' => (string)$data->codigo,
          'codcanal'=> (string)$data->canal,
          'user_id'=>$u->id
        ]);
      }
    }
  }

/**     */
  public static function cargarEjecutivo($path,$name){
    $result = Excel::load($path.'/'.$name, function($reader){})->get();

    // Variables para separar los datos correctos de los incorrectos
    $errores = [];
    $correctos = [];

    foreach($result as $data){
      if(
        !empty($data->dni) &&
        empty(User::where('dni','=',$data->dni)->first()) &&
        empty(User::where('email','=',$data->correo)->first()) &&
        empty(User::where('username','=',$data->usuarios)->first()) &&
        empty(Ejecutivo::where('codejecutivo','=',$data->id)->first())
      ){
        $dato['error'] = "";
        foreach($data as $k => $v){
          $dato[$k]=$v;
        }
        array_push($correctos,$dato);
        $a = $correctos[0];
      }
      else{
        if(empty($data->dni))
          $dato['error'] = "FALTA DNI";
        if(!empty(User::where('dni','=',$data->dni)->first()))
          $dato['error'] = "DNI DE EJECUTIVO YA EXISTE";
        if(!empty(User::where('email','=',$data->correo)->first()))
          $dato['error'] = "CORREO DE EJECUTIVO YA EXISTE";
        if(!empty(User::where('username','=',$data->usuarios)->first()))
          $dato['error'] = "NOMBRE DE USUARIO YA EXISTE";
        if(!empty(Ejecutivo::where('codejecutivo','=',$data->id)->first()))
          $dato['error'] = "CODIGO DE EJECUTIVO YA EXISTE";
        foreach($data as $k => $v){
          $dato[$k]=$v;
        }
        array_push($errores,$dato);
        $a = $errores[0];
      }
    }
    $header = [];
    foreach($a as $key => $value){
      array_push($header,$key);
    }
    return view('mantenimiento.ejecutivo.confirmar',['header'=>$header,'errores'=>$errores,'correctos'=>$correctos,'file'=>$name]);
  }

  public static function cargarEjecutivo2($path,$name){
    $result = Excel::load($path.'/'.$name, function($reader){})->get();
    foreach($result as $data){
      if(
        !empty($data->dni) &&
        empty(User::where('dni','=',$data->dni)->first()) &&
        empty(User::where('email','=',$data->correo)->first()) &&
        empty(User::where('username','=',$data->usuarios)->first()) &&
        empty(Ejecutivo::where('codejecutivo','=',$data->id)->first())
      ){
        $u = User::create([
          'name' => (string)$data->nombre,
          'lastname' => (string)$data->apellido,
          'dni' => (string)$data->dni,
          'username' => (string)$data->usuarios,
          'email' => (string)$data->correo,
          'password' => (string)bcrypt($data->dni),
          'profile_id'=>'1']);

        $r = Ejecutivo::create([
          'codejecutivo' => (string)$data->id,
          'user_id'=>$u->id
        ]);
      }
    }
  }

  public static function cargarDistribuidora($path,$name){
    $result = Excel::load($path.'/'.$name, function($reader){})->get();

    // Variables para separar los datos correctos de los incorrectos
    $errores = [];
    $correctos = [];

    foreach($result as $data){
      if(
        !empty($data->id_representante) &&
        !is_null(Ejecutivo::where('codejecutivo','=',(string)$data->id_representante)->first()) &&
        !empty($data->canal) &&
        !is_null(Representante::where('codcanal','=',(string)$data->canal)->first()) &&
        empty(Distribuidora::where('coddistribuidora','=',$data->codigo)->first())
      ){

        $dato['error'] = "";
        foreach($data as $k => $v){
          $dato[$k]=$v;
        }
        array_push($correctos,$dato);
        $a = $correctos[0];
      }else{
        if(empty($data->id_representante))
          $dato['error'] = "FALTA ID REPRESENTANTE";
        if(is_null(Ejecutivo::where('codejecutivo','=',(string)$data->id_representante)->first()))
          $dato['error'] = "REPRESENTANTE NO EXISTE";
        if(empty($data->canal))
          $dato['error'] = "FALTA CANAL";
        if(is_null(Representante::where('codcanal','=',(string)$data->canal)->first()))
          $dato['error'] = "CANAL NO EXISTE";
        if(!empty(Distribuidora::where('coddistribuidora','=',$data->codigo)->first()))
          $dato['error'] = "CODIGO DE DISTRIBUIDORA REGISTRADA";
        foreach($data as $k => $v){
          $dato[$k]=$v;
        }
        array_push($errores,$dato);
        $a = $errores[0];
      }
    }
    $header = [];
    foreach($a as $key => $value){
      array_push($header,$key);
    }
    return view('mantenimiento.distribuidora.confirmar',['header'=>$header,'errores'=>$errores,'correctos'=>$correctos,'file'=>$name]);
  }

  public static function cargarDistribuidora2($path,$name){
    $result = Excel::load($path.'/'.$name, function($reader){})->get();
    foreach($result as $data){
      if(!empty($data->id_representante) && !empty($data->canal)){
        // Representante
        $r = Representante::where('codcanal','=',(string)$data->canal)->first();

        // Ejecutivo
        $j = Ejecutivo::where('codejecutivo','=',(string)$data->id_representante)->first();

        // Distribuidora
        if(!is_null($r) && !is_null($j)){
          $r = Distribuidora::create([
            'name' => (string)$data->clientes,
            'address'=>(string)$data->direccion,
            'reference' => (string)$data->referencia,
            'zona' => (string)$data->zona,
            'phone'=>(string)$data->telefono,
            'email' => (string)$data->correo,
            'coddistribuidora'=>(string)$data->codigo,
            'representante_id' => $r->id,
            'ejecutivo_id'=>$j->id,
          ]);
        }
      }
    }
  }

  public static function cargarSupervisores($path,$name){
    $result = Excel::load($path.'/'.$name, function($reader){})->get();

    // Variables para separar los datos correctos de los incorrectos
    $errores = [];
    $correctos = [];

    foreach($result as $data){
      if(
        !empty($data->dni) &&
        empty(Supervisor::where('dni','=',$data->dni)->first()) &&
        !empty($data->cod_distribuidora) &&
        !empty(Distribuidora::where('coddistribuidora','=',(int)$data->cod_distribuidora)->first())
      ){
        $dato['error'] = "";
        foreach($data as $k => $v){
          $dato[$k]=$v;
        }
        array_push($correctos,$dato);
        $a = $correctos[0];
      }else{
        if(empty($data->dni))
          $dato['error'] = "FALTA DNI";
        if(!empty(Supervisor::where('dni','=',$data->dni)->first()))
          $dato['error'] = "DNI YA REGISTRADO";
        if(empty($data->cod_distribuidora))
          $dato['error'] = "FALTA CODIGO DE LA DISTRIBUIDORA";
        if(empty(Distribuidora::where('coddistribuidora','=',(int)$data->cod_distribuidora)->first()))
          $dato['error'] = "DISTRIBUIDORA NO REGISTRADA";
        foreach($data as $k => $v){
          $dato[$k]=$v;
        }
        array_push($errores,$dato);
          $a = $errores[0];
      }
    }
    $header = [];
    foreach($a as $key => $value){
      array_push($header,$key);
    }
    return view('mantenimiento.supervisor.confirmar',['header'=>$header,'errores'=>$errores,'correctos'=>$correctos,'file'=>$name]);
  }

  public static function cargarSupervisores2($path,$name){
    $result = Excel::load($path.'/'.$name, function($reader){})->get();
    foreach($result as $data){
      if(
        !empty($data->dni) &&
        empty(Supervisor::where('dni','=',$data->dni)->first()) &&
        !empty($data->cod_distribuidora) &&
        !empty(Distribuidora::where('coddistribuidora','=',(int)$data->cod_distribuidora)->first())
      ){
        $d = Distribuidora::where('coddistribuidora','=',(int)$data->cod_distribuidora)->first();
        $r = Supervisor::create([
          'distribuidor_id' => $d->id,
          'dni' => (string)$data->dni,
          'name' => (string)$data->nombres,
          'lastname' => (string)$data->apellido_paterno,
          'lastname2'=>(string)$data->apellido_materno,
          'address'=>(string)$data->direccion,
          'distrito' => (string)$data->distrito,
          'provincia'=> (string)$data->provincia,
          'departamento' => (string)$data->departamento,
          'cel_phone' => (string)$data->celular,
          'phone'=>(string)$data->telefono_fijo,
          'email' => (string)$data->correo_elec,
          'cargo'=>(string)$data->cargo,
        ]);
      }
    }
  }
/**     */
  public static function cargarCuota($path,$name,$concurso,$distribuidoras){
    // Obteniendo los datos del ejecutivo
    $ejecutivo = Auth::user()->ejecutivo;

    // Obteniendo los datos del excel
    $result = Excel::load($path.'/'.$name, function($reader){})->get();

    // Variables para separar los datos correctos de los incorrectos
    $errores = [];
    $correctos = [];

    // Arreglo de codigos de distribuidoras
    $coddistribuidora = [];
    foreach($distribuidoras as $d){
      array_push($coddistribuidora,$d->coddistribuidora);
    }
    foreach($result as $data){
      if(
        empty($data->nro_documento) ||
        empty(Supervisor::where('dni','=',(string)$data->nro_documento)->first()) ||
        empty($data->cod_distribuidor) ||
        empty(Distribuidora::where('coddistribuidora','=',(string)$data->cod_distribuidor)->first()) ||
        !in_array($data->cod_distribuidor,$coddistribuidora) ||
        !empty(Cuota::where('concurso_id',$concurso->id)
          ->where('ejecutivo_id',$ejecutivo->id)
          ->where('supervisor_id',Supervisor::where('dni','=',(string)$data->nro_documento)->first()->id)
          ->first()) ||
        empty($data->cuota)
      ){
        $dato = [];
        if(empty($data->nro_documento)){
          $dato['error'] = "FALTA DNI";
        }elseif(empty(Supervisor::where('dni','=',(string)$data->nro_documento)->first())){
          $dato['error'] = "DNI NO REGISTRADO";
        }elseif(empty($data->cod_distribuidor)){
          $dato['error'] = "FALTA COD. DISTRIBUIDORA";
        }elseif(empty(Distribuidora::where('coddistribuidora','=',(string)$data->cod_distribuidor)->first())){
          $dato['error'] = "COD. DISTRIBUIDORA NO REGISTRADO";
        }elseif(!in_array($data->cod_distribuidor,$coddistribuidora)){
          $dato['error'] = "COD. DISTRIBUIDORA NO VÁLIDO";
        }elseif(!empty(Cuota::where('concurso_id',$concurso->id)
          ->where('ejecutivo_id',$ejecutivo->id)
          ->where('supervisor_id',Supervisor::where('dni','=',(string)$data->nro_documento)->first()->id)
          ->first())){
            $dato['error'] = "YA REGISTRA CUOTA";
          } elseif(empty($data->cuota)){
            $dato['error'] = "FALTA CUOTA";
          }
        foreach($data as $k => $v){
          $dato[$k]=$v;
        }
        array_push($errores,$dato);
        $a = $errores[0];
      }else{
        $dato['error'] = "";
        foreach($data as $k => $v){
          $dato[$k]=$v;
        }
        array_push($correctos,$dato);
        $a = $correctos[0];
      }
    }
    $header = [];
    foreach($a as $key => $value){
      array_push($header,$key);
    }
    return view('cuotas.confirmar',['header'=>$header,'errores'=>$errores,'correctos'=>$correctos,'file'=>$name,'codconcurso' => $concurso->id,'coddistribuidora'=>$coddistribuidora]);
  }

  public static function cargarCuota2($path,$name,$concurso,$coddistribuidoras){
    // Obteniendo los datos del ejecutivo
    $ejecutivo = Auth::user()->ejecutivo;

    // Obteniendo los datos del excel
    $result = Excel::load($path.'/'.$name, function($reader){})->get();

    foreach($result as $data){
      if(
        empty($data->nro_documento) ||
        empty(Supervisor::where('dni','=',(string)$data->nro_documento)->first()) ||
        empty($data->cod_distribuidor) ||
        empty(Distribuidora::where('coddistribuidora','=',(string)$data->cod_distribuidor)->first()) ||
        !in_array($data->cod_distribuidor,$coddistribuidoras) ||
        !empty(Cuota::where('concurso_id',$concurso->id)
          ->where('ejecutivo_id',$ejecutivo->id)
          ->where('supervisor_id',Supervisor::where('dni','=',(string)$data->nro_documento)->first()->id)
          ->first()) ||
        empty($data->cuota)
      ){

      }else{
        $c = new Cuota;
        $c->volumen = (string)$data->cuota;
        $c->condicion = $concurso->value_condition;

        $d = Distribuidora::where('coddistribuidora','=',(string)$data->cod_distribuidor)->first();
        $s = Supervisor::where('dni','=',(string)$data->nro_documento)->first();

        $c->supervisor_id = $s->id;
        $c->distribuidor_id = $d->id;
        $c->concurso_id = $concurso->id;
        $c->ejecutivo_id = $ejecutivo->id;
        $c->representante_id = $d->representante_id;

        $c->save();
      }
    }
  }

  public static function cargarAvances($path,$name,$concurso,$codigos){
      // Obteniendo los datos del ejecutivo
      $ejecutivo = Auth::user()->ejecutivo;

      // Obteniendo los datos del excel
      $result = Excel::load($path.'/'.$name, function($reader){})->get();

      // Variables para separar los datos correctos de los incorrectos
      $errores = [];
      $correctos = [];

      // Arreglo de codigos de distribuidoras
      $coddistribuidora = [];
      foreach($codigos as $d){
        array_push($coddistribuidora,$d->coddistribuidora);
      }

      foreach($result as $data){
        if(
          empty($data->nro_documento) ||
          empty(Supervisor::where('dni','=',(string)$data->nro_documento)->first()) ||
          empty($data->cod_distribuidor) ||
          empty(Distribuidora::where('coddistribuidora','=',(string)$data->cod_distribuidor)->first()) ||
          !in_array($data->cod_distribuidor,$coddistribuidora) ||
          empty(Cuota::where('concurso_id',$concurso->id)
            ->where('ejecutivo_id',$ejecutivo->id)
            ->where('supervisor_id',Supervisor::where('dni','=',(string)$data->nro_documento)->first()->id)
            ->first())
        ){
          $dato = [];
          if(empty($data->nro_documento)){
            $dato['error'] = "FALTA DNI";
          }elseif(empty(Supervisor::where('dni','=',(string)$data->nro_documento)->first())){
            $dato['error'] = "DNI NO REGISTRADO";
          }elseif(empty($data->cod_distribuidor)){
            $dato['error'] = "FALTA COD. DISTRIBUIDORA";
          }elseif(empty(Distribuidora::where('coddistribuidora','=',(string)$data->cod_distribuidor)->first())){
            $dato['error'] = "COD. DISTRIBUIDORA NO REGISTRADO";
          }elseif(!in_array($data->cod_distribuidor,$coddistribuidora)){
            $dato['error'] = "COD. DISTRIBUIDORA NO VÁLIDO";
          }elseif(empty(Cuota::where('concurso_id',$concurso->id)
            ->where('ejecutivo_id',$ejecutivo->id)
            ->where('supervisor_id',Supervisor::where('dni','=',(string)$data->nro_documento)->first()->id)
            ->first())){
              $dato['error'] = "NO REGISTRA CUOTA";
            }
          foreach($data as $k => $v){
            $dato[$k]=$v;
          }
          array_push($errores,$dato);
          $a = $errores[0];
        }else{
          $dato['error'] = "";
          foreach($data as $k => $v){
            $dato[$k]=$v;
          }
          array_push($correctos,$dato);
          $a = $correctos[0];
        }
      }
      $header = [];
      foreach($a as $key => $value){
        array_push($header,$key);
      }
      return view('avances.confirmar',['header'=>$header,'errores'=>$errores,'correctos'=>$correctos,'file'=>$name,'codconcurso' => $concurso->id,'coddistribuidora'=>$coddistribuidora]);
  }

  public static function cargarAvance2($path,$name,$concurso,$coddistribuidoras){
    // Obteniendo los datos del ejecutivo
    $ejecutivo = Auth::user()->ejecutivo;

    // Obteniendo los datos del excel
    $result = Excel::load($path.'/'.$name, function($reader){})->get();

    foreach($result as $data){
      if(
        empty($data->nro_documento) ||
        empty(Supervisor::where('dni','=',(string)$data->nro_documento)->first()) ||
        empty($data->cod_distribuidor) ||
        empty(Distribuidora::where('coddistribuidora','=',(string)$data->cod_distribuidor)->first()) ||
        !in_array($data->cod_distribuidor,$coddistribuidoras) ||
        empty(Cuota::where('concurso_id',$concurso->id)
          ->where('ejecutivo_id',$ejecutivo->id)
          ->where('supervisor_id',Supervisor::where('dni','=',(string)$data->nro_documento)->first()->id)
          ->first())
      ){

      }else{
        $a = new Avance;
        $a->volumen = (string)$data->ventas;
        $a->cobertura = (string)$data->ptos_coberturados;
        $a->condicion = $concurso->value_condition;

        $d = Distribuidora::where('coddistribuidora','=',(string)$data->cod_distribuidor)->first();
        $s = Supervisor::where('dni','=',(string)$data->nro_documento)->first();

        $a->supervisor_id = $s->id;
        $a->distribuidor_id = $d->id;
        $a->concurso_id = $concurso->id;
        $a->ejecutivo_id = $ejecutivo->id;
        $a->representante_id = $d->representante_id;
        $a->cuota_id = Cuota::where('concurso_id',$concurso->id)
          ->where('ejecutivo_id',$ejecutivo->id)
          ->where('supervisor_id',Supervisor::where('dni','=',(string)$data->nro_documento)->first()->id)
          ->first()->id;

        $a->save();
      }
    }
  }

  public static function cargarCierre($path,$name,$concurso,$codigos,$nombreSustentos){
    // Obteniendo los datos del ejecutivo
    $ejecutivo = Auth::user()->ejecutivo;

    // Obteniendo los datos del excel
    $result = Excel::load($path.'/'.$name, function($reader){})->get();

    // Variables para separar los datos correctos de los incorrectos
    $errores = [];
    $correctos = [];

    // Arreglo de codigos de distribuidoras
    $coddistribuidora = [];
    foreach($codigos as $d){
      array_push($coddistribuidora,$d->coddistribuidora);
    }

    foreach($result as $data){
      if(
        empty($data->nro_documento) ||
        empty(Supervisor::where('dni','=',(string)$data->nro_documento)->first()) ||
        empty($data->cod_distribuidor) ||
        empty(Distribuidora::where('coddistribuidora','=',(string)$data->cod_distribuidor)->first()) ||
        !in_array($data->cod_distribuidor,$coddistribuidora) ||
        empty(Cuota::where('concurso_id',$concurso->id)
          ->where('ejecutivo_id',$ejecutivo->id)
          ->where('supervisor_id',Supervisor::where('dni','=',(string)$data->nro_documento)->first()->id)
          ->first())
      ){
        $dato = [];
        if(empty($data->nro_documento)){
          $dato['error'] = "FALTA DNI";
        }elseif(empty(Supervisor::where('dni','=',(string)$data->nro_documento)->first())){
          $dato['error'] = "DNI NO REGISTRADO";
        }elseif(empty($data->cod_distribuidor)){
          $dato['error'] = "FALTA COD. DISTRIBUIDORA";
        }elseif(empty(Distribuidora::where('coddistribuidora','=',(string)$data->cod_distribuidor)->first())){
          $dato['error'] = "COD. DISTRIBUIDORA NO REGISTRADO";
        }elseif(!in_array($data->cod_distribuidor,$coddistribuidora)){
          $dato['error'] = "COD. DISTRIBUIDORA NO VÁLIDO";
        }elseif(empty(Cuota::where('concurso_id',$concurso->id)
          ->where('ejecutivo_id',$ejecutivo->id)
          ->where('supervisor_id',Supervisor::where('dni','=',(string)$data->nro_documento)->first()->id)
          ->first())){
            $dato['error'] = "NO REGISTRA CUOTA";
          }
        foreach($data as $k => $v){
          $dato[$k]=$v;
        }
        array_push($errores,$dato);
        $a = $errores[0];
      }else{
        $dato['error'] = "";
        foreach($data as $k => $v){
          $dato[$k]=$v;
        }
        array_push($correctos,$dato);
        $a = $correctos[0];
      }
    }

    $header = [];
    foreach($a as $key => $value){
      array_push($header,$key);
    }
    return view('cierres.confirmarCarga',
      ['header'=>$header,'errores'=>$errores,'correctos'=>$correctos,
      'file'=>$name,'codconcurso' => $concurso->id,'coddistribuidora'=>$coddistribuidora,
      'nombreSustentos'=>$nombreSustentos]);
  }

  public static function cargarCierre2($path,$name,$concurso,$coddistribuidoras,$sustentos){
    // Obteniendo los datos del ejecutivo
    $ejecutivo = Auth::user()->ejecutivo;

    // Obteniendo los datos del excel
    $result = Excel::load($path.'tmp/'.$name, function($reader){})->get();

    foreach($sustentos as $s){
      $sus = new Sustento;
      $sus->file = $s;
      $sus->concurso_id = $concurso->id;
      $sus->ejecutivo_id = $ejecutivo->id;
      $sus->representante_id = $concurso->representante->id;
      $sus->save();
    }

    foreach($result as $data){
      if(
        empty($data->nro_documento) ||
        empty(Supervisor::where('dni','=',(string)$data->nro_documento)->first()) ||
        empty($data->cod_distribuidor) ||
        empty(Distribuidora::where('coddistribuidora','=',(string)$data->cod_distribuidor)->first()) ||
        !in_array($data->cod_distribuidor,$coddistribuidoras) ||
        empty(Cuota::where('concurso_id',$concurso->id)
          ->where('ejecutivo_id',$ejecutivo->id)
          ->where('supervisor_id',Supervisor::where('dni','=',(string)$data->nro_documento)->first()->id)
          ->first())
      ){

      }else{
        $ci = new Cierre;
        $ci->volumen = (string)$data->ventas;
        $ci->cobertura = (string)$data->ptos_coberturados;
        $ci->condicion = $data->condicion;

        $d = Distribuidora::where('coddistribuidora','=',(string)$data->cod_distribuidor)->first();
        $s = Supervisor::where('dni','=',(string)$data->nro_documento)->first();

        $ci->supervisor_id = $s->id;
        $ci->distribuidor_id = $d->id;
        $ci->concurso_id = $concurso->id;
        $ci->ejecutivo_id = $ejecutivo->id;
        $ci->representante_id = $d->representante_id;
        $ci->cuota_id = Cuota::where('concurso_id',$concurso->id)
          ->where('ejecutivo_id',$ejecutivo->id)
          ->where('supervisor_id',Supervisor::where('dni','=',(string)$data->nro_documento)->first()->id)
          ->first()->id;

        $ci->save();
      }
    }
  }
}
