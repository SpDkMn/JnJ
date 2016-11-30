<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      if(Auth::user()->profile->weight == 10){
        return redirect()->route('mantenimiento_representante_view');
      }elseif(Auth::user()->profile->weight == 7){
        return redirect()->route('concurso_view');
      }elseif(Auth::user()->profile->weight == 4){
        return redirect()->route('concursos_view');
      }
    }
}
