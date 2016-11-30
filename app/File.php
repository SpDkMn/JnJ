<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $table = 'files';

    public function distribuidora(){
      return $this->belongsTo('App\Distribuidora', 'distribuidora_id', 'id');
    }

    public function file(){
      return $this->belongsTo('App\File','file_id','id');
    }

    public function files(){
      return $this->hasMany('App\File','file_id','id');
    }
}
