<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Representante extends Model
{
    use SoftDeletes;

    protected $table = 'representantes';

    protected $fillable = [
        'codrepresentante', 'codcanal', 'user_id',
    ];
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function user(){
       return $this->belongsTo('App\User','user_id','id');
    }

    public function distribuidoras(){
      return $this->hasMany('App\Distribuidora','representante_id','id');
    }

    public function concursos(){
      return $this->hasMany('App\Concurso','representante_id','id');
    }
}
