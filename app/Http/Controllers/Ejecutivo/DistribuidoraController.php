<?php

namespace App\Http\Controllers\Ejecutivo;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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



}
