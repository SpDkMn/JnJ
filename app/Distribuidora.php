<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Distribuidora extends Model
{
    use SoftDeletes;

    protected $table = 'distribuidoras';

    protected $fillable = [
      'name','ruc','address','reference','phone','email','coddistribuidora','representante_id','ejecutivo_id','zona'
    ];
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function supervisores(){
      return $this->hasMany('App\Supervisor','distribuidor_id');
    }

    public function representante(){
      return $this->belongsTo('App\Representante', 'representante_id', 'id');
    }
}
