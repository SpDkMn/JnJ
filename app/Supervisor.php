<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supervisor extends Model
{
    use SoftDeletes;

    protected $table = 'supervisores';


    protected $fillable = ['distribuidor_id','dni','name','lastname',
      'lastname2','address','distrito','provincia','departamento','cel_phone',
      'phone','email','cargo'];
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
}
