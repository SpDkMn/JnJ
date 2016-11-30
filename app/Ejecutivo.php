<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ejecutivo extends Model
{
    use SoftDeletes;

    protected $table = 'ejecutivos';

    protected $fillable = [
        'user_id', 'codejecutivo',
    ];
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function  distribuidoras(){
      return $this->hasMany('App\Distribuidora');
    }
}
