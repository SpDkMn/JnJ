<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendedor extends Model
{
    protected $table = 'vendedores';

    protected $fillable = [
      'user_id','canal','codvendedor','supervisor_id',
    ];

    public function supervisor(){
    	return $this->belongsTo('App\Supervisor','supervisor_id','id');
    }

    public function user(){
       return $this->belongsTo('App\User','user_id','id');
    }

}
